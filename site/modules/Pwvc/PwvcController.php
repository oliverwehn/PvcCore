<?php
/**
 * Pwvc Controller Class V. 0.9.0
 * Part of Pwvc, a module for ProcessWire 2.3+
 *
 * by Oliver Wehn
 * https://github.com/oliverwehn
 *
 * inspired of and based on parts of MVC Module by Harmster
 * https://github.com/Hawiak
 * hawiak.nl
 *
 * Basic Controller class that extends PW’s Wire class and
 * so provides access to fuel vars besides basic module-specific
 * methods. Don’t modifiy.
 *
 */
namespace Pwvc;

class PwvcController extends \WireData {

  const DEFAULT_ACTION = 'index';

  protected $_model;

  protected $routes = array();

  /**
   * Initialization and setup
   */
  public function __construct(\Page $model) {
    $this->set('model', $model);
    $this->set('assets', new \WireArray);
    $this->init();
  }

  public function init() {
    $this->set('layout', \PwvcCore::DEFAULT_LAYOUT);
  }

  public function get($key) {
    switch($key) {
      case 'model': {
        return $this->_model;
        break;
      }
      default: {
        if(strpos($key, '.') !== FALSE) {
          $keySegments = explode('.', $key);
          $keySegmentsCount = count($keySegments);
          $i = 0;
          $element = $this;
          do {
            $element = $element->get($keySegments[$i]);
            if($i < $keySegmentsCount - 1 && !method_exists($result, 'get')) {
              throw new \WireException(sprintf($this->_('"%s" has no method "get" to get key "%s".'), implode('.', $keySegmentsPath), $keySegmentsPath[$i+1]));
            }
            $i++;
          }
          while($i < $keySegmentsCount);
          return $element;
        }
        else {
          return parent::get($key);
        }
      }
    }
  }

  public function set($key, $value) {
    switch($key) {
      case 'options': {
        return $this->setOptions($value);
        break;
      }
      case 'model': {
        $this->_model = $value;
        return $this;
        break;
      }
      default: {
        if(strpos($key, '.') !== FALSE) {
          $keySegments = explode('.', $key);
          $keySegmentsCount = count($keySegments);
          $keySegmentsPath = array();
          $i = 0;
          $element = $this;
          do {
            $element = $element->get($keySegments[$i]);
            $keySegmentsPath[] = $keySegments[$i];
            if(!method_exists($element, 'get')) {
              throw new \WireException(sprintf($this->_('"%s" has no method "get" to get key "%s".'), implode('.', $keySegmentsPath), $keySegmentsPath[$i+1]));
            }
            $i++;
          }
          while($i < $keySegmentsCount - 1);
          return $element->set($keySegments[$i], $value);
        }
        else {
          return parent::set($key, $value);
        }
      }
    }
  }

  public function setOptions(Array $options) {
    $currOptions = $this->get('options');
    if(!is_array($currOptions)) {
      $currOptions = array();
    }
    if(array_key_exists('pageStack', $options) && count($options['pageStack']) == 0) {
      if(!array_key_exists('route', $options)) {
        $urlSegments = array();
        if($this->input->urlSegments) {
          for($i=0, $cnt=$this->input->urlSegments->count; $i<$cnt; $i++) {
            $urlSegments[] = $this->input->urlSegments($i);
          }
        }
        $options['route'] = '/' . (count($urlSegments) > 0 ? implode('/', $urlSegments) . '/' : '');
      }
    }
    $options = array_merge($currOptions, $options);
    return parent::set('options', $options);
  }

  public function action($call=NULL) {
    if(!$call) $call = $this->calledAction();
    if(!is_array($call)) $call = array('action' => $call, 'input' => array());
    if(count($call['input']))
      $this->input->route = new \WireInputData($call['input']);
    if($this->call($call['action'])) {
      $result = array();
      if($model = $this->get('model')) {
        $modelScope = $model->getArray();
        foreach($modelScope as $k => $v) {
          $result[$k] = $v;
        }
      }
      $controllerScope = $this->getArray();
      foreach($controllerScope as $k => $v) {
        $result[$k] = $v;
      }
      return $result;
    }
    return false;
  }

  public function validateAction($action) {
    if(!is_string($action)) return FALSE;
    $reflMeth = new \ReflectionMethod($this, $action);
    if($reflMeth->isPublic()) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  public function call($action) {
    if(!$this->validateAction($action)) throw new \WireException(sprintf($this->_('Called invalid action "%s" on controller "%s".'), $action, get_class($this)));
    call_user_func(array(&$this, $action));
    return true;
  }

  public function route($path, array $match, $method) {
    // alias could be '/:id/edit/'
    // should match '/17/edit/'
    // route to action 'actionEdit' with input id=>17 available
    if(!preg_match("#^/(([a-z0-9\-_:]+(/|$))+)$#i", $path, $pathMatch))
      throw new \WireException(sprintf($this->_("Path '%s' is not valid for route alias."), $path));
    if(!method_exists($this, $method))
      throw new \WireException(sprintf($this->_("Tried to set up a route to method '%s' which doesn’t exist."), $method));
    $pathElements = explode("/", rtrim($pathMatch[1], '/'));
    $route = '#^' . preg_quote(rtrim($path, '/')) . '(/(page[0-9]+/?)?)?$#';
    $keys = array();
    $i = 0;
    do {
      $i++;
      $start = mb_strpos($route, ':');
      if($start !== FALSE) {
        if($start == mb_strlen($route) - 1) break;
        $end = mb_strpos($route, '/', $start);
        $key = mb_substr($route, $start + 1, $end - ($start + 1));
        $keys[$i] = $key;
        $route = str_replace(preg_quote(':'.$key), "(".$match[$key].")", $route);
      }
    } while($start !== FALSE);
    $pattern = array(
      'keys' => $keys,
      'action' => $method
      );
    $this->routes[$route] = $pattern;
    return $route;
  }

  public function calledAction() {
    if($result = $this->get('calledAction')) return $result;
    if(!($options = $this->get('options'))) $options = array('route' => '/');
    $result = array('action' => self::DEFAULT_ACTION, 'input' => array());
    if(array_key_exists('action', $options)) {
      $result['action'] = $options['action'];
    }
    elseif(array_key_exists('route', $options)) {
      $route = $options['route'];
      if($resolved = $this->routeToAction($route)) {
        $result['action'] = $resolved['action'];
        $result['input'] = $resolved['input'];
      }
      else {
        throw new \WireException(sprintf($this->_('Route "%s" isn’t valid for Page "%s".'), $route, $this->get('page')->path));
      }
    }
    $this->set('calledAction', $result);
    return $result;
  }

  public function routeToAction($route="/") {
    $result = array('action' => self::DEFAULT_ACTION, 'input' => array());
    if($route === '/') return $result;
    $route_segments = explode('/', rtrim(ltrim($route, '/'), '/'));
    if(count($route_segments) > 0) {
      // maybe a regular action?
      $action = implode('_', $route_segments);
      if($this->validateAction($action)) $result['action'] = $action;
      // or more special? Check for patterns
      if(count($this->routes) > 0) {
        foreach($this->routes as $pattern => $route_def) {
          if(preg_match($pattern, $route, $matches)) {
            $action = $route_def['action'];
            $input = array();
            $cnt_matches = count($matches);
            for($j=1; $j<$cnt_matches; $j++) {
              if(isset($route_def['keys'][$j])) {
                $input[$route_def['keys'][$j]] = $matches[$j];
              } else {
                break;
              }
            }
            $result['action'] = $action;
            $result['input'] = $input;
          }
        }
      }
    }
    return $result;
  }

  /* Add a script to be available to the view
   * @method script
   */
  public function script($name, $group="base", $prio=0) {
    return $this->_setAsset('scripts', $group, $prio, $name);
  }
  /* Add a stylesheet to be available to the view
   * @method script
   */
  public function style($name, $group="base", $prio=0) {
    return $this->_setAsset('styles', $group, $prio, $name);
  }
  /* Internal asset setter method
   * @method script
   */
  private function _setAsset($type, $group, $prio, $name) {
    $assets = $this->get('assets');
    if(!$assets->has($type)) $assets->set($type, new \WireArray);
    $assetsForType = $assets->get($type);
    if(!$assetsForType->has($group)) $assetsForType->set($group, new \WireArray);
    $assetsGroup = $assetsForType->get($group);
    $newAsset = new \WireData;
    $newAssetPath = preg_match("#^(http:)?//#i", $name) ? $name : $this->pwvc->urls->$type . $name;
    $newAsset->set('path', $newAssetPath)->set('priority', $prio);
    $assetsGroup->set($name, $newAsset);
    return $assetsGroup->count;
  }
  private function _getAsset($type, $group, $name) {
    $assets = $this->get('assets');
    if(!(array_key_exists($type, $assets)) || !(array_key_exists($group, $assets[$type])) || !(array_key_exists($name, $assets[$type][$group])))
      return false;
    else
      return $assets[$type][$group][$name];
  }

  // default action
  public function index() {

  }

  /*
  public function set($var, $val, $context = 'root') {
    if(!array_key_exists($context, $this->scope)) $this->scope[strval($context)] = array();
    if(($context == 'root') && (property_exists($this, $var))) parent::set($var, $val);
    else $this->scope[$context][$var] = $val;
  }

  public function get($var, $context = 'root') {
    if(!array_key_exists($context, $this->scope)) return FALSE;
    if(($context == 'root') && (property_exists($this, $var))) return parent::get($var);
    else if(array_key_exists($var, $this->scope[$context])) return $this->scope[$context][$var];
    else return FALSE;
  }

  public function build_scope($context = 'root', $only=FALSE) {
    if(!array_key_exists($context, $this->scope)) return array();
    // start with fuel scrope
    if(!$only) {
      $scope = array_merge($this->scope['fuel'], $this->scope['root']);
    } else {
      $scope = array();
    }
    // add context scope
    if($only || ($context != 'fuel' && $context != 'root'))
      $scope = array_merge($scope, $this->scope[$context]);
    // controller vars

    return $scope;
  }
  */

  public function build_scope($options = NULL) {
    $scope = array('options' => $options);
    foreach($this->fuel as $k=>&$v) {
      $scope[$k] = $v;
      unset($v);
    }
    foreach($this->data as $k=>&$v) {
      $scope[$k] = $v;
      unset($v);
    }
    $controller_vars = get_object_vars($this);
    $sort = array('scripts', 'styles');
    foreach($controller_vars as $k=>&$v) {
      if(in_array($k, $sort)) {
        foreach($v as $group=>$entries) {
          usort($entries, array($this, '_sort_by_priority'));
          $temp_v = array();
          foreach($entries as $entry)
            $temp_v[] = $entry[0];
          $scope[$k][$group] = $temp_v;
        }
      } else {
        $scope[$k] = $v;
      }
      unset($v);
    }
    return $scope;
  }

  public function add_style($style_path, $priority=0, $group_name=NULL) {
    if(!is_string($style_path)) return FALSE;
    $priority = intval($priority);
    $group_name = $group_name?strval($group_name):'styles_' . count($this->styles);
    if(!array_key_exists($group_name, $this->styles)) $this->styles[$group_name] = array();
    $return = array($style_path, $priority, $group_name);
    $styles =& $this->styles[$group_name];
    foreach($styles as &$style) {
      if($style[0] == $style_path) {
        $style_path[1] = $priority;
        return $return;
      }
    }
    $styles[] = array($style_path, $priority);
    return $return;
  }

  public function get_styles($group_name=NULL) {
    $styles = array();
    if((is_string($group_name)) && (array_key_exists($group_name, $this->styles))) {
      $group = array_merge(array(), $this->styles[$group_name]);
      usort($group, array($this, '_sort_by_priority'));
      foreach($group as $style) $styles[] = $style[0];
    }
    else {
      foreach($this->styles as $group_name => $group) {
        $styles = array_merge($styles, $this->get_styles($group_name));
      }
    }
    return $styles;
  }

  public function add_script($script_path, $priority=0, $group_name=NULL) {
    if(!is_string($script_path)) return FALSE;
    $priority = intval($priority);
    $group_name = $group_name?strval($group_name):'scripts_' . count($this->scripts);
    if(!array_key_exists($group_name, $this->scripts)) $this->scripts[$group_name] = array();
    $return = array($script_path, $priority, $group_name);
    $scripts =& $this->scripts[$group_name];
    foreach($scripts as &$script) {
      if($script[0] == $script_path) {
        $script_path[1] = $priority;
        return $return;
      }
    }
    $scripts[] = array($script_path, $priority);
    return $return;
  }

  public function get_scripts($group_name=NULL) {
    $scripts = array();
    if((is_string($group_name)) && (array_key_exists($group_name, $this->scripts))) {
      $group = array_merge(array(), $this->scripts[$group_name]);
      usort($group, array($this, '_sort_by_priority'));
      foreach($group as $style) $scripts[] = $style[0];
    }
    else {
      $scripts = array();
      foreach($this->scripts as $group) {
        usort($group, array($this, '_sort_by_priority'));
        foreach($group as $script) $scripts[] = $script[0];
      }
    }
    return $scripts;
  }

  protected function _sort_by_priority($a, $b) {
    if($a[1] == $b[1]) return 0;
    return $a[1] < $b[1]?-1:1;
  }


  protected function _ext($type, $subtype=NULL) {
    return ProcessPwvc::ext($type, $this->pwvc->template_engine);
  }
}
