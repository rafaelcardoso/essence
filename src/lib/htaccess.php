<?php
/**
 * Import custom .htaccess
 *
 * @package WordPress
 * @subpackage Essence
 */

function essence_htaccess($content) {
  global $wp_rewrite;
  $home = function_exists('get_home_path') ? get_home_path() : ABSPATH;
  $htaccess = $home . '.htaccess';
  $rewrite_enabled = function_exists('got_mod_rewrite') ? got_mod_rewrite() : false;

  if ((!file_exists($htaccess) && is_writable($home) && $wp_rewrite->using_mod_rewrite_permalinks()) || is_writable($htaccess)) {
    if ($rewrite_enabled) {
      $rules = extract_from_markers($htaccess, 'HTML5 Boilerplate');
      if ($rules === array()) {
        $filename = dirname(__FILE__) . '/htaccess';
        return insert_with_markers($htaccess, 'HTML5 Boilerplate', extract_from_markers($filename, 'HTML5 Boilerplate'));
      }
    }
  }

  return $content;
}
add_action('generate_rewrite_rules', 'essence_htaccess');
