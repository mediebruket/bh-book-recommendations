=== BH Book Recommendations ===
Contributors: hgmb
Tags: rss, deichman, library, bibliotek, hordaland, shortcode, widget, feed
Requires at least: 4.3
Tested up to: 5.1.1
Stable tag: 0.1.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides shortcode and widget for displaying book recommendations from RSS feed.

== Description ==

This plugin provides a simple shortcode and a widget which enables you to display book recommendations from RSS feeds on your website. So it's basically a very simple RSS plugin.

The plugin is written primarily to work with the book recommendations feed from the Norwegian [Deichman](https://anbefalinger.deichman.no/lag-lister) library.

The plugin uses the main Deichman RSS feed as default: `https://anbefalinger.deichman.no/`. But this can be changed by supplying a new URL as argument to the shortcode or added in the widget configuration.

The plugin is available in English, Norwegian bokmål and Norwegian nynorsk.

The development of this plugin is sponsored by [Hordaland Fylkeskommune](http://www.hfk.no/).

== Installation ==

Download to `wp-content/plugins/` and activate.

Insert into post or page with shortcode: `[bhbook]`. The shortcode uses the main Deichman RSS feed `https://anbefalinger.deichman.no/feed` as default.

Customize the results by creating a custom feed at `https://anbefalinger.deichman.no/lag-lister`. Copy the generated feed URL and use as argument to the shortcode like this: `[bhbook url=https://anbefalinger.deichman.no/feed?authors=http%3A%2F%2Fdata.deichman.no%2Fperson%2Fh10532400]`.

As per version 0.1 the plugin has two different modes of displaying the feed, namely *list* and *grid*, where the former is default.

In order to change display to grid, use the display argument in the shortcode, for example like this: `[bhbook display="grid"]`. Images can be turned off in shortcode: `[bhbook images="false"]`. Set number of reviews with `[bhbook number=4]`.

Additionally, there is also a widget available. Custom feed URL can be utilized as described above.

== Changelog ==

= 0.1.6 =
* Support for setting number of reviews in both widget and short code (`number=4` for instance) and setting for hiding images added to widget.

= 0.1.5 =
* Reduce size for images from Bokkilden and prevent images from Krydder from being displayed. Added support for disable images altogether in shortcode with `images=false` argument.

= 0.1.4 =
* Version number bump. No changes in this version.

= 0.1.3 =
* Use fixed image height in grid view and use stylesheet timestamp in query string for cache busting.

= 0.1.2 =
* Make plugin translatable.

= 0.1.1 =
* Change grid styling, add html truncation.

= 0.1 =
* Initial release.
