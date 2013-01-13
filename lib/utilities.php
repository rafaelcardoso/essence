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

/**
 * Page title function
 *
 * @since Essence 1.1.0
 */
function essence_page_title()
{
  if (is_home()) {
    if (get_option('page_for_posts', true)) {
      echo get_the_title(get_option('page_for_posts', true));
    } else {
      _e('Latest Posts', THEME_NAME);
    }
  } elseif (is_archive()) {
    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    if ($term) {
      echo $term->name;
    } elseif (is_post_type_archive()) {
      echo get_queried_object()->labels->name;
    } elseif (is_day()) {
      printf(__('Daily Archives: %s', THEME_NAME), get_the_date());
    } elseif (is_month()) {
      printf(__('Monthly Archives: %s', THEME_NAME), get_the_date('F Y'));
    } elseif (is_year()) {
      printf(__('Yearly Archives: %s', THEME_NAME), get_the_date('Y'));
    } elseif (is_author()) {
      global $post;
      $author_id = $post->post_author;
      printf(__('Author Archives: %s', THEME_NAME), get_the_author_meta('display_name', $author_id));
    } else {
      single_cat_title();
    }
  } elseif (is_search()) {
    printf(__('Search Results for %s', THEME_NAME), get_search_query());
  } elseif (is_404()) {
    _e('404 — page not found', THEME_NAME);
  } else {
    the_title();
  }
}
