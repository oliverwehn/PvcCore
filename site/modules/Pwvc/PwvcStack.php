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

  private   $output = null;

  function __construct(\Page $page) {
    parent::__construct();
    $this->_init_stack($page);
  }

  // set up stack
  private function _init_stack(\Page $page, &$errors=array()) {
    $template_name = $page->template->name;
    $classes = array(
      'model' => \PwvcCore::sanitize_classname($template_name, 'model'),
      'controller' => \PwvcCore::sanitize_classname($template_name, 'controller'),
      'view' => \PwvcCore::sanitize_classname($template_name, 'view')
    );
    $init_with = $page;
    foreach($classes as $type => $class) {
      echo "Setting up $class for $type.";

      if($class && !class_exists($class)) { echo " start.";
        $class_file = \PwvcCore::get_class_filename($class);
        $type_plural = $type . 's'; echo " for $type_plural to path "; echo get_class($this->pwvc);
        $class_path = $this->pwvc->paths->$type_plural . $class_file . self::ext($type . 's');
        // check if class file exists
        if(file_exists($class_path)) {
          // yes: include it
          require_once($class_path);
          // check again
          if(!class_exists($class)) {
            $class = null;
          }
        }
        else {
          $base_class = \PwvcCore::sanitize_classname('Pwvc', $type);
          $base_class::extend($class, '$init_with');
        }
      }
      // init class
      $init_with = new $class($init_with);
      echo "setting";
      $this->set($type, $init_with);
    }
    die();
  }

  public function get($key) {
    $method = 'get' . \PwvcCore::camelcase($key);
    if(method_exists($this, $method)) {
      return $this->$method();
    }
    else {
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
  public function setView(PwvcView $view) {
    $this->superSet('view', $view);
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
  public function output($forceNew = false) {
    $view = $this->get('view');
    if($view instanceof PwvcView && !$forceNew) return $view;
    $model = $this->get('model');
    if(!$model->template) return null;
    $controller = $this->get('controller');
    $view = new PwvcView($controller);
    $this->set('view', $view);
    return $view;
  }

}