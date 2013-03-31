<?php
/**
 * The template for displaying search forms
 *
 * @package WordPress
 * @subpackage Essence
 */
?>

<form method="get" action="<?php echo esc_url(home_url('/')); ?>" role="search">
    <label for="s"><?php _e('Search', THEME_NAME); ?></label>
    <input type="search" name="s" placeholder="<?php esc_attr_e('Search', THEME_NAME); ?> <?php bloginfo('name'); ?>">
    <input type="submit" value="<?php esc_attr_e('Search', THEME_NAME); ?>">
</form>
