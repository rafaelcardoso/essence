<?php
/**
 * Configuration and constants
 *
 * @package WordPress
 * @subpackage Essence
 */

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
