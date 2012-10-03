<?php
/**
 * Base template
 *
 * @package WordPress
 * @subpackage Essence
 */
?>

<?php get_template_part('templates/head'); ?>

<body <?php body_class(); ?>>

  <?php get_template_part('templates/header'); ?>

  <div role="main">
    <?php include essence_template_path(); ?>
  </div>

  <?php get_template_part('templates/sidebar'); ?>

<?php get_template_part('templates/footer'); ?>

</body>
</html>
