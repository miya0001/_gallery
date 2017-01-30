=== Masonry Gallery ===
Contributors: miyauchi
Tags: gallery, masonry
Requires at least: 3.7
Tested up to: 4.7
Stable tag: 0.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a Masonry layout to the WordPress Gallery.

== Description ==

This plugin replaces WordPress `[gallery]` shortcode to masonry layout.

* CSS only, no JS.
* Image only, no caption.

So it is very simple.

If you want to change number of columns for mobile or tablet. Please put styles like following in your theme.

`
@media screen and ( max-width: 768px )
{
	.masonry-gallery
	{
		column-count: 3 !important;
	}
}
`

== Installation ==

* Go to the plugins administration screen in your WordPress admin, click on Add New, search for "litty" and click on Install Now.

== Screenshots ==

1. Screenshot.

== Changelog ==

= 1.0 =
* Initial release.
