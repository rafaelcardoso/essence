<?php
/**
 * The template for displaying content in the single.php template
 *
 * @package WordPress
 * @subpackage Essence
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <article>
    <header>
      <h1><?php the_title(); ?></h1>
      <?php get_template_part('templates/entry-meta'); ?>
    </header>

    <?php the_content(); ?>

    <footer>
      <?php $categories = get_the_category(); if ($categories) : ?>
        <p><?php _e('Posted in', THEME_NAME); ?> <?php the_category(' '); ?></p>
      <?php endif; ?>

      <?php $tags = get_the_tags(); if ($tags) : ?>
        <p><?php _e('Tagged with', THEME_NAME); ?> <?php the_tags(); ?></p>
      <?php endif; ?>
    </footer>

    <?php comments_template('templates/comments.php'); ?>
  </article>
<?php endwhile; ?>
