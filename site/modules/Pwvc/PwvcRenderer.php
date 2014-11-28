<?php
/**
 * Pwvc Abstract Renderer Class V. 0.5.1
 * Part of Pwvc, a module for ProcessWire 2.3+
 *
 * by Oliver Wehn
 * https://github.com/oliverwehn
 *
 * All renderer classes extend this class
 * that provides basic functionality.
 *
 */

abstract class PwvcRenderer extends WireData {

  // Name of renderer, used to address it from Pwvc module
  const RENDERER_NAME = '';

  protected $processes = array(),
            $current = null,
            $errors;

  protected static
  $extensions = array(
  );

  public function __construct() {

    $this->init();
  }

  // public function init($settings) {
  public function init() {
    // // check settings
    // $required = array('layout', 'view', 'scope');
    // foreach($required as $r) {
    //   if(!array_key_exists($r, $settings)) throw new \WireException(sprintf($this->_("Required setting '%s' isn’t set."), $r));
    // }
    // // setup new render process entry
    // $process = array(
    //   'layout' => $settings['layout'] !== NULL ? $this->_get_file('layout', $settings['layout']) : NULL,
    //   'view' => $this->_get_file('view', $settings['view']),
    //   'scope' => $settings['scope']
    // );
    // $this->processes[] = $process;
    // $this->current =& $this->processes[count($this->processes) - 1];
    // return $this->current;
  }

  // public function render($process_id=null) {
  //   if(count($this->processes) == 0) throw new \WireException($this->_("No render processes set up for rendering."));
  //   if($process_id !== null) {
  //     if(!isset($this->processes[$process_id])) throw new \WireException(sprintf($this->_("Rendering process with id '%d' doesn’t exist."), $process_id));
  //     $process =& $this->processes[$process_id];
  //   } else {
  //     $process =& $this->current;
  //   }
  //   $out = $this->_process($process);
  //   return $out;
  // }


  protected function _embed($page, $options = array()) {
    if(is_string($options)) $options = array('action' => $options);
    if(is_numeric($page)) {
      $page = $this->pages->get($page);
    } elseif(is_string($page)) {
      if(strpos($page, '=') > 0) {
        $page = $this->pages->get($page);
      }
      elseif(preg_match('#^/?[a-z0-9_\-/]+$#', $page)) {
        $it = $page;
        if(strpos($it, '/') > 0) {
          $it = $this->page->path + '/' + $it;
        }
        $page = $this->pages->get("path={$it}");
        if(!$page->id) {
          $urlSegments = array();
          $maxSegments = $this->config->maxUrlSegments;
          if(is_null($maxSegments)) $maxSegments = 4; // default
          $cnt = 0;

          // if the page isn't found, then check if a page one path level before exists
          // this loop allows for us to have both a urlSegment and a pageNum
          while((!$page || !$page->id) && $cnt < $maxSegments) {
            $it = rtrim($it, '/');
            $pos = strrpos($it, '/')+1;
            $urlSegment = substr($it, $pos);
            $urlSegments[$cnt] = $urlSegment;
            $it = substr($it, 0, $pos); // $it no longer includes the urlSegment
            $page = $this->pages->get("path=$it, status<" . \Page::statusMax);
            $cnt++;
          }
          $options['route'] = '/' . (count($urlSegments) > 0 ? implode('/', $urlSegments) . '/' : '');
          $page = $this->pages->get($page->id);
        }
      }
      else $page = $this->pages->get('name=' . $page);;
    }
    if(!($page instanceof \Page)) return false;
    return $page->render($options);
  }

  protected function _scripts($group) {

  }

  private function _get_file($type, $name) {
    if($path = $this->pwvc->paths->get($type . 's')) {
      $path .= $name . $this->ext($type . 's');
      return $path;
    } else {
      throw new \WireException(sprintf($this->_("Couldn’t get path for '%s' from Pwvc Core."), $type));
    }
  }

  abstract public function ___render(PwvcView $view, Array $scope);

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

  // public function __call($name, $arguments) {
  //   if(method_exists($this, '_get_' . $name)) {
  //     $method = '_get_' . $name;
  //     return $this->$method($arguments[0]);
  //   }
  //   if($name == 'styles' || $name == 'scripts') {
  //     return call_user_func(array($this, '_get_markup'), $name, count($arguments)?$arguments[0]:null);
  //   }
  // }

  protected function _declareTemplateFunc($name) {
    return eval('
      function ' . $name . '() {
        $func = __FUNCTION__;
        $args = func_get_args();
        if($pwvc = wire(\'pwvc\')) {
          $renderer = $pwvc->getRenderer();
          $tempFunc = $renderer->getTemplateFunc($func);
          return call_user_func_array($tempFunc, $args);
        }
        else return false;
      }
    ');
  }

  private function _get_config_value($key) {
    if(!$this->module_config) {
      if(!$this->modules) $this->modules = wire('modules');
      $this->module_config = $this->modules->getModuleConfigData('PwvcCore');
    }
    if(array_key_exists($key, $this->module_config)) return $this->module_config[$key];
    else return FALSE;
  }

  protected function _extractAssets(\WireArray $assets, $type, $group=NULL) {
    $assetsArray = [];
    $assetsOfType = $assets->get($type);
    if($group === NULL) {
      foreach($assetsOfType as $group => $assetItems) {
        $assetsArray = array_merge($assetsArray, $this->_extractAssets($assets, $type, $group));
      }
    }
    else {
      if($assetsOfType->has($group)) {
        $assetsGroup = $assetsOfType->get($group);
        $all = $assetsGroup->getArray();
        usort($all, array($this, '_sortByPriority'));
        foreach($all as $asset) {
          $assetsArray[] = $asset->get('path');
        }
      }
    }
    return $assetsArray;
  }

  private function _sortByPriority($a, $b) {
    $ap = $a->get('priority');
    $bp = $b->get('priority');
    if($ap == $bp) return 0;
    return $ap < $bp?-1:1;
  }
}
