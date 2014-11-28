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
      // else create a new view
      $controllerClass = PwvcCore::getControllerName($templateName);
      if(!((class_exists($controllerClass)) || (PwvcController::extend($controllerClass)))) {
        throw new WireException(sprintf($this->_('Wasn’t able to set up controller "%s".'), $controllerClass));
      }
      $controller = new $controllerClass($this);
      $view = new PwvcView($controller);
      // check if view file exists
      $page->set('output', $view);
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