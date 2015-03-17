<?php
/**
 * Pvc Abstract Renderer Class V. 0.5.1
 * Part of Pvc, a module for ProcessWire 2.4+
 *
 * by Oliver Wehn
 * https://github.com/oliverwehn
 *
 * All renderer classes extend this class
 * that provides basic functionality.
 *
 */

abstract class PvcRenderer extends WireData implements Module, ConfigurableModule {

  // Name of renderer, used to address it from Pvc module
  const RENDERER_NAME = '';

  protected $processes = array(),
            $current = null,
            $errors;

  protected static
  $extensions = array(
  );

  public function __construct() {

    $this->init();
  }

  public function init() {

  }

  abstract public function ___render(PvcView $view, Array $scope);

  protected function _declareHelper($name) {
    if(!is_string($name)) return false;
    eval('
      function ' . $name . '() {
        $func = __FUNCTION__;
        $args = func_get_args();
        if($pvc = wire(\'pvc\')) {
          $renderer = $pvc->getRenderer();
          $helper = $renderer->getHelper($func);
          return call_user_func_array($helper, $args);
        }
        else return false;
      }
    ');
    return function_exists($name);
  }

  public function getHelpers() {
    return $this->get('helpers');
  }

  public function setHelper($name, \Closure $closure) {
    $helpers =& $this->data['helpers'];
    if(!array_key_exists($name, $helpers)) {
      $helpers[$name] = $closure;
      return $name;
    }
    else {
      return false;
    }
  }

  public function getHelper($name) {
    $helpers = $this->getHelpers();
    if(array_key_exists($name, $helpers)) {
      return $helpers[$name];
    }
    else {
      throw new \WireException(sprintf($this->_('Helper %s() isn’t defined.'), $name));
    }
  }

  public static function ext($of) {
    $extensions = static::$extensions;
    if(!array_key_exists($of, $extensions)) return FALSE;
    else return $extensions[$of];
  }

  /**
   * Configuration
   */
  static public function getModuleConfigInputfields(array $data) {
    $pvc = wire('pvc');

    $form = new InputfieldForm();
    $form->attr('id', 'pvc-renderer');

    return $form;
  }

}
