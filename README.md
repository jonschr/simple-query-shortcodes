# Simple Query Shortcodes

This loop plugin has one piece of functionality I've found to be missing in most of the commercial plugins that do similar things: it allows for custom layouts to be registered by themes or plugins, then assigned to a loop pulling in a particular subset of posts.

## Table of contents

* [Registering a layout](#registering-a-layout)
* [Sample shortcodes](#sample-shortcodes)
	* [Blog posts](#blog-posts)
	* [Pull in a specific post](#pull-in-a-specific-post)
	* [Custom post types](#custom-post-types)
	* [Custom taxonomies](#custom-taxonomies)
* [View all parameters](#parameters)

## Registering a layout

This set of instructions are for theme and plugin authors. If you'd like to add a layout, this is the code to copy and paste. There are two functions in here – the first for adding things *before* the loop starts (scripts and styles, usually), and the second for setting up the markup *within* each post in the loop.

(The first one is commented out because for simple use cases it won't be needed). Usually I add all of the styles in the theme or plugin that's registering the layout to avoid loading unnecessary files.

**Please note: correct usage would be to register the script elsewhere and to use it here, NOT to simply enqueue it directly**

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

For example, these should pull in exactly the same thing:

```
[loop category="uncategorized" ]
[loop terms="uncategorized"]
[loop taxonomy="categories" terms="uncategorized"]
```

And here's the killer functionality. You can attach these loops to a layout you register in a theme or plugin (instructions and sample code below). Then use it like so:

```
[loop post_type="myposttype" layout="mycustomlayout"]
```

To pull in a specific number of recent posts, something like this will work:

```
[loop posts_per_page="9"]
```

### Pull in a specific post
This pulls in the post with a specific ID.

```
[loop p="123"]
```

### Custom post types

You can pull in custom content types as well. If there's a custom content type, the plugin will detect whether a layout has been registered with the same name, and will load that as a default if nothing else has been manually set.

This will pull in a list of testimonials, using the "testimonials" layout if one exists or the "default" layout if there isn't one:

```
[loop post_type="testimonials"]
```

### Custom taxonomies

This can be used with posts *or* custom post types, but is only commonly used with CPTs. If there's only one custom taxonomy registered, then you can simply leave out the taxonomy, and the plugin will detect it.

```
[loop post_type="testimonials" taxonomy="testimonial-categories" terms="featured"]
```

## Parameters

It's probably useful to see a complete list of parameters that can be used. Here's that list, with their associated defaults, and they correspond to the commonly-used ones in [the WordPress documentation](https://codex.wordpress.org/Class_Reference/WP_Query), plus a few extras:

```php
'debug' 		=> null,
'category' 		=> null,
'category_name' 	=> null,
'post_type' 		=> 'post',
'post__in' 		=> null,
'posts_per_page' 	=> '-1',
'p' 			=> null,
'name' 			=> null,
'taxonomy' 		=> null,
'field' 		=> 'slug',
'terms' 		=> null,
'operator' 		=> 'IN',
'orderby' 		=> null,
'order' 		=> null,
'offset' 		=> null,
'columns' 		=> 1,
'layout' 		=> null,
'align' 		=> 'left',
'extras' 		=> null,
'class' 		=> null,
'style' 		=> null,
'connected_type' 	=> null,
```

