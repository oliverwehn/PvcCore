<?php
/**
 * PWvc View Class V. 0.9.0
 * Part of PWvc, a module for ProcessWire 2.5+
 *
 * by Oliver Wehn
 * https://github.com/oliverwehn
 *
 * View class extends PW’s native TemplateFile class.
 * Don’t modifiy.
 *
 */
namespace PWvc;

class PwvcView extends \TemplateFile {

  protected $_controller;

  /**
   * Construct the view from template name
   *
   * @param string $template_name Page’s template name
   *
   */
  public function __construct(PwvcController $controller) {
    $this->set('_controller', $controller);
    $fuel = self::getAllFuel();
    $this->set('wire', $fuel);
    foreach($fuel as $key => $value) $this->set($key, $value);
    $this->output->set('page', $this->page);

  }

  public function loadViewFile() {
    $filename = $this->getViewFilename();
    if(file_exists($filename)) {
      parent::__construct($filename);
      return $filename;
    }
    else {
      return FALSE;
    }
  }

  public function ___render() {
    $out = parent::___render();
    return $out;
  }

  public function get($key) {
    $result = $this->_controller->get($key);
    if($result === NULL) {
      $method = 'get' . \PwvcCore::camelcase($key);
      if(method_exists($this, $method)) {
        $result = $this->$method();
      }
    }
    return $result ? $result : parent::get($key);
  }

  public function set($key, $value) {
    $controller = &$this->_controller;
    if($controller && $controller->get($key) !== NULL) {
      return $controller->set($key, $value);
    }
    else {
      $method = 'set' . \PwvcCore::camelcase($key);
      if(method_exists($this, $method)) {
        return $this->$method($value);
      }
    }
    return parent::set($key, $value);
  }

  public function getController() {
    return $this->_controller;
  }

  public function setController(PwvcController $controller) {
    $this->_controller = $controller;
    return $this;
  }

  public function getViewFilename($template_name=null, $action = null) {
    $path = $this->pwvc->paths->views;
    $dir = \PwvcCore::sanitize_filename($template_name ? $template_name : get_class($this));
    $path .= $dir . '/';
    if(!$action) {
      $controller = $this->get('controller');
      $action = $controller->calledAction();
    }
    $path .= $this->pwvc->get_filename('view', $action);
    return $path;
  }


  public static function extend($class_name) {
    $args = func_get_args();
    $init_with = array();
    foreach($args as $i=>$v) {
      if($i > 0) {
        $init_with[] = $v;
      }
    }
    $class_code = "
    namespace PWvc;
    class " . preg_replace('#^' . __NAMESPACE__ . '\\\#', '', $class_name) . " extends " . preg_replace('#^' . __NAMESPACE__ . '\\\#', '', get_called_class()) . " {
      public function __constructor(" . implode(', ', $init_with) .") {
        parent::__constructor(" . implode(', ', $init_with) .");
      }
    }
    ";
    eval($class_code);
  }
}