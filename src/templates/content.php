<?php
/**
 * The default template for displaying content
 *
 * @package WordPress
 * @subpackage Essence
 */
?>

<?php if (have_posts()): ?>

    <?php while (have_posts()): the_post(); ?>
        <article>
            <header>
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <?php get_template_part('templates/entry-meta'); ?>
            </header>

            <?php the_content(__('Continue reading &rarr;', THEME_NAME)); ?>

            <footer>
                <?php $categories = get_the_category(); if ($categories): ?>
                    <p><?php _e('Posted in', THEME_NAME); ?> <?php the_category(' '); ?></p>
                <?php endif; ?>

                <?php $tags = get_the_tags(); if ($tags): ?>
                    <p><?php _e('Tagged with', THEME_NAME); ?> <?php the_tags(); ?></p>
                <?php endif; ?>
            </footer>
        </article>
    <?php endwhile; ?>

<?php else: ?>

    <article>
        <header>
            <h2><?php _e('Sorry, no results were found', THEME_NAME); ?></h2>
        </header>

        <p><?php _e('Please try the following:', THEME_NAME); ?></p>
        <ul>
            <li><?php _e('Check your spelling', THEME_NAME); ?></li>
            <li><?php printf(__('Return to the <a href="%s">home page</a>', THEME_NAME), home_url()); ?></li>
            <li><?php _e('Click the <a href="javascript:history.back()">Back</a> button', THEME_NAME); ?></li>
        </ul>

        <?php get_search_form(); ?>
    </article>

<?php endif; ?>
