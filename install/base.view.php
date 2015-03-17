<?php
/**
 * Pvc BaseView Class V. 1.0.0
 * Part of Pvc, a module for ProcessWire 2.4+
 *
 * by Oliver Wehn
 * https://github.com/oliverwehn/PvcCore
 *
 * Basic view that extends PvcView class.
 * You can introduce general view logic here
 * like custom view helpers.
 * Make sure your template specific views
 * extend BaseView class to keep your helpers
 * and logic available to all views!
 *
 */

class BaseView extends PvcView {

  /**
   * Add custom view helpers that will be available as
   * global functions within your templates.
   * @method customViewHelpers
   * @param Array $scope contains an associative array of template scope
   * @returns Array An associative array with key/closure pairs
   */
  public function customViewHelpers($scope) {
    return array(
      /**
       * Each helper is defined as a pair of key (name of the helper
       * function in template context) and closure (function body)
       * Via use() you can pass in $scope and custom values to be
       * available within your helper.
       * The helper below can be called like this: <?=sayHi('your name');?>
       *
      'sayHi' => function($name) use($scope) {
        return sprintf("Hi %s, welcome to my article \"%s\"!", $name, $scope['title']);
      }
       */
    );
  }

}