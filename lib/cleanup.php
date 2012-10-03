<?php
/**
 * Removal of unnecessary stuff
 *
 * Cleaner wp_head, search URLs and much more.
 *
 * @package WordPress
 * @subpackage Essence
 */

/**
 * Clean up stuff from wp_head()
 *
 * Remove unnecessary <link>'s
 * Remove inline CSS used by Recent Comments widget
 * Remove self-closing tag and change ''s to "'s on rel_canonical()
 * Remove Wordpress version from RSS feed
 *
 * @since Essence 1.0.0
 *
 * @link http://wpengineer.com/1438/wordpress-header/
 */
function essence_head_cleanup()
{
  remove_action('wp_head', 'feed_links', 2);
  remove_action('wp_head', 'feed_links_extra', 3);
  remove_action('wp_head', 'rsd_link');
  remove_action('wp_head', 'wlwmanifest_link');
  remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
  remove_action('wp_head', 'wp_generator');
  remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
  add_action('wp_head', 'essence_remove_recent_comments_style', 1);

  if (!class_exists('WPSEO_Frontend')) {
    remove_action('wp_head', 'rel_canonical');
    add_action('wp_head', 'essence_rel_canonical');
  }
}
add_action('init', 'essence_head_cleanup');

/**
 * Remove CSS from recent comments widget
 *
 * @since Essence 1.0.0
 */
function essence_remove_recent_comments_style()
{
  global $wp_widget_factory;

  if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
  }
}

/**
 * Remove WordPress version from RSS feed
 *
 * @since Essence 1.0.0
 */
function essence_no_generator()
{
  return '';
}
add_filter('the_generator', 'essence_no_generator');

/**
 * Add canonical links
 *
 * @since Essence 1.0.0
 */
function essence_rel_canonical()
{
  global $wp_the_query;

  if (!is_singular()) {
    return;
  }

  if (!$id = $wp_the_query->get_queried_object_id()) {
    return;
  }

  $link = get_permalink($id);
  echo "\t<link rel=\"canonical\" href=\"$link\">\n";
}

/**
 * Root relative URLs
 * WordPress likes to use absolute URLs on everything - let's clean that up.
 *
 * @since Essence 1.0.0
 *
 * @link http://www.456bereastreet.com/archive/201010/how_to_make_wordpress_urls_root_relative/
 */
function essence_root_relative_url($input)
{
  $output = preg_replace_callback(
    '!(https?://[^/|"]+)([^"]+)?!',
    create_function(
      '$matches',
      // If full URL is home_url("/") and this isn't a subdir install, return a slash for relative root
      'if (isset($matches[0]) && $matches[0] === home_url("/") && str_replace("http://", "", home_url("/", "http"))==$_SERVER["HTTP_HOST"]) { return "/";' .
      // If domain is equal to home_url("/"), then make URL relative
      '} elseif (isset($matches[0]) && strpos($matches[0], home_url("/")) !== false) { return $matches[2];' .
      // If domain is not equal to home_url("/"), do not make external link relative
      '} else { return $matches[0]; };'),
    $input
  );

  return $output;
}

/**
 * Terrible workaround to remove the duplicate subfolder
 * in the src of <script> and <link> tags
 *
 * @since Essence 1.0.0
 */
function essence_fix_duplicate_subfolder_urls($input)
{
  $output = essence_root_relative_url($input);
  preg_match_all('!([^/]+)/([^/]+)!', $output, $matches);

  if (isset($matches[1]) && isset($matches[2])) {
    if ($matches[1][0] === $matches[2][0]) {
      $output = substr($output, strlen($matches[1][0]) + 1);
    }
  }

  return $output;
}

/**
 * Function to check whether theme should apply relative URLs
 *
 * @since Essence 1.0.0
 */
function essence_enable_root_relative_urls()
{
  return !(is_admin() && in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php')));
}

/**
 * Apply relative URLs
 *
 * @since Essence 1.0.0
 */
if (essence_enable_root_relative_urls()) {
  $essence_rel_filters = array(
    'bloginfo_url',
    'theme_root_uri',
    'stylesheet_directory_uri',
    'template_directory_uri',
    'script_loader_src',
    'style_loader_src',
    'plugins_url',
    'the_permalink',
    'wp_list_pages',
    'wp_list_categories',
    'wp_nav_menu',
    'the_content_more_link',
    'the_tags',
    'get_pagenum_link',
    'get_comment_link',
    'month_link',
    'day_link',
    'year_link',
    'tag_link',
    'the_author_posts_link'
  );
  add_filters($essence_rel_filters, 'essence_root_relative_url');
  add_filter('script_loader_src', 'essence_fix_duplicate_subfolder_urls');
  add_filter('style_loader_src', 'essence_fix_duplicate_subfolder_urls');
}

/**
 * Clean up the_excerpt()
 *
 * Sets excerpt lenght
 * Returns a "Continue Reading" link for excerpts
 *
 * @since Essence 1.0.0
 */
function essence_excerpt_length($length)
{
  return POST_EXCERPT_LENGTH;
}
function essence_excerpt_more($more)
{
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continue reading', THEME_NAME) . '</a>';
}
add_filter('excerpt_more', 'essence_excerpt_more');
add_filter('excerpt_length', 'essence_excerpt_length');

/**
 * Cleaner walker for wp_nav_menu()
 *
 * Walker_Nav_Menu (WordPress default) example output:
 * <li id="menu-item-8" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-8"><a href="/">Home</a></li>
 * <li id="menu-item-9" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9"><a href="/sample-page/">Sample Page</a></l
 *
 * Essence_Nav_Walker example output:
 * <li class="menu-home"><a href="/">Home</a></li>
 * <li class="menu-sample-page"><a href="/sample-page/">Sample Page</a></li>
 *
 * @since Essence 1.0.0
 */
class Essence_Nav_Walker extends Walker_Nav_Menu
{
  function check_current($classes)
  {
    return preg_match('/(current[-_])/', $classes);
  }

  function start_el(&$output, $item, $depth, $args)
  {
    global $wp_query;
    $indent = ($depth) ? str_repeat("\t", $depth) : '';

    $slug = sanitize_title($item->title);
    $id = 'menu-' . $slug;

    $class_names = $value = '';
    $classes = empty($item->classes) ? array() : (array) $item->classes;

    $classes = array_filter($classes, array(&$this, 'check_current'));

    if ($custom_classes = get_post_meta($item->ID, '_menu_item_classes', true)) {
      foreach ($custom_classes as $custom_class) {
        $classes[] = $custom_class;
      }
    }

    $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
    $class_names = $class_names ? ' class="' . $id . ' ' . esc_attr($class_names) . '"' : ' class="' . $id . '"';

    $output .= $indent . '<li' . $class_names . '>';

    $attributes  = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
    $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target    ) .'"' : '';
    $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn       ) .'"' : '';
    $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url       ) .'"' : '';

    $item_output  = $args->before;
    $item_output .= '<a'. $attributes .'>';
    $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
    $item_output .= '</a>';
    $item_output .= $args->after;

    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }
}

/**
 * Cleanup wp_nav_menu_args
 *
 * Remove the container
 * Use Essence_Nav_Walker() by default
 *
 * @since Essence 1.0.0
 */
function essence_nav_menu_args($args = '')
{
  $essence_nav_menu_args['container']  = false;
  $essence_nav_menu_args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';

  if (!$args['walker']) {
    $essence_nav_menu_args['walker'] = new Essence_Nav_Walker();
  }

  return array_merge($args, $essence_nav_menu_args);
}
add_filter('wp_nav_menu_args', 'essence_nav_menu_args');

/**
 * Replace various active menu class names with "is-active"
 *
 * @since Essence 4.0
 */
function essence_wp_nav_menu_active($text) {
  $text = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'is-active', $text);
  $text = preg_replace('/( active){2,}/', 'is-active', $text);
  return $text;
}
add_filter('wp_nav_menu', 'essence_wp_nav_menu_active');

/**
 * Redirects search results from /?s=query to
 * /search/query/, converts %20 to +
 *
 * @since Essence 1.0.0
 *
 * @link http://txfx.net/wordpress-plugins/nice-search/
 */
function essence_search_redirect()
{
  if (is_search() && strpos($_SERVER['REQUEST_URI'], '/wp-admin/') === false && strpos($_SERVER['REQUEST_URI'], '/search/') === false) {
    wp_redirect(home_url('/search/' . str_replace(array(
      ' ',
      '%20'
    ), array(
      '+',
      '+'
    ), urlencode(get_query_var('s')))), 301);
    exit();
  }
}
add_action('template_redirect', 'essence_search_redirect');

/**
 * Fix for get_search_query() returning +'s between search terms
 *
 * @since Essence 1.0.0
 */
function essence_search_query($escaped = true)
{
  $query = apply_filters('essence_search_query', get_query_var('s'));

  if ($escaped) {
    $query = esc_attr($query);
  }

  return urldecode($query);
}
add_filter('get_search_query', 'essence_search_query');

/**
 * Fix for empty search queries redirecting to home page
 *
 * @since Essence 1.0.0
 *
 * @link http://wordpress.org/support/topic/blank-search-sends-you-to-the-homepage#post-1772565
 * @link http://core.trac.wordpress.org/ticket/11330
 */
function essence_search_request_filter($query_vars)
{
  if (isset($_GET['s']) && empty($_GET['s'])) {
    $query_vars['s'] = ' ';
  }

  return $query_vars;
}
add_filter('request', 'essence_search_request_filter');

/**
 * Cleanup language_attributes() used in <html> tag
 *
 * Change lang="en-US" to lang="en"
 * Remove dir="ltr"
 *
 * @since Essence 1.0.0
 */
function essence_language_attributes()
{
  $attributes = array();
  $output     = '';

  if (function_exists('is_rtl')) {
    if (is_rtl() == 'rtl') {
      $attributes[] = 'dir="rtl"';
    }
  }

  $lang = get_bloginfo('language');

  if ($lang && $lang !== 'en-US') {
    $attributes[] = "lang=\"$lang\"";
  } else {
    $attributes[] = 'lang="en"';
  }

  $output = implode(' ', $attributes);
  $output = apply_filters('essence_language_attributes', $output);

  return $output;
}
add_filter('language_attributes', 'essence_language_attributes');

/**
 * Cleanup output of stylesheet <link> tags
 *
 * @since Essence 1.0.0
 */
function essence_clean_style_tag($input)
{
  preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);

  // Only display media if it's print
  $media = $matches[3][0] === 'print' ? ' media="print"' : '';
  return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
}
add_filter('style_loader_tag', 'essence_clean_style_tag');

/**
 * Remove unnecessary self-closing tags
 *
 * @since Essence 1.0.0
 */
function essence_remove_self_closing_tags($input)
{
  return str_replace(' />', '>', $input);
}
add_filter('get_avatar', 'essence_remove_self_closing_tags');
add_filter('comment_id_fields', 'essence_remove_self_closing_tags');
add_filter('post_thumbnail_html', 'essence_remove_self_closing_tags');

/**
 * Add the RSS feed link in the <head> if there's posts
 *
 * @since Essence 1.0.0
 */
function essence_feed_link() {
  $count = wp_count_posts('post'); if ($count->publish > 0) {
    echo "\n\t<link rel=\"alternate\" type=\"application/rss+xml\" title=\"". get_bloginfo('name') ." Feed\" href=\"". home_url() ."/feed/\">\n";
  }
}
add_action('wp_head', 'essence_feed_link', -2);

/**
 * Don't return the default description in the RSS feed if it hasn't been changed
 *
 * @since Essence 2.0
 */
function essence_remove_default_description($bloginfo) {
  $default_tagline = 'Just another WordPress site';
  return ($bloginfo === $default_tagline) ? '' : $bloginfo;
}
add_filter('get_bloginfo_rss', 'essence_remove_default_description');

/**
 * Remove unnecessary dashboard widgets
 *
 * @since Essence 1.0.0
 *
 * @link http://www.deluxeblogtips.com/2011/01/remove-dashboard-widgets-in-wordpress.html
 */
function essence_remove_dashboard_widgets()
{
  remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
  remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
  remove_meta_box('dashboard_primary', 'dashboard', 'normal');
  remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
}
add_action('admin_init', 'essence_remove_dashboard_widgets');

/**
 * Allow more tags in TinyMCE including <iframe> and <script>
 *
 * @since Essence 1.0.0
 */
function essence_change_mce_options($options)
{
  $ext = 'pre[id|name|class|style],iframe[align|longdesc|name|width|height|frameborder|scrolling|marginheight|marginwidth|src],script[charset|defer|language|src|type]';
  if (isset($initArray['extended_valid_elements'])) {
    $options['extended_valid_elements'] .= ',' . $ext;
  } else {
    $options['extended_valid_elements'] = $ext;
  }
  return $options;
}
add_filter('tiny_mce_before_init', 'essence_change_mce_options');

/**
 * Tell WordPress to use searchform.php from the templates/ directory
 *
 * @since Essence 1.0.0
 */
function essence_get_search_form() {
  locate_template('/templates/searchform.php', true, true);
}
add_filter('get_search_form', 'essence_get_search_form');
