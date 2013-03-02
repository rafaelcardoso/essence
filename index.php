<?php
/**
 * The main template file
 *
 * @package WordPress
 * @subpackage Essence
 */
?>

<?php get_template_part('templates/title'); ?>
<?php get_template_part('templates/content', get_post_format()); ?>
