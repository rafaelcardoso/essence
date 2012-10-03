<?php
/**
 * The head for our theme.
 *
 * Displays the <head> section.
 *
 * @package WordPress
 * @subpackage Essence
 */
?>

<!doctype html>
<!--[if IE 8]> <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
  <meta name="viewport" content="width=device-width">
  <?php wp_head(); ?>
</head>
