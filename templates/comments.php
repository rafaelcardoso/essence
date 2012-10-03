<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to essence_comment().
 *
 * @package WordPress
 * @subpackage Essence
 */
?>

<?php
/**
 * Custom callback for outputting comments
 *
 * @since Essence 1.0.0
 */
function essence_comment($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment; ?>
  <?php if ($comment->comment_approved == '1'): ?>
  <li>
    <article id="comment-<?php comment_ID() ?>">
      <?php echo get_avatar($comment); ?>
      <h4><?php comment_author_link() ?></h4>
      <time><a href="#comment-<?php comment_ID() ?>" pubdate><?php comment_date() ?> at <?php comment_time() ?></a></time>
      <?php comment_text() ?>
    </article>
  </li>
  <?php endif; ?>
<?php } ?>

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
    <?php wp_list_comments( array('callback' => 'essence_comment')); ?>
  </ol>

  <?php
    /* If there are no comments and comments are closed, let's leave a little note, shall we?
     * But we don't want the note on pages or post types that do not support comments.
     */
    elseif (! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments')) :
  ?>
  <p><?php _e('Comments are closed.', THEME_NAME); ?></p>
  <?php endif; ?>
  <?php comment_form(); ?>

</section>
