=== BuddyMedia ===
Contributors: modemlooper, webdevstudios, jazzs3quence, tw2113
Tags: buddypress, media, images
Requires at least: 4.2
Tested up to: 4.7
Stable tag: 1.0.1
License: GPLv2

Media component for BuddyPress

== Description ==
Adds a media component to BuddyPress to allow users to attach media to activity postings and user generated media albums.

[Pluginize](https://pluginize.com/?utm_source=buddy-media&utm_medium=text&utm_campaign=wporg) was launched in 2016 by [WebDevStudios](https://webdevstudios.com/) to promote, support, and house all of their [WordPress products](https://pluginize.com/shop/?utm_source=buddy-media&utm_medium=text&utm_campaign=wporg). Pluginize is dedicated to creating products that make your [BuddyPress site](https://pluginize.com/product-category/buddypress/?utm_source=buddy-media&utm_medium=text&utm_campaign=wporg) easy to manage, without having to touch a line of code. Pluginize also provides [ongoing support and development for WordPress community favorites like CPTUI](https://wordpress.org/plugins/custom-post-type-ui/), [CMB2](https://wordpress.org/plugins/cmb2/), and more.

== Installation ==
= Automatic Installation =
1. From inside your WordPress administration panel, visit \'Plugins -> Add New\'
2. Search for \'BuddyMedia\' and find this plugin in the results
3. Click \'Install\'
4. Activate the plugin through the \'Plugins\' menu in WordPress

= Manual Installation =

1. Upload \'buddymedia\' to the \'/wp-content/plugins/\' directory
2. Activate the plugin through the \'Plugins\' menu in WordPress

== Frequently Asked Questions ==
=Activity Attachments=

When the plugin is activated there will be a button added to the activity post form to allow attaching an image to status updates. These are stored in an \"Attachments\" album on a profile. If this album does not exist it will be created. NOTE: This plugin replaces the default post-form.php. The file replacing the default from is similar in structure with a few additions to accommodate the UI for adding images. Also some styles have been added to give the form a more dedicated structure.

=Group Attachments=

The groups by default do not show the \"add image\" button on the activity form. In the group\'s settings there is an option to turn this functionality on per group. You can turn this on for all groups with define(\'ENABLE_MEDIA\', true). In the future this define will be moved to global settings. The images attached to group activity will show in a users Attachments Album. Currently no group album functionality but will be added in a future update.

=Creating Albums=

To create albums, visit your profile and click the media tab. After an album is created you can add images and edit the album info. There are three album types, public, friends, and private:

Public - viewable to anyone including logged out users
Friends - viewable by only those in your friends list
Private - only viewable but the user who created the album (loggedin user)

=DEV Notes=

This plugin is a bit different that other media plugins for BuddyPress. Each album created is a custom post type. You can view and manage the albums in the admin just as you would a post or a page. The albums are listed under the menu item \"User Media\". Each image in the album is a post attachment.

== Changelog ==
* fix albums not showing when albums created in admin
* added UI to add images to album from admin

== Screenshots ==
1. Activity Attachment
2. User Album grid
3. Single Image
