<?php
/**
 * Custom walker for `wp_list_comments()`
 *
 * @package WordPress
 * @subpackage Essence
 */

class Essence_Comment extends Walker_Comment {
    function start_el(&$output, $comment, $depth, $args, $id = 0) {
        $depth++;
        $GLOBALS['comment_depth'] = $depth;
        $GLOBALS['comment']       = $comment; ?>

        <li>
            <figure><?php echo get_avatar($comment) ?></figure>
            <header>
                <h4><?php echo get_comment_author_link() ?></h4>
                <time datetime="<?php echo get_comment_date('c') ?>">
                    <?php echo get_comment_date() . ' ' . get_comment_time() ?>
                </time>
                <a href="<?php echo get_comment_link($comment->comment_ID) ?>"><?php echo '#' . get_comment_ID() ?></a>
                <a href="<?php echo get_edit_comment_link() ?>"><?php echo __('(Edit)', THEME_NAME) ?></a>
            </header>
            <?php comment_text();
    }

    function end_el(&$output, $comment, $depth = 0, $args = array()) { ?>
        </li>
    <?php }
}
