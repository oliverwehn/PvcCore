<?php
/**
 * PWvc Native Renderer Class V. 0.5.0
 * Part of PWvc, a module for ProcessWire 2.3+
 *
 * by Oliver Wehn
 * https://github.com/oliverwehn
 *
 * Renderer that processes templated with good ol’ fashioned
 * PHP and PW API calls.
 *
 */
namespace PWvc;

class PwvcRendererNative extends PwvcRenderer {

  const RENDERER_NAME = 'Native';

  protected function _process($process) {
    $layout_file = $process['layout'];
    $view_file = $process['view'];
    $scope = $process['scope'];

    if($layout_file !== NULL) {
      if(FALSE === ($layout = $this->_setup_template($layout_file, $scope))) {
        throw new \WireException(sprintf($this->_("Wasn’t able to load layout file '%s'"), $process['layout']));
      }
    } else {
      $layout = NULL;
    }
    if(FALSE === ($view = $this->_setup_template($view_file, $scope))) {
      throw new \WireException(sprintf($this->_("Wasn’t able to load view file '%s'"), $process['view']));
    }

    // render view and add to layout
    if(FALSE === ($view_output = $view->render())) {
      throw new \WireException(sprintf($this->_("Failed rendering view for action '%s' in controller '%s'."), $this->action, get_class($this->controller)));
    }

    // render layout or–if no layout set–view output
    if($layout instanceof \TemplateFile) {
      $layout->set('view', $view_output);
      if(FALSE === ($output = $layout->render())) {
        throw new \WireException(sprintf($this->_("Failed rendering layout with controller '%s'."), get_class($this->controller)));
      }
    } else {
      $output = $view_output;
    }
    return $output;
  }

  private function _setup_template($file, $scope) {
    if(!file_exists($file)) return FALSE;
    $template = new \TemplateFile($file);
    foreach($scope as $k=>&$v) {
      $template->set($k, $v);
    }
    $template->set('renderer', $this);
    return $template;
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
