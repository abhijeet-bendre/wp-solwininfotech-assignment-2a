=== Solwin Infotech Plugin Assignment-2a: Simple Alert Plugin ===
Contributors: (abhijeetbendre)
Requires at least: 4.8.1
Tested up to: 4.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin for Assignment 2a from here: https://www.solwininfotech.com/career/wordpress-developer/wordpress-assignments/

== Description ==

##  Admin-Side:

    Creates an admin-side page under **settings tab**. This page contains following fields,
    1. Text field for adding text which will be displayed as alert on front side.
    2. Checkboxes for all custom post types. For ex:
    ..* posts
    ..* pages
    When **admin checks any of the above post type’s checkbox**, all posts of that post type will be listed below in multi-selectbox. 
    **User can select multiple posts** from the selection for which they want to **show alert on frontend**.
    
    “Save Changes” button to save your changes.

## Front-end:

    When **user will open any page**, post or custom post type post and **if it is selected from admin side** then **alert box should be opened**.
    Alert box should contain admin side added text from settings page.

== Installation ==
 
1. Upload the entire `wp-solwininfotech-assignment-2a` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

You will find 'Solwin Infotech Plugin Assignment 2a' menu in your WordPress admin panel under **Settings** menu.

 
== Changelog ==
 
= 0.1 =
* First Version
 
