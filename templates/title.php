<?php
/**
 * The template used for displaying the page title.
 *
 * @package WordPress
 * @subpackage Essence
 */
?>

<header>
  <h1>
    <?php
      if (is_home()) {
        if (get_option('page_for_posts', true)) {
          echo get_the_title(get_option('page_for_posts', true));
        } else {
          _e('Latest posts', THEME_NAME);
        }
      }
      elseif (is_archive()) {
        $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
        if ($term) {
          echo $term->name;
        } elseif (is_post_type_archive()) {
          echo get_queried_object()->labels->name;
        } elseif (is_day()) {
          printf(__('Daily Archives: %s', THEME_NAME), get_the_date());
        } elseif (is_month()) {
          printf(__('Monthly Archives: %s', THEME_NAME), get_the_date('F Y'));
        } elseif (is_year()) {
          printf(__('Yearly Archives: %s', THEME_NAME), get_the_date('Y'));
        } elseif (is_author()) {
          global $post;
          $author_id = $post->post_author;
          printf(__('Author Archives: %s', THEME_NAME), get_the_author_meta('display_name', $author_id));
        }
        else {
          single_cat_title();
        }
      }
      elseif (is_search()) {
        printf(__('Search Results for %s', THEME_NAME), get_search_query());
      }
      elseif (is_404()) {
        _e('404 — page not found', THEME_NAME);
      }
      else {
        the_title();
      }
    ?>
  </h1>
</header>
