<?php
/**
 * Pvc AppController Class V. 1.0.0
 * Part of Pvc, a module for ProcessWire 2.4+
 *
 * by Oliver Wehn
 * https://github.com/oliverwehn
 *
 * Basic AppController that extends PvcController class.
 * General helper methods and properties can be declared here.
 * All template controllers should extend AppController and
 * will inherit methods and properties of AppController.
 *
 */

class AppController extends PvcController {

  public function init() {
    parent::init();

    $this->_add_scripts();
    $this->_add_styles();
  }

  /**
   * Setup helpers
   */

  private function _add_scripts() {
    $this->script('http://cdn.foundation5.zurb.com/foundation.js', 'header', 0);
  }

  private function _add_styles() {
    $this->style('http://cdn.foundation5.zurb.com/foundation.css', 'header', 0);
  }

}
