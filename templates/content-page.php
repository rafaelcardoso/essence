<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Essence
 */
?>

<?php while (have_posts()): the_post(); ?>
  <?php the_content(); ?>
<?php endwhile; ?>
