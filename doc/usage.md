[Documentation table of contents](TOC.md)

# Usage

What follows is a general overview of each major part and how to use them.

### assets/css/

This directory should contain all your project's CSS files. Reference these
from `lib/theme.php`.

### assets/img/

This directory should contain all your project's image files.

### assets/js/

This directory should contain all your project's JS files. Libraries, plugins,
and custom code can all be included here. It includes some initial JS to help
get you started. Reference these from `lib/theme.php`.

### doc/

This directory contains all the Essence documentation. You can use it as the
location and basis for your own project's documentation.

### lang/

This directory contains all of the theme translations. [About translating the theme](http://www.icanlocalize.com/site/tutorials/how-to-translate-with-gettext-po-and-pot-files/).

### lib/

This directory contains all of the theme functionality. [About the theme library](lib.md).

### templates/

This directory contains all of the theme templates. [About the templates](templates.md).

### 404.php

A helpful custom 404 to get you started.

### base.php

This is the default HTML skeleton that forms the basis of all pages on your
site. [About the theme wrapper](wrapper.md).

### functions.php

This file loads all of the [theme library](lib.md) files.

### index.php

This file is used to serve all of the archive templates.

### page.php

This file is used to serve the page template.

### single.php

This file is used to serve the single post template.

### style.css

This file is used to tell WordPress that we're a theme. None of the actual CSS
is contained in this file.
