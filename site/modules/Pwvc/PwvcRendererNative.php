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
namespace Pwvc;

class PwvcRendererNative extends PwvcRenderer {

  const RENDERER_NAME = 'Native';

  protected $templateFuncs;

  public function init() {

  }


  public function ___render(PwvcView $view, Array $scope) {

    extract($scope);

    if(!($templateFuncs = $this->getTemplateFuncs())) {
      $this->setTemplateFuncs($scope);
      $templateFuncs = $this->getTemplateFuncs();
    }
    // extract($templateFuncs);
    foreach($templateFuncs as $k => $f) {
      if(!function_exists($k)) {
        eval('
              function ' . $k . '() {
                $func = __FUNCTION__;
                $args = func_get_args();
                if($pwvc = wire(\'pwvc\')) {
                  $renderer = $pwvc->getRenderer();
                  $tempFunc = $renderer->getTemplateFunc($func);
                  return call_user_func_array($tempFunc, $args);
                }
                else return false;
              }
            ');
      }
    }

    ob_start();

    require($view->filename);

    $out = ob_get_contents();
    ob_end_clean();

    return trim($out);
  }

  public function ___setTemplateFuncs($scope) {
    $this->set('templateFuncs',
      array(
        'snippet' => function($name) use ($scope) {
          $snippetPath = $this->pwvc->paths->snippets . strtolower($name) . $this->pwvc->ext('snippet');
          if(!file_exists($snippetPath)) return false;
          extract($scope);
          ob_start();
          require($snippetPath);
          $out = ob_get_contents();
          ob_end_clean();
          return $out;
        },
        'embed' => function($page) use ($scope) {
          return $this->_embed($page);
        },
        'scripts' => function($group) use ($scope) {
          if(!array_key_exists('assets', $scope)) return false;
          if(!($markup = $this->pwvc->getConfigValue('cfgScriptsMarkup'))) return false;
          $scripts = $this->_extractAssets($scope['assets'], 'scripts', $group);
          $scriptsMarkup = "";
          foreach($scripts as $path) {
            $scriptsMarkup .= sprintf($markup, $path);
          }
          return  $scriptsMarkup;
        },
        'styles' => function($group) use ($scope) {
          if(!array_key_exists('assets', $scope)) return false;
          if(!($markup = $this->pwvc->getConfigValue('cfgStylesMarkup'))) return false;
          $styles = $this->_extractAssets($scope['assets'], 'styles', $group);
          $stylesMarkup = "";
          foreach($styles as $path) {
            $stylesMarkup .= sprintf($markup, $path);
          }
          return  $stylesMarkup;
        }
      )
    );
  }

  public function getTemplateFuncs() {
    return $this->get('templateFuncs');
  }

  public function getTemplateFunc($key) {
    $funcs = $this->getTemplateFuncs();
    if(array_key_exists($key, $funcs)) {
      return $funcs[$key];
    }
    else {
      throw new \WireException(sprintf($this->_('Template function %s() isn’t defined.'), $k));
    }
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