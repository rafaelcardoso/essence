<?php
/**
 * Displays the <head> section
 *
 * @package WordPress
 * @subpackage Essence
 */
?>

<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
    <meta charset="utf-8">
    <title><?php wp_title('&ndash;', true, 'right'); bloginfo('name'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
