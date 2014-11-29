<?php
/**
 * Pwvc Native Renderer Class V. 0.5.0
 * Part of Pwvc, a module for ProcessWire 2.3+
 *
 * by Oliver Wehn
 * https://github.com/oliverwehn
 *
 * Renderer that processes templated with good ol’ fashioned
 * PHP and PW API calls.
 *
 */

class PwvcRendererNative extends PwvcRenderer {

  const RENDERER_NAME = 'Native';

  protected $templateFuncs;

  public function init() {

  }


  public function ___render(PwvcView $view, Array $scope, $filename=NULL) {
    if($filename !== NULL) {
      if(!file_exists($filename)) throw new WireException(sprintf($this->_('"%s" is not a valid view file.'), $filename));
    }
    else {
      $filename = $view->filename;
    }
    foreach($scope as $k=>&$v) {
      if($v instanceof \Closure) {
        $$k = $v();
      }
      else {
        $$k = $v;
      }
      unset($v);
    }
    // declare view helpers as global functions
    $viewHelpers = $view->getViewHelpers($scope);
    $this->set('helpers', $viewHelpers);
    foreach($viewHelpers as $name => $method) {
      if(!function_exists($name)) {
        $this->_declareHelper($name);
      }
    }

    ob_start();

    require($filename);

    $out = ob_get_contents();
    ob_end_clean();

    return trim($out);
  }

  protected function _process_snippet($snippet_path) {
    $globals = $this->current['scope'];
    $renderer =& $this;
    extract($globals);
    ob_start();
    include($snippet_path);
    $out = ob_get_contents();
    ob_end_clean();
    return $out;
  }

}
