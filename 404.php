<?php
/**
 * The template for displaying 404 pages
 *
 * @package WordPress
 * @subpackage Essence
 */
?>

<article>
  <?php get_template_part('templates/title'); ?>

  <p><?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for.', THEME_NAME); ?></p>

  <p><?php _e('Please try the following:', THEME_NAME); ?></p>
  <ul>
    <li><?php _e('Check your spelling', THEME_NAME); ?></li>
    <li><?php printf(__('Return to the <a href="%s">home page</a>', THEME_NAME), home_url()); ?></li>
    <li><?php _e('Click the <a href="javascript:history.back()">Back</a> button', THEME_NAME); ?></li>
  </ul>

  <?php get_search_form(); ?>
</article>
