<?php
/**
 * This is where much of the magic happens
 *
 * @package WordPress
 * @subpackage Essence
 */

/**
 * Check to see if the server is Apache
 * If yes, then go on
 *
 * @since Essence 1.0.0
 */
if (stristr($_SERVER['SERVER_SOFTWARE'], 'apache') || stristr($_SERVER['SERVER_SOFTWARE'], 'litespeed') !== false) {
  /**
   * Check to see if htaccess is writable
   *
   * @since Essence 1.0.0
   */
  function essence_htaccess_writable()
  {
    if (!is_writable(get_home_path() . '.htaccess')) {
      if (current_user_can('administrator')) {
        add_action('admin_notices', create_function('', "echo '<div class=\"error\"><p>" . sprintf(__('Please make sure your <a href="%s">.htaccess</a> file is writable!', THEME_NAME), admin_url('options-permalink.php')) . "</p></div>';"));
      }
    }
  }
  add_action('admin_init', 'essence_htaccess_writable');

  /**
   * Add rewrites
   * Do not work for child themes
   *
   * @since Essence 1.0.0
   */
  function essence_add_rewrites($content)
  {
    global $wp_rewrite;

    $essence_new_non_wp_rules = array(
      'assets/css/(.*)' => THEME_PATH . '/assets/css/$1',
      'assets/js/(.*)' => THEME_PATH . '/assets/js/$1',
      'assets/img/(.*)' => THEME_PATH . '/assets/img/$1',
      'plugins/(.*)' => RELATIVE_PLUGIN_PATH . '/$1'
    );
    $wp_rewrite->non_wp_rules = array_merge($wp_rewrite->non_wp_rules, $essence_new_non_wp_rules);

    return $content;
  }

  /**
   * Clean URLs
   *
   * @since Essence 1.0.0
   */
  function essence_clean_urls($content)
  {
    if (strpos($content, FULL_RELATIVE_PLUGIN_PATH) === 0) {
      return str_replace(FULL_RELATIVE_PLUGIN_PATH, WP_BASE . '/plugins', $content);
    } else {
      return str_replace('/' . THEME_PATH, '', $content);
    }
  }

  /**
   * Apply our clean URLs
   * Only happens if our theme isn't a child theme or network install
   *
   * @since Essence 1.0.0
   */
  if (!is_multisite() && !is_child_theme()) {
    add_action('generate_rewrite_rules', 'essence_add_rewrites');
    add_action('generate_rewrite_rules', 'essence_add_htaccess');

    if (!is_admin()) {
      $tags = array(
        'plugins_url',
        'bloginfo',
        'stylesheet_directory_uri',
        'template_directory_uri',
        'script_loader_src',
        'style_loader_src'
      );

      add_filters($tags, 'essence_clean_urls');
    }
  }

  /**
   * Add custom htaccess file
   *
   * @since Essence 1.0.0
   */
  function essence_add_htaccess($content)
  {
    global $wp_rewrite;

    $home_path = function_exists('get_home_path') ? get_home_path() : ABSPATH;
    $htaccess_file = $home_path . '.htaccess';
    $mod_rewrite_enabled = function_exists('got_mod_rewrite') ? got_mod_rewrite() : false;

    if ((!file_exists($htaccess_file) && is_writable($home_path) && $wp_rewrite->using_mod_rewrite_permalinks()) || is_writable($htaccess_file)) {
      if ($mod_rewrite_enabled) {
        $rules = extract_from_markers($htaccess_file, 'HTML5 Boilerplate');
        if ($rules === array()) {
          $filename = __DIR__ . '/htaccess';
          return insert_with_markers($htaccess_file, 'HTML5 Boilerplate', extract_from_markers($filename, 'HTML5 Boilerplate'));
        }
      }
    }

    return $content;
  }
}
