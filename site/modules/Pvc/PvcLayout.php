<?php
/**
 * Pvc Layout Class V. 0.9.0
 * Part of Pvc, a module for ProcessWire 2.5+
 *
 * by Oliver Wehn
 * https://github.com/oliverwehn
 *
 * View class extends PvcView class.
 * Don’t modifiy.
 *
 */
class PvcLayout extends PvcView {

  public function ___loadLayoutFile() {
    $filename = $this->getLayoutFilename();
    if(file_exists($filename)) {
      \TemplateFile::__construct($filename);
      return $filename;
    }
    else {
      return FALSE;
    }
  }
  public function ___loadViewFile($action=null) { return $this->loadLayoutFile(); }


  public function ___getLayoutFilename($layout_name=NULL) {
    $path = $this->pvc->paths->layouts;
    if(!$layout_name) $layout_name = $this->_controller->get('layout');
    $path .= $this->pvc->getFilename($layout_name, 'template');
    return $path;
  }
  public function ___getViewFilename($template_name=null, $action = null) { return $this->getLayoutFilename($template_name=null); }

  public function ___render($super=false) {
    if($super) return \TemplateFile::___render();

    $renderer = $this->pvc->getRenderer();
    if(!($scope = $this->get('scope'))) {
      $scope = $this->buildScope();
    }
    $this->savedDir = getcwd();

    chdir(dirname($this->filename));

    $out = "\n" . $renderer->render($this, $scope) . "\n";

    if($this->savedDir) chdir($this->savedDir);

    return $out;
  }

}