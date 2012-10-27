[Documentation table of contents](TOC.md)

# Clean up

Clean up is handled by `lib/cleanup.php`. Major parts include:

### Clean up wp_head()

* Remove unnecessary <link>'s
* Remove inline CSS used by Recent Comments widget
* Remove self-closing tag and change ''s to "'s on rel_canonical()
* Remove Wordpress version from RSS feed

### Root relative URLs

When active this will return URLs such as `/assets/css/main.css` instead of
`http://example.com/assets/css/main.css`.

### Clean up the_excerpt()

The excerpt length is defined in `lib/config.php`. Excerpts are ended with
anchor to the post and with "Continue reading" instead of "[…]".

### Clean up wp_nav_menu()

Walker_Nav_Menu (WordPress default) example output:

```html
<li id="menu-item-8" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-8"><a href="/">Home</a></li>
<li id="menu-item-9" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9"><a href="/sample-page/">Sample Page</a></li>
```

Essence_Nav_Walker example output:

```html
<li class="menu-home"><a href="/">Home</a></li>
<li class="menu-sample-page"><a href="/sample-page/">Sample Page</a></li>
```

Instead of the many different active class varities that WordPress usually
uses, only `is-active` is returned on active items.

### Remove unnecessary self-closing tags

Self-closing tags aren't necessary with HTML5. They're removed on:

* `get_avatar()` (`<img />`)
* `comment_id_fields()` (`<input />`)
* `post_thumbnail_html()` (`<img />`)

### Don't return the default description in the RSS feed if it hasn't been changed

If your site tagline is still `Just another WordPress site` then the
description isn't returned in the feed.

### Allow more tags in TinyMCE

Allow `<iframe>` and `<script>` to be used without issues.

### Clean up search URLs

Redirect `/?s=query` to `/search/query/`, convert `%20` to `+`.
