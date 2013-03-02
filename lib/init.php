<?php
/**
 * Initialization of core theme functions and definition of helper constants
 *
 * 1. Define helper constants
 * 2. Initialize core theme functions
 *
 * @package WordPress
 * @subpackage Essence
 */

/**
 * Define helper constants
 */
$get_theme_name = explode('/themes/', get_template_directory());
define('WP_BASE', wp_base_dir());
define('THEME_NAME', next($get_theme_name));
define('RELATIVE_PLUGIN_PATH', str_replace(site_url() . '/', '', plugins_url()));
define('FULL_RELATIVE_PLUGIN_PATH', WP_BASE . '/' . RELATIVE_PLUGIN_PATH);
define('RELATIVE_CONTENT_PATH', str_replace(site_url() . '/', '', content_url()));
define('THEME_PATH', RELATIVE_CONTENT_PATH . '/themes/' . THEME_NAME);

/**
 * Initialize core theme functions
 */
function essence_init() {
  // Add support for translations
  load_theme_textdomain(THEME_NAME, get_template_directory() . '/lang');

  // Add support for thumbnails
  add_theme_support('post-thumbnails');

  // Add support for a variety of post formats
  add_theme_support('post-formats', array(
    'aside',
    'link',
    'gallery',
    'status',
    'quote',
    'image'
  ));

  // Tell the TinyMCE editor to use editor-style.css
  add_editor_style('assets/css/editor-style.css');
}
add_action('after_setup_theme', 'essence_init');
