<?php


function university_files() {
  wp_enqueue_script("main-university-js", get_theme_file_uri("/build/index.js"), array("jquery"), "1.0", true);
  // wp_enqueue_style("university_main_styles", get_theme_file_uri("/build/style-index.css"));
  wp_enqueue_style("university_extra_styles", get_theme_file_uri("/build/index.css"));
}

add_action("wp_enqueue_scripts", "university_files");

function university_features() {
  add_theme_support("title-tag");
  add_theme_support("post-thumbnails");
}

add_action("after_setup_theme", "university_features");


/** 注册自定义 Block */
class JSXBlock {
  function __construct($name, $renderCallback = null, $data = null) {
    $this->name = $name;
    $this->renderCallback = $renderCallback;
    $this->data = $data;
    add_action("init", [$this, "onInit"]);
  }

  function ourRenderCallback($attributes, $content) {
    // 创建缓冲区读取 buffer
    ob_start();
    require get_theme_file_path("/src/blocks/{$this->name}/{$this->name}.php");
    return ob_get_clean();
  }

  function onInit() {
    wp_register_script($this->name, get_stylesheet_directory_uri() . "/build/{$this->name}/{$this->name}.js", array("wp-blocks", "wp-editor"));

    // 水合默认数据
    if ($this->data) {
      wp_localize_script($this->name, $this->name, $this->data);
    }

    $ourArgs = array(
      "editor_script" => $this->name
    );

    if ($this->renderCallback) {
      $ourArgs["render_callback"] = [$this, "ourRenderCallback"];
    }

    register_block_type("ourblocktheme/{$this->name}", $ourArgs);
  }
}

new JSXBlock("example-block");
/** 注册自定义 Block */

class PlaceholderBlock {
  function __construct($name) {
    $this->name = $name;
    add_action("init", [$this, "onInit"]);
  }

  function ourRenderCallback($attributes, $content) {
    // 创建缓冲区读取 buffer
    ob_start();
    require get_theme_file_path("/src/blocks/{$this->name}/{$this->name}.php");
    return ob_get_clean();
  }

  function onInit() {
    wp_register_script($this->name, get_stylesheet_directory_uri() . "/build/{$this->name}/{$this->name}.js", array("wp-blocks", "wp-editor"));

    $ourArgs = array(
      "editor_script" => $this->name,
      "render_callback" => [$this, "ourRenderCallback"]
    );

    register_block_type("ourblocktheme/{$this->name}", $ourArgs);
  }
}
