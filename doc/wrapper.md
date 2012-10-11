[Documentation table of contents](TOC.md)

# Theme wrapper

The theme wrapper functionality is found in `lib/utilities.php`. This code comes
directly from [scribu's theme wrapper](http://scribu.net/wordpress/theme-wrappers.html) post.

`base.php` is used to serve all of the templates for your site. In the theme
root, the following files are only used to include files in the `templates/`
directory, which contains all of the [theme templates](templates.md):

* `index.php` (archive page templates) includes `templates/content.php`
* `page.php` includes `templates/content-page.php`
* `single.php` includes `templates/content-single.php`

The [Template Hierarchy](http://codex.wordpress.org/Template_Hierarchy) is
traversed as normal before the wrapper is loaded.
