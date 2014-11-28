<?php
/**
 * Pwvc Model Class V. 0.1.0
 * Part of Pwvc, a module for ProcessWire 2.4+
 *
 * by Oliver Wehn
 * https://github.com/oliverwehn
 * *
 * Model class that wraps PW’s API page and
 * so provides access to fuel vars besides basic module-specific
 * methods. Don’t modifiy.
 *
 */
class PwvcModel extends PwvcObject {

  protected $_page = null;


  public function __construct(\Page $page) {
    $this->set('_page', $page);
  }

  /**
   *
   */
  public function get($key) {
    $result = parent::get($key);
    return $result ? $result : $this->_page->get($key);
  }

  public function set($key, $value) {
    $page = &$this->_page;
    if($page && $page->get($key) !== NULL) {
      return $page->set($key, $value);
    }
    return parent::set($key, $value);
  }

  public function getPage() {
    return $this->_page;
  }
  public function setPage(\Page $page) {
    $this->_page = $page;
    return $this;
  }

  public function __call($method, $arguments) {
    try {
      $reflMeth = new \ReflectionMethod($this->_page, $method);
      return $reflMeth->isPublic() ? call_user_func(array(&$this->_page, $method), $arguments) : FALSE;
    } catch(\Exception $e) {
      throw new \WireException(sprintf($this->_('An error occured: %s'), $e));
    }
  }

  public static function __callStatic($name, $arguments) {
    return call_user_func_array('\Page::' . $name, $arguments);
  }

}