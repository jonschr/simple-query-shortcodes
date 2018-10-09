# Simple Query Shortcodes

This loop plugin has one piece of functionality I've found to be missing in most of the commercial plugins that do similar things: it allows for custom layouts to be registered by themes or plugins, then assigned to a loop pulling in a particular subset of posts.

## Table of contents

* [Registering a layout](#registering-a-layout)
* [Sample shortcodes](#sample-shortcodes)

## Registering a layout

This set of instructions are for theme and plugin authors. If you'd like to add a layout, this is the code to copy and paste. There are two functions in here – the first for adding things *before* the loop starts (scripts and styles, usually), and the second for setting up the markup *within* each post in the loop.

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

```
[loop]
```
