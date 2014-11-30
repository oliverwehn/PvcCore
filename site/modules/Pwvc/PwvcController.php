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

class PwvcController extends WireData {

  const DEFAULT_ACTION = 'index';

  protected $_page;

  protected $routes = array();

  /**
   * Initialization and setup
   */
  public function __construct(PwvcPageProxy $page) {
    $this->set('page', $page);
    $this->set('assets', new WireArray);
    $this->init();
  }

  public function init() {
    $this->set('layout', PwvcCore::DEFAULT_LAYOUT);
  }

  public function get($key) {
    switch($key) {
      case 'page': {
        return $this->_page;
        break;
      }
      default: {
        $method = 'get' . PwvcCore::camelcase($key);
        if(method_exists($this, $method)) {
          return $this->$method();
        }
        return parent::get($key);
      }
    }
  }

  public function set($key, $value) {
    switch($key) {
      case 'page': {
        $this->_page = $value;
        return $this;
        break;
      }
      default: {
        $method = 'set' . PwvcCore::camelcase($key);
        if(method_exists($this, $method)) {
          return $this->$method($value);
        }
        return parent::set($key, $value);
      }
    }
  }

  public function execute($action, $input=array()) {
    if(count($input))
      $this->input->route = new WireInputData($input);
    if($this->call($action)) {
      $result = array('action' => $action);
      if($page = $this->get('page')) {
        $pageScope = $page->getArray();
        foreach($pageScope as $k => $v) {
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
    if(!(is_string($action)) || !(method_exists($this, $action))) return FALSE;
    $reflMeth = new ReflectionMethod($this, $action);
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

  public function calledAction($justName=false) {
    if((!$justName) && ($action = $this->get('calledAction'))) return $action;
    if(!($options = $this->get('options'))) $options = array('route' => '/');
    $actionName = self::DEFAULT_ACTION;
    $actionInput = array();
    if(array_key_exists('action', $options)) {
      $actionName = $options['action'];
    }
    elseif(array_key_exists('route', $options)) {
      $route = $options['route'];
      if($resolved = $this->routeToAction($route)) {
        $actionName = $resolved['action'];
        $actionInput = $resolved['input'];
      }
      else {
        throw new \WireException(sprintf($this->_('Route "%s" isn’t valid for Page "%s".'), $route, $this->get('page')->path));
      }
    }
    if(!$justName) {
      $self = $this;
      $action = function() use($self, $actionName, $actionInput) {
        return call_user_func(array(&$self, 'execute'), $actionName, $actionInput);
      };
      $this->set('calledAction', $action);
    }
    else {
      $action = $actionName;
    }
    return $action;
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

  protected function _sortByPriority($a, $b) {
    if($a[1] == $b[1]) return 0;
    return $a[1] < $b[1]?-1:1;
  }


  protected function _ext($type, $subtype=NULL) {
    return ProcessPwvc::ext($type, $this->pwvc->template_engine);
  }

  public static function extend($className) {
    $classCode = '
    class '. $className . ' extends ' . get_called_class() . ' {
      public function __constructor(PwvcPageProxy $page) {
        parent::__constructor($page);
      }
    }
    ';
    eval($classCode);
    return class_exists($className);
  }
}
