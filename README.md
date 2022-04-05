# elemendas-addons
Widgets and Extensions to improve Elementor

## Description

This plugin is an addon for Elementor Pro. It adds the __Search Results Title__ and the __Search Results Highlight__ widget to the Search Results Archive. This widgets allows you to show the number of posts containing the search query, and to highlight the search query string within the results.

You can customize the message according to the number of results obtained:

* For no result,
* for a single result and
* for more than one result.

You can choose many alternatives to highlight the search string:
* Quotation marks
* Highlighter
* Underline
* and the typical color and typography controls

## Requirements
* WordPress 5.0, tested up to 5.9

## Installation

### WordPress Plugins Dashboard

1. From your WordPress dashboard -> Go to __Plugins -> __Add New__ screen.
2. In the __Search plugins...__ field, enter "Elemendas Addons" and choose it.
3. Press __Install Now__.
4. After installation, click __Activate__.

Check out the WordPress.org site for more information about [automatic plugin installation](https://wordpress.org/support/article/managing-plugins/#automatic-plugin-installation-1).

### Upload

1. Download the latest tagged archive (choose the "zip" option).
2. Go to the __Plugins -> Add New__ screen and click the __Upload__ tab.
3. Upload the zipped archive directly.
4. Go to the Plugins screen and click __Activate__.

Check out the WordPress.org site for more information about [upload plugins](https://wordpress.org/support/article/managing-plugins/#upload-via-wordpress-admin).

### Manual

1. Download the latest tagged archive (choose the "zip" option).
2. Unzip the archive.
3. Copy the folder to your `/wp-content/plugins/` directory.
4. Go to the Plugins screen and click __Activate__.

Check out the WordPress.org site for more information about [installing plugins manually](https://wordpress.org/support/article/managing-plugins/#manual-plugin-installation-1).

### Git

Using git, browse to your `/wp-content/plugins/` directory and clone this repository:

`git clone git@github.com:sanbec/elemendas-addons.git`

Then go to your Plugins screen and click __Activate__.

## Screenshots

## Frequently Asked Questions

### How do I use the Search Results Widgets?

1. Just create a search results archive at the Elementor's theme builder.
2. Go to Preview Settings.
3. Set __Preview Dynamic Content as__ "__Search Results__" and fill the __Search Term__.
4. Adjust the __Display Conditions__ to __Search Results__.
5. Drag and drop the __Search Results Title__ widget to customize the title adding the results number and highlighting the search string.
6. Drag and drop the __Search Results Highlight__ widget to highlight the search string within the search results.

Relax! If you don't follow the steps correctly, you will get a note with instructions in the editor when you add the widget.

__Remember__: these widgets will only appear in archive templates.

## Credits

* Built by [Santiago Becerra](https://elemendas.com/)

## Changelog

### 2.0.0
* New "Search Results Highlight" widget to highlight the search string within the search results
* Added underline control for search terms highlight
* Added text style control for search terms highlight

### 1.3.1
* Fix color label text

### 1.3.0
* Added highlighter control for search terms highlight

### 1.2.0
* Added background color control for search terms highlight

### 1.0.1
* Minor changes to improve i10n

### 1.0.0
* Initial release on WordPress.org
