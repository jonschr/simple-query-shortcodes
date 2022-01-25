### 1.4.1
* Whoops. Didn't actually publish changes in 1.4.
### 1.4
* Adding 'exclude_current' parameter. When set to true, it adds 'post__not_in = array( get_the_ID() )' to the query parameters, excluding the current post.
### 1.3
* Adding 'has-post-thumbnail' class to posts

### 1.2
* Update capabilities added natively
* BUGFIX: Fixed isset() notices when term or taxonomy (but not both at the same time) were used

### 1.1.2-1.1.4
* Minor changes over time, so small that they didn't get noted

### 1.1.1
* BUGFIX: Queries where the post type has multiple taxonomies weren't pulling correctly

### 1.0.3

* Verifying that our update functionality is operating correclty

### 1.0.2

* BUGFIX: When a CPT was being used along with a custom layout, the layout wasn't being selected properly. Instead, the plugin was grabbing the layout which corresponds to the name of the CPT (in most cases these will be the same anyway)
* Added documentation and comments throughout

### 1.0.1

* Adding Github updater capability
* Updating plugin metadata

### 1.0

* Code refactored
* Code updated to add filters to allow for cleaner, simpler code in terms of editing variables, eliminating the need for a bunch of complex, nested conditional statements