<?php
/**
 * The template for displaying the footer.
 *
 * @package WordPress
 * @subpackage Essence
 */
?>

<footer role="contentinfo">
  <?php dynamic_sidebar('widget-footer'); ?>
  <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
</footer>

<?php wp_footer(); ?>
