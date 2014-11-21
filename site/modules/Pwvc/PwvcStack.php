<?php
/**
 * Pwvc Stack Class V. 0.1.0
 * Part of Pwvc, a module for ProcessWire 2.4+
 *
 * by Oliver Wehn
 * https://github.com/oliverwehn
 * *
 * Basic object class that is extended by Models and Controllers and
 * so provides access to properties besides API vars and
 * methods. Don’t modifiy.
 *
 */
namespace Pwvc;

class PwvcStack extends PwvcObject {

  function __construct(\Page $page) {
    parent::__construct();
    $this->_initStack($page);

  }

  // set up stack
  private function _initStack(\Page $page) {
    $templateName = $page->template->name;
    $stack = array('model', 'controller', 'view');
    $initWith = $page;
    foreach($stack as $layer) {
      $class = \PwvcCore::getClassName($templateName, $layer);
      $this->superSet($layer . 'Class', $class);
      if($class && !class_exists($class)) {
        $classFile = \PwvcCore::getFilename($layer, $class);
        $layerPlural = $layer . 's';
        $classPath = $this->pwvc->paths->$layerPlural . $classFile;
        // check if class file exists
        if(file_exists($classPath)) {
          // yes: include it
          require_once($classPath);
        }
        // check again
        if(!class_exists($class)) {
          // fall back to creating class on demand
          $baseClass = \PwvcCore::getClassName('Pwvc', $layer);
          $baseClass::extend($class, '$initWith');
        }
      }
      // initiate class
      $instance = new $class($initWith);
      // add to stack
      $this->set($layer, $layer !== 'view' ? $instance : NULL);
      $initWith = $this->get($layer);
    }
  }

  public function get($key) {
    if(property_exists(__CLASS__, $key)) {
      $result = $this->$key;
    }
    else {
      $method = 'get' . \PwvcCore::camelcase($key);
      if(method_exists($this, $method)) {
        $result = $this->$method();
      }
      else {
        $result = $this->superGet($key);
        if(!$result) {
          $model = $this->get('model');
          $result = $model->get($key);
        }
      }
    }
    return $result;
  }

  public function set($key, $value) {
    $method = 'set' . \PwvcCore::camelcase($key);
    if(method_exists($this, $method)) {
      $this->$method($value);
    }
    else {
      $model = $this->get('model');
      $model->set($key, $value);
    }
    return $this;
  }

  public function __call($method, $arguments) {
    $model = $this->get('model');
    return call_user_func(array(&$model, $method), $arguments);
  }

  // public function getRoute() {
  //   $route = '/';
  //   if($action) {
  //     // $rou
  //   }
  //   if($this->validateAction($action)) {
  //     if($action !== self::DEFAULT_ACTION) {
  //       if(strpos($action, '_'))
  //         $action = implode('/', explode('_', $action));
  //       $route .= $action . '/';
  //     }
  //   } else {
  //     // get route from urlSegements
  //     $i = 0;
  //     $routeSegments = array();
  //     while(isset($this->input->urlSegments[$i+1])) {
  //       $i++;
  //       $routeSegments[] = $this->input->urlSegments[$i];
  //     }
  //     if($i > 0)
  //       $route .= implode('/', $routeSegments) . '/';
  //   }
  //   return $route;
  // }

  public function setModel(PwvcModel $model) {
    $this->superSet('model', $model);
    return $this;
  }
  public function getModel() {
    return $this->superGet('model');
  }
  public function setController(PwvcController $controller) {
    $this->superSet('controller', $controller);
    return $this;
  }
  public function getController() {
    return $this->superGet('controller');
  }
  public function setView($view) {
    if($view instanceof PwvcView || $view === NULL) {
      $this->superSet('view', $view);

    }
    else {
      throw new \WireException($this->_('Invalid value or view: Has to be an instance of PwvcView or NULL. Was ' . gettype($view) . '.'));
    }
    return $this;
  }
  public function getView() {
    return $this->superGet('view');
  }

  /**
   * Render page output, replaces $page->output()
   * @method output
   * @param Page $page Object of page to be rendered
   * @param bool $forceNew Forces it to return a new (non-cached) TemplateFile object (default=false)
   * @return PwvcView (extends TemplateFile)
   */
  public function output($forceNew = FALSE) {
    $view = $this->get('view');
    // use existing view, when existing and !$forceNew
    if($view instanceof PwvcView && !$forceNew) return $view;
    // else create a new view
    $model = $this->get('model');
    if(!$model->template) return NULL;
    $controller = $this->get('controller');
    // set up view
    $viewClass = $this->superGet('viewClass');
    $view = new $viewClass($controller);
    // check if view file exists
    $this->set('view', $view);
    return $view;
  }

}