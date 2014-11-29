<?php

class PwvcPageProxy extends WireData {

  protected $_page = null;

  public function __construct(Page $page) {
    $this->_page = $page;
    return $this->_page->id;
  }


  /**
   * Render page output, replaces $page->output()
   * @method output
   * @param bool $forceNew Forces it to return a new (non-cached) TemplateFile object (default=false)
   * @return PwvcView (extends TemplateFile)
   */
  public function output($forceNew=true) {
    $page = $this->_page;
    $templateName = $page->template->name;
    $view = $page->get('output');
    // use existing view, when existing and !$forceNew
    if(!($view instanceof PwvcView && !$forceNew)) {
      $saveDir = getcwd();
      chdir(dirname(getenv('SCRIPT_FILENAME')));
      // else create a new view
      $controllerClass = PwvcCore::getClassName($templateName, 'controller');
      $controllerFilename = PwvcCore::getConfigValue('cfgControllersPath') . PwvcCore::getFilename('controller', $controllerClass);
      if(file_exists($controllerFilename)) require_once($controllerFilename);
      $viewClass = PwvcCore::getClassName($templateName, 'view');
      $viewFilename = PwvcCore::getConfigValue('cfgViewsPath') . PwvcCore::getFilename('view', $viewClass);
      if(file_exists($viewFilename)) require_once($viewFilename);

      if(!((class_exists($controllerClass)) || (PwvcController::extend($controllerClass)))) {
        throw new WireException(sprintf($this->_('Wasn’t able to set up controller "%s".'), $controllerClass));
      }
      $controller = new $controllerClass($this);
      if(!((class_exists($viewClass)) || (PwvcView::extend($viewClass)))) {
        throw new WireException(sprintf($this->_('Wasn’t able to set up view "%s".'), $viewClass));
      }
      $view = new $viewClass($controller);
      // check if view file exists
      $page->set('output', $view);
      chdir($saveDir);
    }
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
    return call_user_func(array(&$this->_page, $method), $arguments);
  }
}