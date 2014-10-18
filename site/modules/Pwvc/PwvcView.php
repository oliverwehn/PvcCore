<?php
/**
 * PWvc View Class V. 0.9.0
 * Part of PWvc, a module for ProcessWire 2.5+
 *
 * by Oliver Wehn
 * https://github.com/oliverwehn
 *
 * View class extends PW’s native TemplateFile class.
 * Don’t modifiy.
 *
 */
namespace PWvc;

class PwvcView extends \TemplateFile {

  protected $_controller;


  /**
   * Construct the view from template name
   *
   * @param string $template_name Page’s template name
   *
   */
  public function __construct(PwvcController $controller) {
    $this->set('_controller', $controller);
    $filename = $this->pwvc->get_controller_filename(get_class($this));
    if($filename) $this->setFilename($filename);

    $fuel = self::getAllFuel();
    $this->set('wire', $fuel);
    foreach($fuel as $key => $value) $this->set($key, $value);
    $this->output->set('page', $page);

  }

  public function get($key) {
    $result = $this->_controller->get($key);
    return $result ? $result : parent::get($key);
  }

  public function set($key, $value) {
    $controller = &$this->_controller;
    if($controller && $controller->get($key) !== NULL) {
      return $controller->set($key, $value);
    }
    return parent::set($key, $value);
  }

  public function getController() {
    return $this->_controller;
  }

  public function setController(PwvcController $controller) {
    $this->_controller = $controller;
    return $this;
  }
}