<?php
/**
 * Configuration and constants
 *
 * @package WordPress
 * @subpackage Essence
 */

/**
 * Define which pages shouldn't have the sidebar
 *
 * See lib/sidebar.php for more details
 */
function essence_display_sidebar() {
  $sidebar_config = new Essence_Sidebar(
    /**
     * Conditional tag checks (http://codex.wordpress.org/Conditional_Tags)
     * Any of these conditional tags that return true won't show the sidebar
     *
     * To use a function that accepts arguments, use the following format:
     *
     * array('function_name', array('arg1', 'arg2'))
     *
     * The second element must be an array even if there's only 1 argument.
     */
    array(
      'is_404'
    ),
    /**
     * Page template checks (via is_page_template())
     * Any of these page templates that return true won't show the sidebar
     */
    array(
    )
  );

  return $sidebar_config->display;
}

/**
 * Post excerpt lenght
 *
 * @since Essence 1.0.0
 */
define('POST_EXCERPT_LENGTH',       40);

/**
 * Get the theme name
 *
 * @since Essence 1.0.0
 */
$get_theme_name = explode('/themes/', get_template_directory());

/**
 * Backwards compatibility for older than PHP 5.3.0
 *
 * @since Essence 1.0.0
 */
if (!defined('__DIR__'))
  define('__DIR__', dirname(__FILE__));

/**
 * Theme directories shorthands
 *
 * @since Essence 1.0.0
 */
define('WP_BASE',                   wp_base_dir());
define('THEME_NAME',                next($get_theme_name));
define('RELATIVE_PLUGIN_PATH',      str_replace(site_url() . '/', '', plugins_url()));
define('FULL_RELATIVE_PLUGIN_PATH', WP_BASE . '/' . RELATIVE_PLUGIN_PATH);
define('RELATIVE_CONTENT_PATH',     str_replace(site_url() . '/', '', content_url()));
define('THEME_PATH',                RELATIVE_CONTENT_PATH . '/themes/' . THEME_NAME);
