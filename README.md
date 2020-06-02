# JAMP Notes (Just Another Memo Plugin) - WordPress Plugin

**NOTE:** this is a development repository. Please download latest stable release from the WordPress.org directory: https://wordpress.org/plugins/jamp-notes/.

## Description

Using this plugin you can attach notes to some elements in the WordPress dashboard, such as:

- posts
- pages
- custom post types from other plugins (except the notes from this plugin)
- dashboard sections
- the whole dashboard

It can be helpfull if you manage a site with other people or just to take notes for yourself.

## Features

- manage notes like the standard posts by opening the Notes page from the admin menu.
- while editing a note, use the meta box on the right to set note properties.
- manage section and global notes from the admin bar.
- manage item notes (eg. posts and pages) from the custom column in the admin pages.
- get note details from the tooltip showing on the "Info" links or icons.
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

**1.0.0**
* First release.


## License
[GPLv2 or later](http://www.gnu.org/licenses/gpl-2.0.html)