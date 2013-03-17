<?php
/**
 * URL rewrites
 *
 * Rewrites currently aren't avaiable for child themes or network installs
 *
 * Rewrite:
 *   /wp-content/themes/themename/css/ to /css/
 *   /wp-content/themes/themename/js/  to /js/
 *   /wp-content/themes/themename/img/ to /img/
 *   /wp-content/plugins/              to /plugins/
 *
 * @package WordPress
 * @subpackage Essence
 */

/**
 * Define rewrite paths
 */
function essence_url_define($content) {
  global $wp_rewrite;
  $rewrites = array(
    'assets/css/(.*)' => THEME_PATH . '/assets/css/$1',
    'assets/js/(.*)'  => THEME_PATH . '/assets/js/$1',
    'assets/img/(.*)' => THEME_PATH . '/assets/img/$1',
    'plugins/(.*)'    => RELATIVE_PLUGIN_PATH . '/$1'
  );
  $wp_rewrite->non_wp_rules = array_merge($wp_rewrite->non_wp_rules, $rewrites);

  return $content;
}

/**
 * Remove full relative path from URLs
 */
function essence_url_clean($content) {
  if (strpos($content, FULL_RELATIVE_PLUGIN_PATH) === 0) {
    return str_replace(FULL_RELATIVE_PLUGIN_PATH, WP_BASE . '/plugins', $content);
  } else {
    return str_replace('/' . THEME_PATH, '', $content);
  }
}

/**
 * Apply rewrites
 */
if (!is_multisite() && !is_child_theme() && get_option('permalink_structure')) {
  if (!is_admin()) {
    $tags = array(
      'plugins_url',
      'bloginfo',
      'stylesheet_directory_uri',
      'template_directory_uri',
      'script_loader_src',
      'style_loader_src'
    );
    add_action('generate_rewrite_rules', 'essence_url_define');
    add_filters($tags, 'essence_url_clean');
  }
}
