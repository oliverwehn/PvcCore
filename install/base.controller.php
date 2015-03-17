<?php
/**
 * Pvc BaseController Class V. 1.0.0
 * Part of Pvc, a module for ProcessWire 2.4+
 *
 * by Oliver Wehn
 * https://github.com/oliverwehn/PvcCore
 *
 * Basic BaseController that extends PvcController class.
 * General helper methods and properties can be declared here.
 * All template controllers should extend BaseController and
 * will inherit methods and properties of BaseController.
 *
 */

class BaseController extends PvcController {

  /**
   * Do general controller setup here.
   * Like setting scripts and styles to be accessible in
   * all views/layouts. Or setting another layout than 'default'.
   */
  public function init() {
    /**
     * Layouts have to be present in 'site/templates/layouts/'.
     * Set layout like:
     * $this->set('layout', 'my-layout');
     */

    // Custom helpers
    $this->_add_scripts();
    $this->_add_styles();
  }

  /**
   * Create your custom helpers
   */
  private function _add_scripts() {
    /**
     * Add script files to assets using script() method of controllers like:
     * $this->script({path}, {group}, {priority});
     * If {path} is no url, file is looked up in 'site/templates/assets/scripts/'.
     * Example: $this->script('vendor/jquery.1.8.4.js', 'bottom', 0);
     * {group} allows you to output bundles of assets in templates/layouts using
     * view helpers like <?=scripts({group});?>. {priority} determines the
     * order in which the asset links are put out.
     */
    $this->script('http://cdn.foundation5.zurb.com/foundation.js', 'header', 0);
  }

  private function _add_styles() {
    /**
     * Add style files to assets using style() method of controllers like:
     * $this->style({path}, {group}, {priority});
     * If {path} is no url, file is looked up in 'site/templates/assets/styles/'.
     * Example: $this->styles('vendor/foundation.css', 'bottom', 0);
     */
    $this->style('http://cdn.foundation5.zurb.com/foundation.css', 'header', 0);
  }

}
