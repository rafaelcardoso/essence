<?php
/**
 * Add and remove features
 *
 * 1. `wp_head()` customization
 * 2. Canonical links support
 * 3. Root relative URLs
 * 4. Search customization
 * 5. Normalize language attributes
 * 6. Normalize `<style>` tags
 * 7. TinyMCE options
 * 8. Dashboard widgets removal
 * 9. Don't show default description in feed
 *
 * @package WordPress
 * @subpackage Essence
 */

/**
 * Customize `wp_head()`
 *
 * @link http://wpengineer.com/1438/wordpress-header/
 */
function essence_head_cleanup() {
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

    // Remove WordPress version from RSS feed
    add_filter('the_generator', '__return_false');

    // Remove gallery inline CSS
    add_filter('use_default_gallery_style', '__return_null');

    // Remove recent comments inline CSS
    global $wp_widget_factory;
    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));

    // Determine whether to use custom support for canonical links or not
    if (!class_exists('WPSEO_Frontend')) {
        remove_action('wp_head', 'rel_canonical');
        add_action('wp_head', 'essence_rel_canonical');
    }
}
add_action('init', 'essence_head_cleanup');

/**
 * Add support for canonical links
 */
function essence_rel_canonical() {
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
 *
 * @link http://www.456bereastreet.com/archive/201010/how_to_make_wordpress_urls_root_relative/
 */
function essence_rooturl($input) {
    if(!is_admin() && site_url() != home_url() && stristr($input, 'wp-includes') === false) {
        $input = str_replace(site_url(), "", $input);
    }

    $output = preg_replace_callback(
        '!(https?://[^/|"]+)([^"]+)?!',
        create_function(
            '$matches',
            // If full URL is home_url("/") and this isn't a subdir install, return a slash for relative root
            'if (isset($matches[0]) && $matches[0] === home_url("/") && str_replace("http://", "", home_url("/", "http"))==$_SERVER["HTTP_HOST"]) { return "/";' .
            // If domain is equal to home_url("/"), then make URL relative
            '} elseif (isset($matches[0]) && strpos($matches[0], home_url("/")) !== false) { return $matches[2];' .
            // If domain is not equal to home_url("/"), do not make external link relative
            '} else { return $matches[0]; };'
        ),
        $input
    );

    // Detect and correct for subdir installs
    if($subdir = parse_url(home_url(), PHP_URL_PATH)) {
        if(substr($output, 0, strlen($subdir)) == (substr($output, strlen($subdir), strlen($subdir)))) {
            $output = substr($output, strlen($subdir));
        }
    }

    return $output;
}

/**
 * Enable root relative URLs
 */
function essence_rooturl_enable() {
    return !(is_admin() && in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php')));
}
if (essence_rooturl_enable()) {
    $args = array(
        'bloginfo_url',
        'theme_root_uri',
        'stylesheet_directory_uri',
        'template_directory_uri',
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
        'the_author_posts_link',
        'script_loader_src',
        'style_loader_src'
    );
    add_filters($args, 'essence_rooturl');
}

/**
 * Redirects search results from `/?s=query` to
 * `/search/query/`, converts `%20` to `+`
 *
 * @link http://txfx.net/wordpress-plugins/nice-search/
 */
function essence_search_redirect() {
    global $wp_rewrite;
    if (!isset($wp_rewrite) || !is_object($wp_rewrite) || !$wp_rewrite->using_permalinks()) {
        return;
    }

    $search_base = $wp_rewrite->search_base;
    if (is_search() && !is_admin() && strpos($_SERVER['REQUEST_URI'], "/{$search_base}/") === false) {
        wp_redirect(home_url("/{$search_base}/" . urlencode(get_query_var('s'))));
        exit();
    }
}
add_action('template_redirect', 'essence_search_redirect');

/**
 * Fix for empty search queries redirecting to home page
 *
 * @link http://wordpress.org/support/topic/blank-search-sends-you-to-the-homepage#post-1772565
 * @link http://core.trac.wordpress.org/ticket/11330
 */
function essence_search_empty($args) {
    if (isset($_GET['s']) && empty($_GET['s'])) {
        $args['s'] = ' ';
    }

    return $args;
}
add_filter('request', 'essence_search_empty');

/**
 * Tell WordPress to use searchform.php from the templates/ directory
 */
function essence_search_form($args) {
    if ($args === '') {
        locate_template('/templates/searchform.php', true, false);
    }
}
add_filter('get_search_form', 'essence_search_form');

/**
 * Normalize `language_attributes()` used in `<html>` tag
 */
function essence_language_attributes() {
    $attributes = array();
    $output     = '';
    $lang       = get_bloginfo('language');

    // Remove `dir="ltr"`
    if (function_exists('is_rtl')) {
        if (is_rtl() == 'rtl') {
            $attributes[] = 'dir="rtl"';
        }
    }

    // Change `lang="en-US"` to `lang="en"`
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
 * Customize output of stylesheet `<link>` tags
 */
function essence_style_tag($input) {
    preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);
    // Only display media if it's print
    $media = $matches[3][0] === 'print' ? ' media="print"' : '';
    return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
}
add_filter('style_loader_tag', 'essence_style_tag');

/**
 * Allow more tags in TinyMCE
 */
function essence_mce_options($args) {
    $tag = 'pre[id|name|class|style],iframe[align|longdesc|name|width|height|frameborder|scrolling|marginheight|marginwidth|src],script[charset|defer|language|src|type]';

    if (isset($initArray['extended_valid_elements'])) {
        $args['extended_valid_elements'] .= ',' . $tag;
    } else {
        $args['extended_valid_elements'] = $tag;
    }

    return $args;
}
add_filter('tiny_mce_before_init', 'essence_mce_options');

/**
 * Remove unnecessary dashboard widgets
 *
 * @link http://www.deluxeblogtips.com/2011/01/remove-dashboard-widgets-in-wordpress.html
 */
function essence_dashboard_widgets() {
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
    remove_meta_box('dashboard_primary', 'dashboard', 'normal');
    remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
}
add_action('admin_init', 'essence__dashboard_widgets');

/**
 * Don't return the default description in the RSS feed
 */
function essence_default_description($bloginfo) {
    $default_tagline = 'Just another WordPress site';
    return ($bloginfo === $default_tagline) ? '' : $bloginfo;
}
add_filter('get_bloginfo_rss', 'essence_default_description');
