<?php
/**
 * The template for displaying comments
 *
 * @package WordPress
 * @subpackage Essence
 */
?>

<section>
    <?php if (post_password_required()) : ?>
    <p><?php _e('This post is password protected. Enter the password to view any comments.', THEME_NAME); ?></p>
</section>

    <?php
            return;
        endif;
    ?>

    <?php if (have_comments()) : ?>

    <h2><?php comments_number(); ?></h2>
    <ol>
        <?php wp_list_comments(array('walker' => new Essence_Comment)); ?>
    </ol>

    <?php
        // If there are no comments and comments are closed, let's leave a little
        // note, shall we? But we don't want the note on pages or post types that
        // do not support comments.
        elseif (!comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments')) :
    ?>

    <p><?php _e('Comments are closed.', THEME_NAME); ?></p>

    <?php endif; ?>
    <?php comment_form(); ?>

</section>
