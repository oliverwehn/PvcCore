<?php

class HomeController extends AppController {

  /**
   * For general controller setup use init() method
   *
  public init() {
    // Set a different layout for this page
    // Layout must exist in 'site/templates/layouts/'
    $this->set('layout', 'shop');

    // Set action route to assign patterns with dynamic elements
    // to action methods providing dynamic parts in $this->input->route->{name}.
    // Example calls method edit() and provides $this->input->route->id
    $this->route('/edit/:id/', array('id'=>'[0-9]+'), 'edit');
  }
   */

  /**
   * 'index' is default action to performed on page view
   */
  public function index() {
    /**
     * Set a value to be available in template
     * $this->set('name', $value);
     *
     * Set a dynamic value to be calculated on render
     * $this->set('oneWeekLater', function() {
     *  $date = $this->page->get('date');
     *  $ts = strtotime($date) + 7 * 86400;
     *  return date('c', $ts);
     * });
     *
     */
  }

  /**
   * Create public methods as actions addressable via urlSegments
   * '/edit/' -> method 'edit'
   * '/comments/new/' -> method 'commentsNew'
   * Provide a view file for each action in corresponding view folder
  public function edit() {
    // Input from action route defined in init()
    $id = $this->input->route->id;
  }
   */
}
?>
