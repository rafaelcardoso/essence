<?php
/**
 * Base template
 *
 * @package WordPress
 * @subpackage Essence
 */
?>

<?php get_template_part('templates/head'); ?>

<body>

  <?php get_template_part('templates/header'); ?>

  <div role="main">
    <?php include essence_template_path(); ?>
  </div>

  <?php if (essence_display_sidebar()) : ?>
    <?php include essence_sidebar_path(); ?>
  <?php endif; ?>

<?php get_template_part('templates/footer'); ?>

</body>
</html>
