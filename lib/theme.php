<?php
/**
 * Initialization of core theme functions
 *
 * @package WordPress
 * @subpackage Essence
 */

/**
 * Tell WordPress to run essence_setup() when
 * the 'after_setup_theme' hook is run.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for custom post formats.
 *
 * @since Essence 1.0.0
 *
 * @link http://codex.wordpress.org/Plugin_API/Action_Reference/after_setup_theme
 */
function essence_setup()
{
  // Make theme available for translation.
  // http://codex.wordpress.org/Function_Reference/load_theme_textdomain
  load_theme_textdomain(THEME_NAME, get_template_directory() . '/lang');

  // Tell the TinyMCE editor to use editor-style.css
  // http://codex.wordpress.org/Function_Reference/add_editor_style
  add_editor_style('editor-style.css');

  // Add support for a variety of post formats
  // http://codex.wordpress.org/Post_Formats
  add_theme_support('post-formats', array(
    'aside',
    'link',
    'gallery',
    'status',
    'quote',
    'image'
  ));

  // Add default posts and comments RSS feed links to head
  add_theme_support('automatic-feed-links');

  // Add support for post thumbnails
  // http://codex.wordpress.org/Post_Thumbnails
   add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'essence_setup');

/**
 * Setup default menus
 *
 * @uses register_nav_menu() to add support for navigation menus.
 *
 * @since Essence 1.0.0
 *
 * @link http://codex.wordpress.org/Function_Reference/register_nav_menus
 */
function essence_register_menus()
{
  register_nav_menus(array(
    'nav_primary' => __('Primary Navigation', THEME_NAME)
  ));
}
add_action('after_setup_theme', 'essence_register_menus');

/**
 * Register our sidebars and widgetized areas
 *
 * @uses register_sidebar() to add widgetized areas.
 *
 * @since Essence 1.0.0
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function essence_register_widgets()
{
  register_sidebar(array(
    'name' => __('Sidebar', THEME_NAME),
    'id' => 'widget-sidebar',
    'before_widget' => '<section>',
    'after_widget' => '</section>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
  ));

  register_sidebar(array(
    'name' => __('Footer', THEME_NAME),
    'id' => 'widget-footer',
    'before_widget' => '<section>',
    'after_widget' => '</section>',
    'before_title' => '<h4>',
    'after_title' => '</h4>'
  ));
}
add_action('widgets_init', 'essence_register_widgets');

/**
 * Load our stylesheets
 *
 * @since Essence 1.0.0
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_enqueue_style
 */
function essence_styles()
{
  // Register CSS
  wp_register_style('main', get_template_directory_uri() . '/assets/css/main.css', false, null);

  // Enqueue!
  wp_enqueue_style('main');
}
add_action('wp_enqueue_scripts', 'essence_styles');

/**
 * Load our scripts
 *
 * @since Essence 1.0.0
 *
 * @link http://codex.wordpress.org/Function_Reference/wp_enqueue_script
 */
function essence_scripts()
{
  // Discover the correct protocol to use for CDN jQuery
  $protocol = is_ssl() ? 'https:' : 'http:';

  // Register jQuery
  if (!is_admin()) {
    wp_deregister_script('jquery');
    wp_register_script('jquery', $protocol . '//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js', false, null, true);
    wp_enqueue_script('jquery');
  }

  // Threaded comments
  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  // Register our custom scripts
  wp_register_script('modernizr', get_template_directory_uri() . '/assets/js/vendor/modernizr-2.6.2.min.js', false, null, false);
  wp_register_script('main', get_template_directory_uri() . '/assets/js/main.js', false, null, true);

  // Enqueue!
  wp_enqueue_script('modernizr');
  wp_enqueue_script('jquery');
  wp_enqueue_script('main');
}
add_action('wp_enqueue_scripts', 'essence_scripts');
