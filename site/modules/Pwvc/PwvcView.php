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
    foreach($fuel as $key => $value) $this->set($key, $value);
    $page = $this->page;

  }

  public function ___loadViewFile() {
    $filename = $this->getFilename();
    if(file_exists($filename)) {
      return $filename;
    }
    else {
      return FALSE;
    }
  }

  public function ___buildScope() {
    $scope = array();
    $properties = array(
      $this->getArray(),

    );
    foreach($properties as $propSet) {
      foreach($propSet as $k=>$v) {
        if(array_key_exists($k, $scope)) {
          $scope[$k] = null;
        }
        $scope[$k] = $this->$k;
        unset($v);
      }
    }
    return $scope;
  }

  public function ___importScope(Array $scope) {
    return $this->set('scope', $scope);
  }


  public function ___render($super=false) {
    if(!$this->loadViewFile()) {
      $options = $this->get('options');
      throw new \WireException(sprintf($this->_('Template file for view "' . get_class($this) . '" on route "' . $options['route'] . '" doesn’t exist.')));
    }
    if($super) return parent::___render();
    $renderer = $this->pwvc->getRenderer();
    $controller = &$this->_controller;
    if($result = $controller->action()) {
      // $scope = $this->buildScope();
      $scope = $this->buildScope();
      foreach($result as $k => $v) {
        // $this->set($k, $v);
        $scope[$k] = $v;
      }

      $this->savedDir = getcwd();
      chdir(dirname($this->get('filename')));

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
    else {
      throw new \WireException(sprintf($this->_('Failed to perform action on controller "' . get_class($controller) . '".')));
    }
  }

  public function get($key) {
    $method = 'get' . \PwvcCore::camelcase($key);
    if(method_exists($this, $method)) {
      $result = $this->$method();
    }
    else {
      $result = parent::get($key);
    }
    return $result;
  }

  public function set($key, $value) {
    $method = 'set' . \PwvcCore::camelcase($key);
    if(method_exists($this, $method)) {
      return $this->$method($value);
    }
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

  public function setFilename($filename) {
    $options = $this->get('options');
    $options['filename'] = $filename;
    return parent::setFilename($filename);
  }
  public function getFilename() {
    if($filename = parent::get('filename')) return $filename;
    else return $this->getViewFilename();
  }

  public function setOptions($options) {
    if(array_key_exists('filename', $options)) {
      if($options['filename'] === $this->page->template->filename) {
        unset($options['filename']);
      }
    }
    if(is_object($this->_controller)) {
      $this->_controller->set('options', $options);
    }
    else {
      throw new \WireException('Tried to set options on controller that isn’t set up, yet.');
    }
    return $this;
  }

  public function ___getViewFilename($action = null) {
    if(!$action && $filename = parent::get('filename')) return $filename;
    $filename = $this->pwvc->paths->views;
    $dir = \PwvcCore::sanitizeFilename(get_class($this));
    $filename .= $dir . '/';
    if(!$action) {
      $controller = $this->get('controller');
      $call = $controller->calledAction();
      $action = $call['action'];
    }
    $filename .= $this->pwvc->getFilename('template', $action);
    if(!$action) {
      parent::setFilename($filename);
    }
    return $filename;
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