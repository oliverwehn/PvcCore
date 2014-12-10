<?php

class PvcPageProxy extends WireData {

  protected $_page = null;

  public function __construct(Page $page) {
    $this->_page = &$page;
    $view = $this->_setupView();
    // get action and store view file name as altFilename with page’s template
    // (just to make viewable check pass)
    $controller = $view->get('controller');
    $action = $controller->calledAction(true);
    $filename = $view->getViewFilename($action);
    $page->template->set('filename', $filename);
    $page->template->set('altFilename', '');

    return $this->_page->id;
  }


  /**
   * Render page output, replaces $page->output()
   * @method output
   * @param bool $forceNew Forces it to return a new (non-cached) TemplateFile object (default=false)
   * @return PvcView (extends TemplateFile)
   */
  public function output($forceNew=true) {
    $view = $this->get('output');
    // use existing view, when existing and !$forceNew
    if(!($view instanceof PvcView && !$forceNew)) {
      $view = $this->_setupView();
    }
    return $view;
  }

  private function _setupView() {
    $page = $this->_page;
    $templateName = $page->template->name;
    // save working dir
    $saveDir = getcwd();
    chdir(dirname(getenv('SCRIPT_FILENAME')));
    // get controller class
    $controllerClass = PvcCore::getClassName($templateName, 'controller');
    $controllerFilename = PvcCore::getConfigValue('cfgControllersPath') . PvcCore::getFilenameFromClass($controllerClass);
    if(file_exists($controllerFilename)) require_once($controllerFilename);
    if(!((class_exists($controllerClass)) || (PvcController::extend($controllerClass)))) {
      throw new WireException(sprintf($this->_('Wasn’t able to set up controller "%s".'), $controllerClass));
    }
    $controller = new $controllerClass($this);
    // get base view class
    $baseViewClass = PvcCore::getClassName('base', 'view', false);
    $extendBaseViewClass = true;
    if(!class_exists($baseViewClass)) {
      $baseViewFilename = PvcCore::getConfigValue('cfgViewsPath') . PvcCore::getFilenameFromClass($baseViewClass, false);
      if(file_exists($baseViewFilename)) require_once($baseViewFilename);
      if(!class_exists($baseViewClass)) {
        $extendBaseViewClass = PvcView::extend($baseViewClass);
      }
      else $extendBaseViewClass = false;
    }
    // get view class
    $viewClass = PvcCore::getClassName($templateName, 'view');
    if(!class_exists($viewClass)) {
      $viewFilename = PvcCore::getConfigValue('cfgViewsPath') . PvcCore::getFilenameFromClass($viewClass);
      if(file_exists($viewFilename)) require_once($viewFilename);
      if(!class_exists($viewClass)) {
        if($extendBaseViewClass)
            $extended = call_user_func($baseViewClass . '::extend', $viewClass);
        else
          $extended = PvcView::extend($viewClass);
        if(!$extended) {
          throw new WireException(sprintf($this->_('Wasn’t able to set up view "%s".'), $viewClass));
        }
      }
    }
    $view = new $viewClass($controller);
    // store view instead of template file with page
    $page->set('output', $view);
    // restore working dir
    chdir($saveDir);
    // return view
    return $view;
  }


  public function __get($key) {
    return $this->_page->get($key);
  }
  public function __set($key, $value) {
    $this->_page->set($key, $value);
    return $this;
  }
  public function __call($method, $arguments=null) {
    return call_user_func_array(array(&$this->_page, $method), $arguments);
  }
}