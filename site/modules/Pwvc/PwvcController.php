<?php
/**
 * PWvc Controller Class V. 0.9.0
 * Part of PWvc, a module for ProcessWire 2.3+
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
namespace PWvc;

class PwvcController extends PwvcObject {

  const DEFAULT_ACTION = 'index';

  protected $_model;

  protected $layout = NULL;
  protected $scripts = array();
  protected $styles = array();
  protected $routes = array();

  /**
   * Initialization and setup
   */
  public function __construct(PwvcModel $model) {
    $this->set('_model', $model);
    $this->init();
  }

  public function init() {
    $this->set('layout', \PwvcCore::DEFAULT_LAYOUT);
  }

  public function get($key) {
    $result = $this->_model->get($key);
    return $result ? $result : parent::get($key);
  }

  public function set($key, $value) {
    $model = &$this->_model;
    if($model && $model->get($key) !== NULL) {
      return $model->set($key, $value);
    }
    return parent::set($key, $value);
  }

  public function getModel() {
    return $this->_model;
  }

  public function setModel(PwvcModel $model) {
    $this->_model = $model;
    return $this;
  }

  public function action($action=NULL) {
    if(!$action) $action = $this->calledAction();
    if(!method_exists($this, $action)) throw new \WireException(sprintf($this->_('Called invalid action "%s" on controller "%s".'), $action, get_class($this)));
    $refl_meth = new \ReflectionMethod($this, $action);
    if(!$refl_meth->isPublic()) throw new \WireException(sprintf($this->_('No public action method "%s" found on controller "%s".'), $action, get_class($this)));
    return $this->$action();
  }

  public function calledAction() {
    return 'index';
  }

  /**
   * Execution
   */
  public function execute($options) {
    // get route
    $force_action = NULL;
    if(array_key_exists('action', $options))
      $force_action = $options['action'];
    else
      $force_action = self::DEFAULT_ACTION;
    $route = $this->get_route($force_action);
    // get action
    $action = $this->get_action($route);
    // execute action
    $this->$action($options);
    // package stuff for renderer
    $result = array(
      'layout' => $this->layout,
      'view' => $this->pwvc->get_controller_filename(get_class($this)) . '/' . $action,
      'scope' => $this->build_scope($options)
    );
    return $result;
  }

  public function get_route($action = NULL) {
    $route = '/';
    if($this->validate_action($action)) {
      if($action !== self::DEFAULT_ACTION) {
        if(strpos($action, '_'))
          $action = implode('/', explode('_', $action));
        $route .= $action . '/';
      }
    } else {
      // get route from urlSegements
      $i = 0;
      $route_segments = array();
      while(isset($this->input->urlSegments[$i+1])) {
        $i++;
        $route_segments[] = $this->input->urlSegments[$i];
      }
      if($i > 0)
        $route .= implode('/', $route_segments) . '/';
    }
    return $route;
  }

  public function validate_action($action) {
    if(!is_string($action)) return FALSE;
    try {
      $refl_meth = new \ReflectionMethod($this, $action);
    } catch(\Exception $e) {
      //printf($this->_("Class '%s' has no action '%s'."), get_class($this), $action);
      return FALSE;
    }
    if($refl_meth->isPublic()) {
      return TRUE;
    } else {
      return FALSE;
    }
  }


  public function set_route_pattern($path, array $match, $method) {
    // alias could be '/{id}/edit/'
    // should match '/17/edit/'
    // route to action 'action-edit' with input id=>17 available
    if(!preg_match("#^/(([a-z0-9\-_\{\}]+(/|$))+)$#i", $path, $path_match))
      throw new \WireException(sprintf($this->_("Path '%s' is not valid for route alias."), $path));
    if(!method_exists($this, $method))
      throw new \WireException(sprintf($this->_("Tried to set up a route to method '%s' which doesn’t exist."), $method));
    $path_elements = explode("/", rtrim($path_match[1], '/'));
    $route = '#^' . preg_quote(rtrim($path, '/')) . '(/(page[0-9]+/?)?)?$#';
    $keys = array();
    $i = 0;
    do {
      $i++;
      $start = mb_strpos($route, '{');
      if($start !== FALSE) {
        if($start == mb_strlen($route) - 1) break;
        $end = mb_strpos($route, '}', $start);
        $key = mb_substr($route, $start + 1, $end - ($start + 2));
        $keys[$i] = $key;
        $route = str_replace(preg_quote('{'.$key.'}'), "(".$match[$key].")", $route);
      }
    } while($start !== FALSE);
    $pattern = array(
      'keys' => $keys,
      'action' => $method
      );
    $this->routes[$route] = $pattern;
    return $route;
  }

  public function get_action($route) {
    $route_segments = explode('/', rtrim(ltrim($route, '/'), '/'));
    if(count($route_segments) > 1) {
      // maybe a regular action?
      $action = implode('_', $route_segments);
      if($this->validate_action($action)) return $action;
      // or more special? Check for patterns
      if(count($this->routes) > 0) {
        foreach($this->routes as $pattern => $route_def) {
          if(preg_match($pattern, $route, $matches)) {
            $action = $route_def['action'];
            $arguments = array();
            $cnt_matches = count($matches);
            for($j=1; $j<$cnt_matches; $j++) {
              if(isset($route_def['keys'][$j])) {
                $arguments[$route_def['keys'][$j]] = $matches[$j];
              } else {
                break;
              }
            }
            $this->input->route = new \WireInputData($arguments);
            return $action;
          }
        }
      }
    }
    return self::DEFAULT_ACTION;
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
