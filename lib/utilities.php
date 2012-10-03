<?php
/**
 * Adds some helper functions
 *
 * @package WordPress
 * @subpackage Essence
 */

/**
 * Theme Wrapper
 *
 * @since Essence 1.0.0
 *
 * @link http://scribu.net/wordpress/theme-wrappers.html
 */
function essence_template_path() {
  return Essence_Wrapping::$main_template;
}

class Essence_Wrapping {

  // Stores the full path to the main template file
  static $main_template;

  // Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
  static $base;

  static function wrap($template) {
    self::$main_template = $template;

    self::$base = substr(basename(self::$main_template), 0, -4);

    if ('index' == self::$base) {
      self::$base = false;
    }

    $templates = array('base.php');

    if (self::$base) {
      array_unshift($templates, sprintf('base-%s.php', self::$base ));
    }

    return locate_template($templates);
  }
}
add_filter('template_include', array('Essence_Wrapping', 'wrap'), 99);

/**
 * Returns WordPress subdirectory if applicable
 *
 * @since Essence 1.0.0
 */
function wp_base_dir()
{
  preg_match('!(https?://[^/|"]+)([^"]+)?!', site_url(), $matches);
  if (count($matches) === 3) {
    return end($matches);
  } else {
    return '';
  }
}

/**
 * Opposite of built in WP functions for trailing slashes
 *
 * @since Essence 1.0.0
 */
function leadingslashit($string)
{
  return '/' . unleadingslashit($string);
}

function unleadingslashit($string)
{
  return ltrim($string, '/');
}

/**
 * Add filters
 *
 * @since Essence 1.0.0
 */
function add_filters($tags, $function)
{
  foreach($tags as $tag) {
    add_filter($tag, $function);
  }
}
