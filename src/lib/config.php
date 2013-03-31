<?php
/**
 * Configuration
 *
 * @package WordPress
 * @subpackage Essence
 */

/**
 * Define which pages that shouldn't have the sidebar
 * See lib/sidebar.php for more details
 */
function essence_display_sidebar() {
    $sidebar_config = new Essence_Sidebar(
        // Conditional tag checks
        // Any of these conditional tags that return true won't show the sidebar
        // http://codex.wordpress.org/Conditional_Tags
        array(
            'is_404',
            'is_front_page'
        ),
        // Page template checks (via is_page_template())
        // Any of these page templates that return true won't show the sidebar
        array(
        )
    );

    return $sidebar_config->display;
}
