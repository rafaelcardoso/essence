<?php
/**
 * Theme initialization
 *
 * 1. Register menus
 * 2. Register sidebars and widgets
 * 3. Enqueue CSS
 * 4. Enqueue JS
 *
 * @package WordPress
 * @subpackage Essence
 */

/**
 * Register menus
 */
function essence_register_nav() {
  register_nav_menus(array(
    'nav_primary' => __('Primary Navigation', THEME_NAME)
  ));
}
add_action('after_setup_theme', 'essence_register_nav');

/**
 * Register sidebars and widgetized areas
 */
function essence_register_widget() {
  register_sidebar(array(
    'name' => __('Sidebar', THEME_NAME),
    'id' => 'widget_sidebar',
    'before_widget' => '<section>',
    'after_widget' => '</section>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
  ));
}
add_action('widgets_init', 'essence_register_widget');

/**
 * Enqueue CSS
 */
function essence_css() {
  // Register
  wp_register_style('main', get_template_directory_uri() . '/assets/css/main.css', false, null);

  // Enqueue
  wp_enqueue_style('main');
}
add_action('wp_enqueue_scripts', 'essence_css');

/**
 * Enqueue JS
 */
function essence_js() {
  // Register
  wp_register_script('modernizr', get_template_directory_uri() . '/assets/js/vendor/modernizr-2.6.2.js', false, null, false);
  wp_deregister_script('jquery');
  wp_register_script('main', get_template_directory_uri() . '/assets/js/main.js', false, null, true);

  // Enqueue
  wp_enqueue_script('modernizr');
  wp_enqueue_script('main');
  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}
add_action('wp_enqueue_scripts', 'essence_js');
