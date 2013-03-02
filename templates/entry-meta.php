<?php
/**
 * Entry meta
 *
 * @package WordPress
 * @subpackage Essence
 */
?>

<time datetime="<?php echo get_the_time('c'); ?>" pubdate><?php echo get_the_date(); ?></time>
<p><?php echo __('Written by', THEME_NAME); ?> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" rel="author"><?php echo get_the_author(); ?></a></p>
