<?php
/**
 * Pwvc View Class V. 0.9.0
 * Part of Pwvc, a module for ProcessWire 2.5+
 *
 * by Oliver Wehn
 * https://github.com/oliverwehn
 *
 * View class extends PW’s native TemplateFile class.
 * Don’t modifiy.
 *
 */
namespace Pwvc;

class PwvcView extends \TemplateFile {

  protected $_controller;

  /**
   * Construct the view from template name
   *
   * @param PwvcController $controller template-specific controller object
   *
   */
  public function __construct(PwvcController $controller) {
    $this->set('_controller', $controller);
    $fuel = self::getAllFuel();
    $this->set('wire', $fuel);
    foreach($fuel as $key => $value) $this->superSet($key, $value);
    $page = $this->page;

  }

  public function ___loadViewFile() {
    $filename = $this->getViewFilename();
    if(file_exists($filename)) {
      parent::__construct($filename);
      return $filename;
    }
    else {
      return FALSE;
    }
  }

  public function ___buildScope($layer = NULL, $stack = NULL) {
    $scope = array();
    if($layer === NULL) {
      $layer = $this;
      $stack = array('view', 'controller', 'model', 'page');
      array_shift($stack);
      $scope = array_merge($this->buildScope($this, $stack), $scope);
    }
    else {
      if($layer instanceof PwvcView) {
         $properties = $layer->wire->getArray();
      }
      else {
        $properties = $layer->getArray();
      }
      foreach($properties as $k=>$v) {
        if(array_key_exists($k, $scope)) {
          $scope[$k] = null;
        }
        $scope[$k] = $this->$k;
        unset($v);
      }
      if(count($stack) >= 1) {
        $nextLayer = '_' . array_shift($stack);
        $scope = array_merge($this->buildScope($layer->$nextLayer, $stack), $scope);
      }
    }

    return $scope;
  }

  public function ___importScope(Array $scope) {
    return $this->set('scope', $scope);
  }


  public function ___render($super=false) {
    if($super) return parent::___render();

    $renderer = $this->pwvc->getRenderer();
    $controller = &$this->_controller;
    $controller->action();
    $scope = $this->buildScope();
    $this->savedDir = getcwd();

    chdir(dirname($this->filename));

    $out = "\n" . $renderer->render($this, $scope) . "\n";

    if(count($this->options['pageStack']) == 0) {
      $layoutName = $this->_controller->get('layout');
      if($layoutName != NULL) {
        $layout = $this->_initLayout($layoutName);
        if($layout->loadLayoutFile()) {
          $scope['outlet'] = $out;
          $layout->importScope($scope);
          $out = $layout->render();
        }
      }
    }

    if($this->savedDir) chdir($this->savedDir);

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
    return $result ? $result : $this->superGet($key);
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
    return $this->superSet($key, $value);
  }

  public function superGet($key) {
    return parent::get($key);
  }
  public function superSet($key, $value) {
    return parent::set($key, $value);
  }

  public function setGlobal($key, $value, $override = false) {
    return parent::setGlobal($key, $value, $override);
  }

  public function getController() {
    return $this->_controller;
  }

  public function setController(PwvcController $controller) {
    $this->_controller = $controller;
    return $this;
  }

  public function ___getViewFilename($templateName=null, $action = null) {
    $path = $this->pwvc->paths->views;
    $dir = \PwvcCore::sanitizeFilename($templateName ? $templateName : get_class($this));
    $path .= $dir . '/';
    if(!$action) {
      $controller = $this->get('controller');
      $call = $controller->calledAction();
      $action = $call['action'];
    }
    $path .= $this->pwvc->getFilename('template', $action);
    return $path;
  }

  protected function _initLayout($layoutName) {
    $class = \PwvcCore::getClassname($layoutName, 'layout');
    if($class && !class_exists($class)) {
      $classFile = \PwvcCore::getFilename('layout', $class);
      $classPath = $this->pwvc->paths->layouts . $classFile;
      // check if class file exists
      if(file_exists($classPath)) {
        // yes: include it
        require_once($classPath);
      }
      // check again
      if(!class_exists($class)) {
        // fall back to creating class on demand
        $base_class = \PwvcCore::getClassname('Pwvc', 'layout');
        $base_class::extend($class, '$controller');
      }
    }
    // initiate class
    $instance = new $class($this->_controller);
    return $instance;
  }


  public static function extend($className) {
    $args = func_get_args();
    $initWith = array();
    foreach($args as $i=>$v) {
      if($i > 0) {
        $initWith[] = $v;
      }
    }
    $classCode = "
    namespace Pwvc;
    class " . preg_replace('#^' . __NAMESPACE__ . '\\\#', '', $className) . " extends " . preg_replace('#^' . __NAMESPACE__ . '\\\#', '', get_called_class()) . " {
      public function __constructor(" . implode(', ', $initWith) .") {
        parent::__constructor(" . implode(', ', $initWith) .");
      }
    }
    ";
    eval($classCode);
  }
}