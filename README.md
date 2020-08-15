# JAMP Notes (Just Another Memo Plugin) - WordPress Plugin

**NOTE:** this is a development repository. Please download latest stable release from the WordPress.org directory: https://wordpress.org/plugins/jamp-notes/.

## Description

Using this plugin you can attach notes to some elements in the WordPress dashboard, such as:

- posts
- pages
- custom post types from other plugins (except the notes from this plugin)
- users
- plugins
- dashboard sections
- the whole dashboard

It can be helpfull if you manage a site with other people or just to take notes for yourself.

## Features

- manage notes like the standard posts by opening the Notes page from the admin menu.
- while editing a note, use the meta box on the right to set note properties.
- manage section and global notes from the admin bar.
- manage item notes (eg. posts and pages) from the custom column in the admin pages.
- get notes details by hovering the mouse on the "Info" links or clicking on the "I" icons.
- add images and links in the note content.
- deleted notes go to the trash, so they can be restored.
- automatically discovers custom post types added by other plugins (eg. events, books...).
- creates a list of the dashboard sections based on the admin menu items.

## Configuration

Settings for the plugin are available on the *Settings* -> *JAMP Notes* page.

Please note:

- by default the plugin data is kept after uninstall. You can choose to delete notes and settings enabling the data removal option.
- after activation, the plugin enables notes for all the existing public post types. If you then install other plugins which create new post types, you have to manually enable them in the settings.

## Permissions

The notes are currently available only for the Admin users.
Each Admin can manage all notes.

## Support

If you find any errors or compatibility issues with other plugins, please let me know in the [plugin support forum](https://wordpress.org/support/plugin/jamp-notes/). Thanks!

## Privacy

This plugin does not collect or store any user data. It does not set any cookies and it does not connect to any third-party services.

## Installation

### Installing from WordPress

This is the preferred way since it allows easy updates and provides latest stable release.

1. Go to *Plugins* -> *Add New* in the WordPress dashboard.
2. Insert "jamp" in the search field.
3. Click on the *Install Now* button.
4. Click on the *Activate* button.

### Installing from Zip file

1. Download the plugin zip file.
2. Go to *Plugins* -> *Add New* in the WordPress dashboard.
3. Click on the *Upload Plugin* button.
4. Browse for the plugin zip file and click on *Install Now*.
5. Activate the plugin.

### Uninstalling

1. Go to *Plugins* in the WordPress dashboard.
2. Look for the plugin in the list.
3. Click on *Deactivate*.
4. Click on *Delete*.

Please note: by default the plugin data is kept after uninstall. You can choose to delete all data going to *Settings* -> *JAMP Notes* and enabling data removal on uninstall.

## Changelog

**1.3.0 [2020-08-15]**

- NEW: notes can now be added to the users! Go to *Settings* -> *JAMP Notes* to enable it.
- Fixed a problem preventing settings pages to be recognized as supported sections after settings save.
- Improved user permissions check in the settings page.

**1.2.0 [2020-07-13]**

- NEW: notes can now be added to the plugins!
- The Notes column has now a specific width to prevent random space usage.
- Showing a placeholder for missing note title in admin bar and columns.
- Showing a placeholder for missing titles when selecting items in the note editing page.
- Fixed a bug in the Location column when a note is attached to a post with no title.
- Fixed admin bar notes not showing the bold text style.
- A few changes for performance improvements.
- Tested on WordPress 5.5.

**1.1.0 [2020-06-18]**

- Added global and section notes counters on the admin bar.
- Added validation to the Note Setting meta box.
- Added a meta box to view notes attached to a post while editing it.
- Improved content generation of Location column in the Notes page.

**1.0.1 [2020-06-10]**

- Managed the admin bar panel max height.
- Replaced the tooltip with a hidden section for the admin bar notes details. Click on the "I" icon to show it.
- Fixed an issue with long titles in admin bar notes.
- Fixed an issue in the admin menu names parsing.
- Fixed an issue with the tooltip not showing correctly in the custom column in mobile view.
- Fixed metabox style in mobile view.
- Fixed settings page style in mobile view.

**1.0.0 [2020-06-03]**

- First release.

## License
[GPLv2 or later](http://www.gnu.org/licenses/gpl-2.0.html)