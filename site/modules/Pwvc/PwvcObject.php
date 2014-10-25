<?php
/**
 * PWvc Object Class V. 0.1.0
 * Part of PWvc, a module for ProcessWire 2.4+
 *
 * by Oliver Wehn
 * https://github.com/oliverwehn
 * *
 * Basic object class that is extended by Models and Controllers and
 * so provides access to properties besides API vars and
 * methods. Don’t modifiy.
 *
 */
namespace PWvc;

abstract class PwvcObject extends \WireData {

  public function __construct() {

  }

  public function get($key) {
    $result = NULL;
    $value = parent::get($key);
    if($value !== NULL) {
      $result = is_callable($value) ? $value($this) : $value;
    }
    else {
      $method = 'get' . \PwvcCore::camelcase($key);
      if(method_exists($this, $method)) {
        $refl_meth = new \ReflectionMethod($this, $method);
        $result = $refl_meth->isPublic() ? $this->$method() : NULL;
      }
    }
    return $result;
  }

  public function set($key, $value) {
    $curr_value = parent::get($key);
    if($curr_value !== NULL) {
      if(is_callable($curr_value)) {
        $curr_value($this, $value);
        return $this;
      }
    }
    else {
      $class = get_class($this);
      $method = 'set' . \PwvcCore::camelcase($key);
      if(method_exists($this, $method)) {
        $refl_meth = new \ReflectionMethod($this, $method);
        if($refl_meth->isPublic()) {
          $this->$method($value);
          return $this;
        }
      }
    }
    return parent::set($key, $value);
  }

  public function superGet($key) {
    return parent::get($key);
  }
  public function superSet($key, $value) {
    return parent::set($key, $value);
  }


  public function __get($key) {
    return $this->get($key);
  }
  public function __set($key, $value) {
    return $this->set($key, $value);
  }

  public static function extend($className) {
    $args = func_get_args();
    $initWith = array();
    foreach($args as $i=>$v) {
      if($i > 0) {
        $initWith[] = $v;
      }
    }
    $classCode = "
    namespace PWvc;
    class " . preg_replace('#^' . __NAMESPACE__ . '\\\#', '', $className) . " extends " . preg_replace('#^' . __NAMESPACE__ . '\\\#', '', get_called_class()) . " {
      public function __constructor(" . implode(', ', $initWith) .") {
        parent::__constructor(" . implode(', ', $initWith) .");
      }
    }
    ";
    eval($classCode);
  }

}