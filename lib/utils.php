<?php
/**
 * Define helper utilities
 *
 * @package WordPress
 * @subpackage Essence
 */

/**
 * Theme Wrapper
 *
 * @link http://scribu.net/wordpress/theme-wrappers.html
 */
function essence_template_path() {
  return Essence_Wrapping::$main_template;
}

function essence_sidebar_path() {
  return Essence_Wrapping::sidebar();
}

class Essence_Wrapping {
  // Stores the full path to the main template file
  static $main_template;
  // Stores the base name of the template file; e.g. 'page' for 'page.php' etc.
  static $base;

  static function wrap($template) {
    self::$main_template = $template;
    self::$base          = substr(basename(self::$main_template), 0, -4);

    if (self::$base === 'index') {
      self::$base = false;
    }
    $templates = array(
      'base.php'
    );
    if (self::$base) {
      array_unshift($templates, sprintf('base-%s.php', self::$base));
    }

    return locate_template($templates);
  }

  static function sidebar() {
    $templates = array(
      'templates/sidebar.php'
    );
    if (self::$base) {
      array_unshift($templates, sprintf('templates/sidebar-%s.php', self::$base));
    }

    return locate_template($templates);
  }
}
add_filter('template_include', array('Essence_Wrapping', 'wrap'), 99);

/**
 * Page title function
 */
function essence_page_title() {
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
    _e('404 &ndash; page not found', THEME_NAME);
  } else {
    the_title();
  }
}

/**
 * Show an admin notice if .htaccess isn't writable
 */
function essence_htaccess_writable() {
  if (!is_writable(get_home_path() . '.htaccess')) {
    if (current_user_can('administrator')) {
      add_action('admin_notices', create_function('', "echo '<div class=\"error\"><p>" . sprintf(__('Please make sure your <a href="%s">.htaccess</a> file is writable ', THEME_NAME), admin_url('options-permalink.php')) . "</p></div>';"));
    }
  }
}
add_action('admin_init', 'essence_htaccess_writable');

/**
 * Returns WordPress subdirectory if applicable
 */
function wp_base_dir() {
  preg_match('!(https?://[^/|"]+)([^"]+)?!', site_url(), $matches);
  if (count($matches) === 3) {
    return end($matches);
  } else {
    return '';
  }
}

/**
 * Opposite of built in WP functions for trailing slashes
 */
function leadingslashit($string) {
  return '/' . unleadingslashit($string);
}

function unleadingslashit($string) {
  return ltrim($string, '/');
}

/**
 * Add filters
 */
function add_filters($tags, $function) {
  foreach ($tags as $tag) {
    add_filter($tag, $function);
  }
}
