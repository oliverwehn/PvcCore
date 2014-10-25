<?php
/**
 * PWvc Stack Class V. 0.1.0
 * Part of PWvc, a module for ProcessWire 2.4+
 *
 * by Oliver Wehn
 * https://github.com/oliverwehn
 * *
 * Basic object class that is extended by Models and Controllers and
 * so provides access to properties besides API vars and
 * methods. Don’t modifiy.
 *
 */
namespace PWvc;

class PwvcStack extends PwvcObject {

  function __construct(\Page $page) {
    parent::__construct();
    $this->_init_stack($page);
  }

  // set up stack
  private function _init_stack(\Page $page, &$errors=array()) {
    $template_name = $page->template->name;
    $stack = array('model', 'controller', 'view');
    $init_with = $page;
    foreach($stack as $layer) {
      $class = \PwvcCore::get_classname($template_name, $layer);
      $this->superSet($layer . 'Class', $class);
      if($class && !class_exists($class)) {
        $class_file = \PwvcCore::get_filename($layer, $class);
        $layer_plural = $layer . 's';
        $class_path = $this->pwvc->paths->$layer_plural . $class_file;
        // check if class file exists
        if(file_exists($class_path)) {
          // yes: include it
          require_once($class_path);
        }
        // check again
        if(!class_exists($class)) {
          // fall back to creating class on demand
          $base_class = \PwvcCore::get_classname('Pwvc', $layer);
          $base_class::extend($class, '$init_with');
        }
      }
      // initiate class
      $instance = new $class($init_with);
      // add to stack
      $this->set($layer, $layer !== 'view' ? $instance : NULL);
      $init_with = $this->get($layer);
    }
  }

  public function get($key) {
    if(property_exists(__CLASS__, $key)) {
      return $this->$key;
    }
    $method = 'get' . \PwvcCore::camelcase($key);
    if(method_exists($this, $method)) {
      return $this->$method();
    }
    else {
      $result = $this->superGet($key);
      if($result) {
        return $result;
      }
      $model = $this->get('model');
      return $model->get($key);
    }
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
    if($view->loadViewFile()) {
      $this->set('view', $view);
      return $view;
    }
    // if no view file was found, $view === NULL, so don’t use view
    return NULL;
  }

}