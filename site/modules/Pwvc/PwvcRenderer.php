<?php
/**
 * Pwvc Abstract Renderer Class V. 0.5.1
 * Part of Pwvc, a module for ProcessWire 2.3+
 *
 * by Oliver Wehn
 * https://github.com/oliverwehn
 *
 * All renderer classes extend this class
 * that provides basic functionality.
 *
 */

abstract class PwvcRenderer extends WireData {

  // Name of renderer, used to address it from Pwvc module
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

  // public function init($settings) {
  public function init() {
    // // check settings
    // $required = array('layout', 'view', 'scope');
    // foreach($required as $r) {
    //   if(!array_key_exists($r, $settings)) throw new \WireException(sprintf($this->_("Required setting '%s' isn’t set."), $r));
    // }
    // // setup new render process entry
    // $process = array(
    //   'layout' => $settings['layout'] !== NULL ? $this->_get_file('layout', $settings['layout']) : NULL,
    //   'view' => $this->_get_file('view', $settings['view']),
    //   'scope' => $settings['scope']
    // );
    // $this->processes[] = $process;
    // $this->current =& $this->processes[count($this->processes) - 1];
    // return $this->current;
  }

  // public function render($process_id=null) {
  //   if(count($this->processes) == 0) throw new \WireException($this->_("No render processes set up for rendering."));
  //   if($process_id !== null) {
  //     if(!isset($this->processes[$process_id])) throw new \WireException(sprintf($this->_("Rendering process with id '%d' doesn’t exist."), $process_id));
  //     $process =& $this->processes[$process_id];
  //   } else {
  //     $process =& $this->current;
  //   }
  //   $out = $this->_process($process);
  //   return $out;
  // }


  abstract public function ___render(PwvcView $view, Array $scope);

  protected function _declareHelper($name) {
    if(!is_string($name)) return false;
    eval('
      function ' . $name . '() {
        $func = __FUNCTION__;
        $args = func_get_args();
        if($pwvc = wire(\'pwvc\')) {
          $renderer = $pwvc->getRenderer();
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

}
