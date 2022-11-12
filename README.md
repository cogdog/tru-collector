# TRU Collector Wordpress Theme
by Alan Levine http://cogdog.info/ or http://cogdogblog.com/

[![Wordpress version badge](https://img.shields.io/badge/version-32.45-green.svg)](https://github.com/cogdog/tru-collector/blob/master/style.css)
[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

:house: TRU Collector |
[:mag: Examples](examples.md) | 
[:rocket: Installing](install.md) | 
[:book: Documentation](docs.md) | 
[:speech_balloon: Discussions](https://github.com/cogdog/tru-collector/discussions)

This Wordpress Theme powers [TRU Collector](http://splot.ca/collector/) a site to allow collections of items (termed "collectables") where contributions can be made without any logins. The user never sees any sign of the innards of Wordpress but can create posts for each collectable. 

![Sample Collectables Site](images/collector-site.jpg "Sample Collectables Site")

The theme options allow you to create a simple upload and go mode, but you can also allow (and require or not)-- captions, a source description (maybe more than *hey, I found it on GOOGLE*) or via complete rich text editor, longer and srucured writing, and you can offer a selection of reuse licenses to apply.

*Why TRU?* I developed these initially [while on a fellowship](http://cogdog.trubox.ca) at [Thompson Rivers University](http://tru.ca/) as one of a suite of [SPLOT tools](http://splot.ca/splots/).

If you have problems, feature suggestions, questions piles of unmarked bills to send my way, please [contact me via the discussions area](https://github.com/cogdog/tru-collector/discussions/) on this repo.

For more info about TRU Collector, see

* [TRU Collector](https://splot.ca/splots/tru-collector/) the original home if there is one (splot.ca)
* [Overly detailed Blog Posts About TRU Collector](https://cogdogblog.com/tag/trucollector/) (cogdogblog.com)
* [Talk About TRU Collector](https://github.com/cogdog/tru-collector/discussions) (Github Discussions)


## So You are Interested a TRU Collector Site?

Awesome!

For inspiration I offer [a collection of other sites](examples.md) using this theme, then provide  [details on how to install it](install.md), and once set up, the [documentation](docs.md) for customizing it in WordPress. The same documentation is available in the theme options and also in a more readable format - [see the Docs!](https://docsify-this.net/?basePath=https://raw.githubusercontent.com/cogdog/tru-collector/master&homepage=docs.md&sidebar=true#/) (thanks to [Docsify This](https://docsify-this.net/)).

## With Thanks

SPLOTs have no venture capital backers, no IPOs, no real funding at all. But they have been helped along by a few groups worth recognizing with an icon and a link.

The original TRU Collector was developed under a [Thompson Rivers University Open Learning Fellowship](http://cogdog.trubox.ca/) and further development was supported in part by a [Reclaim Hosting Fellowship](http://reclaimhosting.com), an [OpenETC grant](https://opened.ca), Coventry University's [Disruptive Media Learning Lab](https://dmll.org.uk/)  plus ongoing support by [Patreon patrons](https://patreon.com/cogdog).

[![Thompson Rivers University](https://cogdog.github.io/images/tru.jpg)](https://tru.ca) [![Reclaim Hosting](https://cogdog.github.io/images/reclaim.jpg)](https://reclaimhosting.com) [![OpenETC](https://cogdog.github.io/images/openetc.jpg)](https://opened.ca) [![Disruptive Media Learning Lab](https://cogdog.github.io/images/dmll.jpg)](https://dmll.org.uk/)   [![Supporters on Patreon](https://cogdog.github.io/images/patreon.jpg)](https://patreon.com/cogdog) 

*If this kind of stuff has any value to you, please consider supporting me so I can do more!*

[![Support me on Patreon](http://cogdog.github.io/images/badge-patreon.png)](https://patreon.com/cogdog) [![Support me on via PayPal](http://cogdog.github.io/images/badge-paypal.png)](https://paypal.me/cogdog)----- 


## New Features

* **Collector Names** Updated dashboard view to replace WordPress author column with name of person contributing items, clickable to filter, plus public views by name also can be made into a link

* **Embedded Docs** Making use of Doscify This to include always [the most up to date documentation](https://github.com/cogdog/tru-collector/blob/master/docs.md) from inside the TRU Collector Options, see Documentation tab.
* **Sorting Options Expanded** New option to change default reverse chronological order of items to be everywhere, on home, in tags, in categories, or in tags AND categories.
* **Tag List shortcode** Use to display a list of all used tags for sites that use more than the 45 that can be shown in widget cloud
* **Admin Only Use of Tags/Categories** Theme options can be set to let tags and categories be set only by admins for internal organization, and not shown on writing form
* **Image Alt Displayed** alternative descriptions now displayed in the SPLOT meta data box below a single entry. Also, a new theme option where it can be set as a required item. And a Customizer option to change the label and prompt for the image alt text fields.
* **Tag Suggestions** Tags entry field provides autocomplete suggestions
* **Authorless** Removed need for the secret WordPress account, all contributions made without dashboard access. Removed "desk" template, no longer needed. New drop zone for uploading images, plus inline image uploader added to rich text editor. 
* **Customize Comments** Modify the title where comments are displayed and add an extra prompt. Also added fields to change the label e.g. "4 Comments" when displayed so that can be named whatever one likes.
* **Alt Text Field** Sharing form now includes a place to enter alternative text for the main image for better web accessibility. 
* **Default Description Content** And one more new theme option to pre-populate the description field with default content.
* **Comment Options**  The email entry form now has an option to require entry, as well as another one to allow content creator to choose to receive email notifications of comments.
* **Post Publishing Editing** Not enabled by default, but a new theme option can add to the collection form an email field (optional) that sends a user a special link that can be used to edit an entry later. This also adds a button to single item pages that can request the edit link.
**Options for Special Pages**  No longer are pages for the Welcome Desk (where access codes are entered) and Collector form required to have a set URL; you can create any Page desired for these functions, and set them as the active ones via the theme options.

[![SPLOT Previews](http://img.youtube.com/vi/6trhgkbt7hA/0.jpg)](https://www.youtube.com/watch?v=6trhgkbt7hA "SPLOT Previews")

* **Name the items** New customizer panel to create the name of the item/items in the collection displayed below the site name (replacing generic "item/items")
* **Better Rich Text Editor** the visual editor will now embed media WordPress supports (YouTube, twitter, soundcloud, etc)
* **Preview Option** on collect form allows reviewing content in overlay preview before submitting (embedded tweets do not render in preview, just a big space).
* **By Licenses Shortcode** useful for putting an indexed list of items by licenses into widget or any page.
* **New Login Code** removes the need to copy the collector user's password to the theme options. The special user account is closed after their item is uploaded. Also, there are new admin notices to activate or install the two recommended plugins
* **Enabled for WP-Ratings** With the Wp-Ratings Plugin installed, you can have front page and single item ratings for items
* **Customizer Editor for Upload Form** All field labels and instructions can now be modified via the customizer
* **Rich Text Editor Option** allow for the full Wordpress editor for composing captions
* **Shortcode for Hyperlinks in captions** This one is for you Daniel!
* **Display Order Options** Theme options now allows front page, archive, search results can be sorted by date published (default) or by title, and also the order can be ascending or descending
* **Options Refreshed** Recoded the theme options better organized and also so documentation now links to GitHub most recent documentation (what you are looking at!)

