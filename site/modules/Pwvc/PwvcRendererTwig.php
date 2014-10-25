 <?php
/**
 * Pwvc Twig Renderer Class V. 0.5.0
 * Part of Pwvc, a module for ProcessWire 2.3+
 *
 * by Oliver Wehn
 * https://github.com/oliverwehn
 *
 * inspired of and based on parts of MVC Module by Harmster
 * https://github.com/Hawiak
 * hawiak.nl
 *
 * Renderer that processes Twig templates.
 *
 */

require_once('Twig/Autoloader.php');

class PwvcRendererTwig extends PwvcRenderer {

  // Name of renderer, used to address it from Pwvc module
  const RENDERER_NAME = 'Twig';

  protected static
  $extensions = array(
    'controllers' => '.php',
    'layouts' => '.tmpl',
    'views' => '.view.tmpl',
    'snippets' => '.snippet.tmpl'
    );

  private $cache_path = 'site/assets/cache/Twig/',
          $environments = array();

  public function __construct() {
    parent::__construct();

    // register TwigAutoloader for class loading
    Twig_Autoloader::register();

    // modify behaviour of Page::__isset
    //http://processwire.com/talk/topic/1421-twig/#entry13080
    Page::$issetHas = true;

    // get Twig cache
    $this->cache_path = $this->config->paths->root . '/' . $this->cache_path;
    if(!ProcessPwvc::validate_dir($this->cache_path)) {
      throw new WireException(sprintf($this->__("Twig cache folder '%s' wasn’t found and cound’t be created."), $this->cache_path));
    }
  }


  public function ___render() {
    // template code goes here
    $templates = array();

    $layout_file_name = basename($this->layout_file);
    $view_file_name = basename($this->view_file);


    // get layout template code
    if(!$this->page->get('embedded')) {
      if(file_exists($this->layout_file)) {
        $layout_content = file_get_contents($this->layout_file);
        $templates[basename($this->layout_file)] = $layout_content;
      } else {
        throw new WireException(sprintf($this->__("Wasn’t able to load layout file '%s'"), $this->layout_file));
      }
    } else {
      $layout_content = null;
    }
    // get view
    if(file_exists($this->view_file)) {
      $view_content = file_get_contents($this->view_file);
    } else {
      throw new WireException(sprintf($this->__("Wasn’t able to load view file '%s'"), $this->view_file));
    }

    // get layout
    if($layout_content !== null) {
      $bind_content = "{% extends \"{$layout_file_name}\" %}";
      $bind_content .= "{% block view %}{% set textdomain %}{$this->view_file}{% endset %}" . $view_content . " {% endblock %}";
    } else {
      $bind_content = "{% set textdomain %}{$this->view_file}{% endset %}\n";
      $bind_content .= $view_content . "\n";
    }

    // add block content
    $templates[$view_file_name] = $bind_content;

    // set up Twig with chained loaders
    $loaders = array(
      'array' => new Twig_Loader_Array($templates),
      'filesystem' => new Twig_Loader_Filesystem($this->config->paths->templates),
      'string' => new Twig_Loader_String
    );
    $loaders['filesystem']->addPath($this->pwvc->paths->layouts, 'layouts');
    $loaders['filesystem']->addPath($this->pwvc->paths->views, 'views');
    $loaders['filesystem']->addPath($this->pwvc->paths->snippets, 'snippets');

    $loader_chain = new Twig_Loader_Chain($loaders);

    $this->environments[] = new Twig_Environment($loader_chain, array('debug' => $this->config->debug, 'cache' => $this->cache_path, 'autoescape' => false));
    $twig = &$this->environments[count($this->environments) - 1];
    $twig->addGlobal('renderer', $this);
    $twig->addGlobal('server', $_SERVER);
    // add filter method for translations
    $filters = array(
      new Twig_SimpleFilter('embed', array(&$this, '_get_embed')),
      new Twig_SimpleFilter('process', array(&$this, '_get_processed')),
      new Twig_SimpleFilter('translatable', array(&$this, 'render_twig_translatable'), array('needs_context' => true)),
      new Twig_SimpleFilter('translatable_x', array(&$this, 'render_twig_translatable_x'), array('needs_context' => true)),
      new Twig_SimpleFilter('translatable_n', array(&$this, 'render_twig_translatable_n'), array('needs_context' => true))
    );
    foreach($filters as $filter) $twig->addFilter($filter);
    return $twig->render($view_file_name, $this->scope);
  }

  // Filter method for getting back string processed by Twig
  public function _get_processed($str) {
    $twig = &$this->environments[count($this->environments) - 1];
    return $twig->render($str, $this->scope);
  }

  // Filter method for translatable text in Twig templates
  public function render_twig_translatable($context, $text) {
    $textdomain = $context['textdomain'];
    if(!file_exists($textdomain)) return $text;
    return __($text, $textdomain);
  }
  public function render_twig_translatable_x($context, $text, $context) {
    $textdomain = $context['textdomain'];
    if(!file_exists($textdomain)) return $text;
    return _c($text, $context, $textdomain);
  }
  public function render_twig_translatable_n($context, array $text_array, $textSingular, $textPlural, $count) {
    if(!file_exists($textdomain)) return $text;
    return _n($text_array[0], $text_array[1], $count, $textdomain);
  }
}