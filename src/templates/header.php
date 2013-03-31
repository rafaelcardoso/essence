<?php
/**
 * Displays the header and navigation
 *
 * @package WordPress
 * @subpackage Essence
 */
?>

<header role="banner">
    <h1><a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
</header>

<nav role="navigation">
    <?php wp_nav_menu(array(
        'theme_location' => 'nav_primary',
        'walker' => new Essence_Nav_Menu(),
        'menu_class' => 'nav',
        'fallback_cb' => false
    ));
    ?>
</nav>
