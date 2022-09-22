<?php

function load_assets() {
  wp_enqueue_script('index_js', get_theme_file_uri('/dist/index.js'));
  wp_enqueue_style('index_css', get_theme_file_uri('/dist/index.css'));
  wp_enqueue_style('style_index_css', get_theme_file_uri('/dist/style-index.css'));
}

add_action('wp_enqueue_scripts', 'load_assets');

function add_support() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
}

add_action('after_setup_theme', 'add_support');

/** register custom JSX blocks */
class JSXBlock {
  function __construct($name, $renderCallback = null, $data = null, $scope = "custom-blocks") {
    $this->name = $name;
    $this->renderCallback = $renderCallback;
    $this->data = $data;
    $this->scope = $scope;

    add_action("init", [$this, "onInit"]);
  }

  function onInit() {
    wp_register($this->name,get_stylesheet_directory_uri() . "/dist/{$this->name}/{$this->name}.js", array("wp-blocks", "wp-editor"));

    // hydrate data
    if ($this->data) {
      wp_localize_script($this->name, $this->name, $this->data);
    }

    $ourArgs = array(
      "editor_script" => $this->name
    );

    // use php to render
    if ($this->renderCallback) {
      $ourArgs["render_callback"] = [$this, "renderCallback"];
    }

    register_blocktype("{$this->scope}/{$this->name}", $ourArgs);
  }

  function renderCallback() {
    // read buffer
    ob_start();
    require get_theme_file_path("src/blocks/${$this->name}/{$this->name}.php");

    return ob_get_clean();
  }
}

// manully register custom blocks below
// todo use glob to register automatically
// eg. new JSXBlock("banner", true, ["fallbackimage" => get_theme_file_uri("/images/library-hero.jpg")]);
new JSXBlock("example-block");

/** register custom JSX blocks */

/** register placeholder blocks */
class PlaceholderBlock{
  function __construct($name, $scope = "custom-blocks") {
    $this->name = $name;
    $this->scope = $scope;

    add_action("init", [$this, "onInit"]);
  }

  function onInit() {
    wp_register_script($this->name, get_stylesheet_directory_uri() . "/dist/{$this->name}/{$this->name}.js", array("wp-blocks", "wp-editor"));

    $ourArgs = array(
      "editor_script" => $this->name,
      "render_callback" => [$this, "renderCallback"]
    );

    register_block_type("{$this->scope}/{$this->name}", $ourArgs);
  }

  function renderCallback() {
    // read buffer
    ob_start();
    require get_theme_file_path("src/blocks/${$this->name}/{$this->name}.php");

    return ob_get_clean();
  }
}

// manully register custom blocks below
// todo use glob to register automatically
// eg. new PlaceholderBlock("header");

/** register placeholder blocks */

?>