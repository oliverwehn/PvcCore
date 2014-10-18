<?php
/**
 * PWvc Abstract Renderer Class V. 0.5.1
 * Part of PWvc, a module for ProcessWire 2.3+
 *
 * by Oliver Wehn
 * https://github.com/oliverwehn
 *
 * All renderer classes extend this class
 * that provides basic functionality.
 *
 */
namespace PWvc;

abstract class PwvcRenderer extends \WireData {

  // Name of renderer, used to address it from PWvc module
  const RENDERER_NAME = '';

  protected $processes = array(),
            $current = null,
            $errors;

  protected static
  $extensions = array(
    'controllers' => '.php',
    'layouts' => '.php',
    'views' => '.view.php',
    'snippets' => '.snippet.php'
    );

  public function __construct() {
  }

  public function init($settings) {
    // check settings
    $required = array('layout', 'view', 'scope');
    foreach($required as $r) {
      if(!array_key_exists($r, $settings)) throw new \WireException(sprintf($this->_("Required setting '%s' isn’t set."), $r));
    }
    // setup new render process entry
    $process = array(
      'layout' => $settings['layout'] !== NULL ? $this->_get_file('layout', $settings['layout']) : NULL,
      'view' => $this->_get_file('view', $settings['view']),
      'scope' => $settings['scope']
    );
    $this->processes[] = $process;
    $this->current =& $this->processes[count($this->processes) - 1];
    return $this->current;
  }

  public function render($process_id=null) {
    if(count($this->processes) == 0) throw new \WireException($this->_("No render processes set up for rendering."));
    if($process_id !== null) {
      if(!isset($this->processes[$process_id])) throw new \WireException(sprintf($this->_("Rendering process with id '%d' doesn’t exist."), $process_id));
      $process =& $this->processes[$process_id];
    } else {
      $process =& $this->current;
    }
    $out = $this->_process($process);
    return $out;
  }

  private function _get_file($type, $name) {
    if($path = $this->pwvc->paths->get($type . 's')) {
      $path .= $name . $this->ext($type . 's');
      return $path;
    } else {
      throw new \WireException(sprintf($this->_("Couldn’t get path for '%s' from PWvc Core."), $type));
    }
  }

  abstract protected function _process($process);

  public static function ext($of) {
    $extensions = static::$extensions;
    if(!array_key_exists($of, $extensions)) return FALSE;
    else return $extensions[$of];
  }

  private function _get_markup($type, $group=NULL, $process_id = NULL) {
    if($process_id !== NULL) {
      if(!array_key_exists($process_id, $this->processes)) return FALSE;
      $process = $this->processes[$process_id];
    } else {
      $process = $this->current;
    }
    if(FALSE === ($markup = $this->_get_config_value('cfg_' . $type . '_markup'))) return FALSE;
    if(!array_key_exists($type, $process['scope'])) return FALSE;
    $groups = $process['scope'][$type];
    $output = '';
    if($group !== NULL) {
      if(!array_key_exists($group, $groups)) return FALSE;
      foreach($groups[$group] as $url) {
        $output .= sprintf($markup, $url)."\n";
      }
    } else {
      foreach($groups as $group) {
        foreach($group as $url)
          $output .= sprintf($markup, $url)."\n";
      }
    }
    return $output;
  }

  public function _get_snippet($snippet_name) {
    $snippet_file = $this->pwvc->paths->snippets . $snippet_name . $this->ext('snippets');
    if(!file_exists($snippet_file)) return FALSE;
    if(method_exists($this, '_process_snippet')) return $this->_process_snippet($snippet_file);
    else return implode('', file($snippet_file));

  }

  public function _get_embed($page, $action=null, $vars=null) {
    if(!($page instanceof Page)) {
      if(!is_string($page)) return FALSE;
      if($page = $this->pages->get($page)) return $this->_get_embed($page);
      else return FALSE;
    }
    if($page->template->name == $this->page->template->name) throw new \WireException(sprintf($this->__("Embedding pages of same template into each other prohibited.")));
    $page->set('embedded', TRUE)->set('embedded_into', $this->page);
    if(is_string($action))
      $page->set('force_action', $action);
    if(is_array($vars)) {
      foreach($vars as $var=>$val)
        $page->set($var, $val);
    }
    return $page->render();
  }

  public function __call($name, $arguments) {
    if(method_exists($this, '_get_' . $name)) {
      $method = '_get_' . $name;
      return $this->$method($arguments[0]);
    }
    if($name == 'styles' || $name == 'scripts') {
      return call_user_func(array($this, '_get_markup'), $name, count($arguments)?$arguments[0]:null);
    }
  }

  private function _get_config_value($key) {
    if(!$this->module_config) {
      if(!$this->modules) $this->modules = wire('modules');
      $this->module_config = $this->modules->getModuleConfigData('PwvcCore');
    }
    if(array_key_exists($key, $this->module_config)) return $this->module_config[$key];
    else return FALSE;
  }
}
