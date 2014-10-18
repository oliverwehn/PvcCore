<?php
/**
 * PWvc Controller Class V. 0.9.0
 * Part of PWvc, a module for ProcessWire 2.3+
 * 
 * by Oliver Wehn
 * https://github.com/oliverwehn
 *
 * inspired of and based on parts of MVC Module by Harmster 
 * https://github.com/Hawiak
 * hawiak.nl
 *
 * Basic Controller class that extends PW’s Wire class and 
 * so provides access to fuel vars besides basic module-specific
 * methods. Don’t modifiy.
 *
 */
namespace PWvc;

class Controller extends \Wire {
	protected $layout;
	protected $view;
	protected $scripts = array();
	protected $styles = array(); 
	protected $scope = array();
	protected $routes = array();

	public function __construct(array &$scope) {
		$this->scope = &$scope;
		$this->init();
	}

	public function init() {

	}

public function set_route_pattern($path, array $match, $method) {
		// alias could be '/{id}/edit/'
		// should match '/17/edit/'
		// route to action 'action-edit' with input id=>17 available
		if(!preg_match("#^/(([a-z0-9\-_\{\}]+(/|$))+)$#i", $path, $path_match))
			throw new \WireException(sprintf($this->_("Path '%s' is not valid for route alias."), $path));
		if(!method_exists($this, $method))
			throw new \WireException(sprintf($this->_("Tried to set up a route to method '%s' which doesn’t exist."), $method));
		$path_elements = explode("/", rtrim($path_match[1], '/'));
		$route = '#^' . preg_quote(rtrim($path, '/')) . '(/(page[0-9]+/?)?)?$#';
		$keys = array();
		$i = 0;
		do {
			$i++;
			$start = mb_strpos($route, '{');
			if($start !== false) {
				if($start == mb_strlen($route) - 1) break;
				$end = mb_strpos($route, '}', $start);
				$key = mb_substr($route, $start + 1, $end - ($start + 2));
				$keys[$i] = $key;
				$route = str_replace(preg_quote('{'.$key.'}'), "(".$match[$key].")", $route);
			}
		} while($start !== false);
		$pattern = array(
			'keys' => $keys,
			'action' => $method
			);
		$this->routes[$route] = $pattern;
		return $route;
	}

	public function get_action($force_action=null) {
		// if forced action exists it’ll override the usual action
		if(($force_action) && (method_exists($this, $force_action))) {
			return $force_action;
		}
		// get urlSegements
		$i = 0;
		$path_segments = array();
		while(isset($this->input->urlSegments[$i+1])) {
			$i++;
			$path_segments[] = $this->input->urlSegments[$i];
		}
		if($i > 0) {
			// maybe a regular action?
			$action = implode('_', $path_segments);
			if(method_exists($this, $action)) return $action;
			// or more special? Check for patterns
			if(count($this->routes) > 0) {
				$path = "/" . implode("/", $path_segments);
				foreach($this->routes as $pattern => $route) {
					if(preg_match($pattern, $path, $matches)) {
						$action = $route['action'];
						$arguments = array();
						$cnt_matches = count($matches);
						for($j=1; $j<$cnt_matches; $j++) {
							if(isset($route['keys'][$j])) {
								$arguments[$route['keys'][$j]] = $matches[$j];
							} else {
								break;
							}
						}
						$this->input->route = new \WireInputData($arguments);
						// die(sprintf("Call action '%s' with arguments '%s'", $action, implode("', '", $arguments)));
						return $action;
					}
				}
			}
		}
		return 'index';
	}

	// default action
	public function index() {

	}

	public function set($var, $val, $context = 'root') {
		if(!array_key_exists($context, $this->scope)) $this->scope[strval($context)] = array();
		if(($context == 'root') && (property_exists($this, $var))) $this->$var = $val;
		else $this->scope[$context][$var] = $val;
	}

	public function &get($var, $context = 'root') {
		if(!array_key_exists($context, $this->scope)) return false;
		if(($context == 'root') && (property_exists($this, $var))) return $this->$var;
		else if(array_key_exists($var, $this->scope[$context])) return $this->scope[$context][$var];
		else return false;
	}

	public function get_scope($context = 'root', $only=false) {
		if(!array_key_exists($context, $this->scope)) return array();
		// start with fuel scrope
		if(!$only) {
			$scope = array_merge($this->scope['fuel'], $this->scope['root']);
		} else {
			$scope = array(); 
		}
		// add context scope
		if($only || ($context != 'fuel' && $context != 'root'))
			$scope = array_merge($scope, $this->scope[$context]);
		// controller vars
		/*
		if(!$only) {
			$controller_class = get_class($this);
			$controller_vars = get_object_vars($this);
			foreach($controller_vars as $var => $val) {
				if(($var != 'scope') && !) {
					$scope[$var] =& $this->$var;
				}
			}
		}
		*/
		return $scope;
	}

	public function set_layout($layout) {
		return $this->layout = $layout;
	}
	public function get_layout() {
		return $this->layout;
	}

	public function add_style($style_path, $priority=0, $group_name=null) {
		if(!is_string($style_path)) return false;
		$priority = intval($priority);
		$group_name = $group_name?strval($group_name):'styles_' . count($this->styles);
		if(!array_key_exists($group_name, $this->styles)) $this->styles[$group_name] = array();
		$return = array($style_path, $priority, $group_name);
		$styles =& $this->styles[$group_name];
		foreach($styles as &$style) {
			if($style[0] == $style_path) {
				$style_path[1] = $priority;
				return $return;
			}
		}
		$styles[] = array($style_path, $priority);
		return $return;
	}

	public function get_styles($group_name=null) {
		$styles = array();
		if((is_string($group_name)) && (array_key_exists($group_name, $this->styles))) {
			$group = array_merge(array(), $this->styles[$group_name]);
			usort($group, array($this, '_sort_by_priority'));
			foreach($group as $style) $styles[] = $style[0];
		}
		else {
			foreach($this->styles as $group_name => $group) {
				$styles = array_merge($styles, $this->get_styles($group_name));
			}
		}
		return $styles;
	}

	public function add_script($script_path, $priority=0, $group_name=null) {
		if(!is_string($script_path)) return false;
		$priority = intval($priority);
		$group_name = $group_name?strval($group_name):'scripts_' . count($this->scripts);
		if(!array_key_exists($group_name, $this->scripts)) $this->scripts[$group_name] = array();
		$return = array($script_path, $priority, $group_name);
		$scripts =& $this->scripts[$group_name];
		foreach($scripts as &$script) {
			if($script[0] == $script_path) {
				$script_path[1] = $priority;
				return $return;
			}
		}
		$scripts[] = array($script_path, $priority);
		return $return;
	}

	public function get_scripts($group_name=null) {
		$scripts = array();
		if((is_string($group_name)) && (array_key_exists($group_name, $this->scripts))) {
			$group = array_merge(array(), $this->scripts[$group_name]);
			usort($group, array($this, '_sort_by_priority'));
			foreach($group as $style) $scripts[] = $style[0];
		}
		else {
			$scripts = array();
			foreach($this->scripts as $group) {
				usort($group, array($this, '_sort_by_priority'));
				foreach($group as $script) $scripts[] = $script[0];
			}
		}
		return $scripts;
	}

	protected function _sort_by_priority($a, $b) {
		if($a[1] == $b[1]) return 0;
		return $a[1] < $b[1]?-1:1;
	}


	protected function _ext($type, $subtype=null) {
		return ProcessPwvc::ext($type, $this->pwvc->template_engine);
	}
}