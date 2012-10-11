[Documentation table of contents](TOC.md)

# Theme templates

### comments.php

The comments template wraps each comment in an `<article>` tag. The comments are
then listed in a ordered list.

### content.php

The `content.php` template is included by archive templates (`index.php` etc.)
in the theme root.

### content-page.php

The `content-page.php` template is included by page templates (`page.php` etc.)
in the theme root.

### content-single.php

The `content-single.php` template is included by `single.php` in the theme root.

### entry-meta.php

`entry-meta.php` displays the author byline, post time, and date information.

### footer.php

`footer.php` includes the footer widget area and displays the site copyright
information. This is also where all JS gets outputted.

### head.php

`head.php` includes everything in the `<head>`. This is also where all CSS gets
outputted.

### header.php

`header.php` outputs our site title and `wp_nav_menu()`.

### searchform.php

`searchform.php` is the template used when `get_search_form()` is called.

### sidebar.php

`sidebar.php` includes the primary widget area.

### title.php

`title.php` is included at the top of files in the theme root to display the
`<h1>` on pages before the page content.
