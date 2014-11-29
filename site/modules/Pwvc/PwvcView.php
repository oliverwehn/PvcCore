<?php
/**
 * Pwvc View Class V. 0.9.0
 * Part of Pwvc, a module for ProcessWire 2.5+
 *
 * by Oliver Wehn
 * https://github.com/oliverwehn
 *
 * View class extends PW’s native TemplateFile class.
 * Don’t modifiy.
 *
 */

class PwvcView extends TemplateFile {

  protected $_controller = null;

  /**
   * Construct the view from template name
   *
   * @param PwvcController $controller template-specific controller object
   *
   */
  public function __construct(PwvcController $controller) {
    $this->set('_controller', $controller);
    $fuel = self::getAllFuel();
    $this->set('wire', $fuel);
    foreach($fuel as $key => $value) $this->set($key, $value);
    $page = $this->page;

  }

  public function ___loadViewFile($action='index') {
    $filename = $this->getViewFilename($action);
    if(file_exists($filename)) {
      $this->setFilename($filename);
      return $filename;
    }
    else {
      throw new Wire404Exception(sprintf($this->_('View file "%s" was not found.'), $filename));
    }
  }

  public function ___buildScope(Array $actionScope) {
    $scope = array();
    $properties = array(
      $this->getArray(),

    );
    foreach($properties as $propSet) {
      foreach($propSet as $k=>$v) {
        if(array_key_exists($k, $scope)) {
          $scope[$k] = null;
        }
        $scope[$k] = $this->$k;
        unset($v);
      }
    }
    foreach($actionScope as $k => $v) {
      $scope[$k] = $v;
    }

    return $scope;
  }

  public function ___importScope(Array $scope) {
    return $this->set('scope', $scope);
  }


  public function ___render($super=false) {
    $controller = $this->get('controller');
    $action = $controller->calledAction();
    if($super) return parent::___render();
    $renderer = $this->pwvc->getRenderer();
    if($actionScope = $action()) {
      $actionName = $actionScope['action'];
      if($this->loadViewFile($actionName)) {
        // $scope = $this->buildScope();
        $scope = $this->buildScope($actionScope);

        $this->savedDir = getcwd();
        chdir(dirname($this->get('filename')));

        $out = "\n" . $renderer->render($this, $scope) . "\n";
        $options = $this->get('options');
        if(count($options['pageStack']) == 0) {
          $layoutName = $this->_controller->get('layout');
          if($layoutName != NULL) {
            $layout = $this->_initLayout($layoutName);
            if($layout->loadLayoutFile()) {
              $scope['outlet'] = $out;
              $layout->importScope($scope);
              $out = $layout->render();
            }
          }
        }

        if($this->savedDir) chdir($this->savedDir);

        return $out;
      }
      return false;
    }
    else {
      throw new WireException(sprintf($this->_('Failed to perform action on controller "' . get_class($controller) . '".')));
    }
  }

  public function get($key) {
    switch($key) {
      case 'fuel': {
        return parent::get($key);
        break;
      }
      case 'filename': {
        return parent::get($key);
        break;
      }
      default: {
        $method = 'get' . PwvcCore::camelcase($key);
        if(method_exists($this, $method)) {
          return $this->$method();
        }
        return parent::get($key);
      }
    }
  }

  public function set($key, $value) {
    switch($key) {
      case 'fuel': {
        return parent::set($key, $value);
        break;
      }
      default: {
        $method = 'set' . PwvcCore::camelcase($key);
        if(method_exists($this, $method)) {
          return $this->$method($value);
        }
        return parent::set($key, $value);
      }
    }
  }

  public function setGlobal($key, $value, $override = false) {
    return parent::setGlobal($key, $value, $override);
  }

  public function getController() {
    return $this->_controller;
  }

  public function setController(PwvcController $controller) {
    $this->_controller = $controller;
    return $this;
  }

  public function setFilename($filename) {
    $options = $this->get('options');
    $options['filename'] = $filename;
    return parent::setFilename($filename);
  }

  public function setOptions($options) {
    if(array_key_exists('filename', $options)) {
      if($options['filename'] === $this->page->template->filename) {
        unset($options['filename']);
      }
    }
    if(is_object($this->_controller)) {
      $this->_controller->set('options', $options);
    }
    else {
      throw new WireException('Tried to set options on controller that isn’t set up, yet.');
    }
    return $this;
  }

  public function getOptions() {
    if(is_object($this->_controller)) {
      return $this->_controller->get('options');
    }
    return false;
  }

  public function ___getViewFilename($action = null) {
    if(!$action && $filename = parent::get('filename')) return $filename;
    $filename = $this->pwvc->paths->views;
    $dir = PwvcCore::sanitizeFilename(get_class($this));
    $filename .= $dir . '/';
    $filename .= PwvcCore::getFilename('template', $action);
    return $filename;
  }

  protected function _initLayout($layoutName) {
    $class = PwvcCore::getClassname($layoutName, 'layout');
    if($class && !class_exists($class)) {
      $classFile = PwvcCore::getFilename('layout', $class);
      $classPath = $this->pwvc->paths->layouts . $classFile;
      // check if class file exists
      if(file_exists($classPath)) {
        // yes: include it
        require_once($classPath);
      }
      // check again
      if(!class_exists($class)) {
        // fall back to creating class on demand
        $base_class = PwvcCore::getClassname('Pwvc', 'layout');
        $base_class::extend($class, '$controller');
      }
    }
    // initiate class
    $instance = new $class($this->_controller);
    return $instance;
  }


  /*************************************************************************
   * View helper definition
   *************************************************************************/

  public function getViewHelpers($scope) {
    $helpers = array(
      'snippet' => function($name) use ($scope) {
        $renderer = $this->pwvc->getRenderer();
        $snippetPath = $this->pwvc->paths->snippets . strtolower($name) . $this->pwvc->ext('snippet');
        if($renderer instanceof PwvcRenderer) {
          return $renderer->render($this, $scope, $snippetPath);
        }
        return sprintf($this->_('Snippet "%s" can’t be found.'), $snippetPath);
      },
      'embed' => function($page) use ($scope) {
        return $this->_embed($page);
      },
      'scripts' => function($group) use ($scope) {
        return $this->_assets($scope['assets'], 'scripts', $group);
      },
      'styles' => function($group) use ($scope) {
        return $this->_assets($scope['assets'], 'styles', $group);
      }
    );
    if(method_exists($this, 'customViewHelpers')) {
      $customHelpers = $this->customViewHelpers($scope);
      if(is_array($customHelpers)) {
        $helpers = array_merge($customHelpers, $helpers);
      }
    }
    return $helpers;
  }

  /*************************************************************************
   * View helper logic
   *************************************************************************/

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

  public function _assets($assetsArr, $type, $group) {
    $type = rtrim($type, 's') . 's';
    if(!($markup = $this->pwvc->getConfigValue(sprintf('cfg%sMarkup', $this->pwvc->camelcase($type))))) return false;
    $assets = $this->_extractAssets($assetsArr, $type, $group);
    $assetsMarkup = "";
    foreach($assets as $path) {
      $assetsMarkup .= sprintf($markup, $path);
    }
    return $assetsMarkup;
  }


  public function _snippet($snippet_name) {
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

  protected function _extractAssets(\WireArray $assets, $type, $group=NULL) {
    $assetsArray = [];
    if($assets->has($type)) {
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
    }
    return $assetsArray;
  }

  private function _sortByPriority($a, $b) {
    $ap = $a->get('priority');
    $bp = $b->get('priority');
    if($ap == $bp) return 0;
    return $ap < $bp?-1:1;
  }

  /*************************************************************************
   * General helpers
   *************************************************************************/

  public static function extend($className) {
    $classCode = '
    class '. $className . ' extends ' . get_called_class() . ' {
      public function __constructor(PwvcController $controller) {
        parent::__constructor($controller);
      }
    }
    ';
    eval($classCode);
    return class_exists($className);
  }
}