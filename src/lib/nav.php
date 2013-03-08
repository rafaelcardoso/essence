<?php
/**
 * Navigation
 *
 * 1. Define custom nav walker
 * 2. Customize `wp_nav_menu_args`
 *
 * @package WordPress
 * @subpackage Essence
 */

/**
 * Custom walker for `wp_nav_menu()`
 *
 * Example output:
 *
 * <ul>
 *   <li class="is-active"><a href="/">Home</a></li>
 *   <li><a href="sample-page/">Sample Page</a></li>
 * </ul>
 */
class Essence_Nav_Menu extends Walker_Nav_Menu {
  function start_el(&$output, $item, $depth, $args) {
    $classes[] = ($item->current || $item->current_item_ancestor) ? 'is-active' : '';
    $class     = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item));

    !empty($class)
      and $class = ' class="'. esc_attr($class) . '"';

    $output    .= '<li' . $class . '>';
    $attributes = '';

    !empty($item->attr_title)
      and $attributes .= ' title="' . esc_attr($item->attr_title) . '"';
    !empty($item->url)
      and $attributes .= ' href="' . esc_attr($item->url) . '"';

    $attributes  = trim($attributes);
    $title       = apply_filters('the_title', $item->title, $item->ID);
    $item_output = "$args->before<a $attributes>$args->link_before$title</a>" . "$args->link_after$args->after";

    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }

  function start_lvl(&$output) {
    $output .= '<ul>';
  }

  function end_lvl(&$output) {
    $output .= '</ul>';
  }

  function end_el(&$output) {
    $output .= '</li>';
  }
}

/**
 * Customize `wp_nav_menu_args`
 */
function essence_nav_menu_args($args) {
  $essence_nav_menu_args['container']  = false;
  $essence_nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';

  if (!$args['walker']) {
    $essence_nav_menu_args['walker'] = new Essence_Nav_Menu();
  }

  return array_merge($args, $essence_nav_menu_args);
}
add_filter('wp_nav_menu_args', 'essence_nav_menu_args');
