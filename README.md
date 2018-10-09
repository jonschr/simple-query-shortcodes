# Simple Query Shortcodes

This loop plugin has one piece of functionality I've found to be missing in most of the commercial plugins that do similar things: it allows for custom layouts to be registered by themes or plugins, then assigned to a loop pulling in a particular subset of posts.

## Table of contents

* [Registering a layout](#registering-a-layout)
* [Sample shortcodes](#sample-shortcodes)

## Registering a layout

This set of instructions are for theme and plugin authors. If you'd like to add a layout, this is the code to copy and paste. There are two functions in here – the first for adding things *before* the loop starts (scripts and styles, usually), and the second for setting up the markup *within* each post in the loop.

(The first one is commented out because for simple use cases it won't be needed). Usually I add all of the styles in the theme or plugin that's registering the layout to avoid loading unnecessary files.

```php
//* Output THELAYOUTNAME before
// add_action( 'before_loop_layout_THELAYOUTNAME', 'rb_THELAYOUTNAME_before' );
function rb_THELAYOUTNAME_before( $args ) {
	// wp_enqueue_script( 'SCRIPTHANDLE' );
}

//* Output each THELAYOUTNAME
add_action( 'add_loop_layout_THELAYOUTNAME', 'rb_THELAYOUTNAME_each' );
function rb_THELAYOUTNAME_each() {

	//* Global vars
	global $post;
	$id = get_the_ID();

	//* Vars
	$title = get_the_title();
	$permalink = get_the_permalink();
	// $thing = get_post_meta( $id, 'thing', true );

	//* Markup
	the_title();
	the_content();
}
```

## Sample shortcodes

### Blog posts

The most basic use case is simply the shortcode with no parameters. This will default to pulling a list of all of the posts, using the default layout (which includes the title and excerpt).

```
[loop]
```

There are several ways to add categories into the loop. The plugin should recognize these and set semi-intelligent defaults to try to account for common errors in writing the shortcodes.

```
[loop category="uncategorized" ]
[loop terms="uncategorized"]
```

### Custom post types

You can pull in custom content types as well. If there's a custom content type, the plugin will detect whether a layout has been registered with the same name, and will load that as a default if nothing else has been manually set.

This will pull in a list of testimonials, using the "testimonials" layout if one exists or the "default" layout if there isn't one:

```
[loop post_type="testimonials"]
```

