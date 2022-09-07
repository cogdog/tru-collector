# TRU Collector Wordpress Theme
by Alan Levine http://cogdog.info/ or http://cogdogblog.com/

-----
*If this kind of stuff has any value to you, please consider supporting me so I can do more!*

[![Support me on Patreon](http://cogdog.github.io/images/badge-patreon.png)](https://patreon.com/cogdog) [![Support me on via PayPal](http://cogdog.github.io/images/badge-paypal.png)](https://paypal.me/cogdog)

----- 


![Sample Collectables Site](images/collector-site.jpg "Sample Collectables Site")

## What is this?

This Wordpress Theme powers [TRU Collector](http://splot.ca/collector/) a site to allow collections of items (termed "collectables") where contributions can be made without any  logins. The user never sees any sign of the innards of Wordpress but can create posts for each collectable. 

The options allow you to create a simple Upload and go mode, but you can also allow (and require or not), captions, a source description (maybe more than *hey, I found it on GOOGLE*) or a complete rich text editor, and choose a selection of licenses to apply.

*Why TRU?* I developed these initially [while on a fellowship](http://cogdog.trubox.ca) at [Thompson Rivers University](http://tru.ca/) as one of a suite of [SPLOT tools](http://splot.ca/splots/).

If you have problems, feature suggestions, questions piles of unmarked bills to send my way, please [contact me via the discussions area](https://github.com/cogdog/tru-collector/discussions/) on this repo.
For more info about TRU Collector, click away

* [TRU Collector](https://splot.ca/splots/tru-collector/) the original home if there is one (splot.ca)
* [Overly detailed Blog Posts About TRU Collector](https://splot.ca/splots/tru-collector/) (cogdogblog.com)
* [Talk About TRU Collector](https://github.com/cogdog/tru-collector/discussions) (Github Discussions)

See below a long list of example sites to hopefully inspire you. You will also find the steps and options for installing TRU Collector and reading it's lively documentation.


## So You are Interested in Making a TRU Collector Site?

The next section includes several options and instructions for installing the theme. Once set up, see the documentation of the theme options and other WordPress features. This is [now separated as it's own link here](https://github.com/cogdog/tru-collector/blob/master/docs.md) but can also be viewed in a more readable format - [see the Docs!](https://docsify-this.net/?basePath=https://raw.githubusercontent.com/cogdog/tru-collector/master&homepage=docs.md&sidebar=true#/) (thanks to [Docsify This](https://docsify-this.net/)).


## Installing TRU Collector

Using this theme requires a self-hosted--or institutionally hosted (lucky you)-- Wordpress site (the kind that you download from [wordpress.org](http://www.wordpress.org). You cannot use this theme on the free "wordpress.com" site unless you have a business plan. Maybe check out [Reclaim Hosting](https://reclaimhosting.com/) if you choose to set up your own hosting space. 

The TRU Collector is a child theme based on [the free and elegant Fukasawa theme by Anders Noren](https://wordpress.org/themes/fukasawa). Install this theme first from within the Wordpress Dashboard under **Appearance** -- **Themes** searching on `Fukasawa`.

### Installing TRU Collector from Scratch

(1) Create a fresh new Wordpress site. 

(2) Install the [Fukasawa theme](https://wordpress.org/themes/fukasawa) from the Wordpress Dashboard (I'll gamble that you know how to install themes, search on `Fukasawa` from Appearances -- Themes. It does not need to be activated, it just needs to be present.

(3) Install the TRU Collector theme,

You can download a ZIP file of this theme via the green **Code*" button above (use the Download Zip option). 

The zip can be uploaded directly to your site via **Themes** in the Wordpress dashboard, then **Add Theme** and finally **Upload Theme**. If you run into size upload limits or just prefer going old school like me, unzip the package and ftp the entire folder into your `wp-content/themes` directory.

(4) Activate TRU Collector as the site's theme. 

As of WordPress version 5.5, themes uploaded as .ZIP files can now be updated the same way you installed it. Just download the newest version, and update it by going to **Themes** in the Wordpress dashboard, then **Add Theme** and finally **Upload Theme**. You will be asked to confirm updating the theme with the newer version.  

### Installing TRU Collector in One Click with WP Pusher (get automatic updates!)

To have your site stay up to date automatically, I recommend trying the [WP Pusher plugin](https://wppusher.com/) which makes it easier to install themes and plugins that are published in GitHub. It takes a few steps to set up, but it's the most direct way to get updates to the theme.

To use WP-Pusher you will need to have or create an account on [GitHub](https://github.com/) (free). Log in. 

Next [download WP Pusher plugin](https://wppusher.com/download) as a ZIP file. From the plugins area of your Wordpress dashboard, click the **Upload Plugin** button, select that zip file to upload, and activate the plugin.

Then click the **WP Pusher** option in your Wordpress Dashboard, and then click the **GitHub** tab. Next click the **Obtain a GitHub Token** button to get an authentication token. Copy the one that is generated, paste into the field for it, and finally, click **Save GitHub** Token.

Now you are ready to install TRU Collector! 

![](images/wp-pusher.jpg "WP Pusher Settings")

Look under **WP Pusher** for **Install Theme**. In the form that appears, under **Theme Repository**, enter `cogdog/tru-collector`. Also check the option for **Push-to-Deploy** (this will automatically update your site when the theme is updated) finally, click **Install Theme**.

Woah Neo?

Not only does this install the theme without any messy download/uploads, each time I update the theme on GitHub, your site will be automatically updated to the newest version.  

### Installing From Reclaim Hosting

If you are wise enough to host your web sites at [Reclaim Hosting](http://reclaimhosting.com/) you have the option of installing a fully functioning site with this theme ([a copy of the demo site](http://lab.cogdogblog.com/collector/)) including recommended plugins, configured settings and sample content, all done  in one click. *But wait there is more!* With this method of installing your site, future updates to the theme are automatically added to your site (though not as frequently as the WP Pusher method).

In your cpanel, under **Applications** go to **All Applications**. This theme is available listed under Fratured Applications; just install from there.

![](images/reclaim-featured.jpg "Reclaim Hosting Featured Applications")

*Note that unlike other WordPress installs, this one will not preserve your username/password, so be sure to save that information.* When it's done, log into your new site and start making it your own following the steps below.

## Inserting Demo Content

If you want a site that is not completely empty, after setting up with WP-Pusher or from scratch, you can import all the content set up on the [public demo site](https://lab.cogdogblog.com/collector). 

Install all content by [downloading the WordPress export for that site](https://github.com/cogdog/tru-collector/blob/master/data/tru-collector.xml).  Running the WordPress Importer (under **Tools** -- **Import**) and upload that file when prompted.

You can also get a copy of the Widgets used on that site too. First install/activate the [Widget Importer & Exporter plugin](https://wordpress.org/plugins/widget-importer-exporter/). Download the [Collector Widgets data file](https://github.com/cogdog/tru-collector/blob/master/data/tru-collector-widgets.wie). Look under the **Tools** menu for **[Widget Importer & Exporter** and use the Import Widgets section to upload the data file. Boom! You got my widgets.



## See It In Action

* [Ada Lovelace Day](https://thinking.is.ed.ac.uk/ald2017/) 
* [Animal Body Plans](http://bio2290.trubox.ca/) Biology 2290 at TRU
* [Bavaradio](https://bavaradio.com/)
* [Bingobones](http://bingobones.com/) Skulls and their Stories from some fun at the Open Education 2018 project
* [Brand Storytelling](https://jmc3353.adamcroom.com/brandstorytelling/) Ad Copy Layout, University of Oklahoma
* [Cafecrema](https://cafecrema.procaffination.ca/) Cat pix
* [Canadian Undergraduate Research Network – Events and Opportunities](https://curnopportunitiesandevents.trubox.ca/)
* [The Compendium of Bothersome Beasties](http://creditcontinue.coventry.domains/beasties/) from Coventry University is a tool for self-reflection and formative assessment.
* [CogDogRoo Image Collector](https://cog.dog/roo/collector/) for Alan Levine's Nov 2017 Workshops in Victoria, Australia
* [Course (Re)design Institute ](https://cri.trubox.ca/) Thompson Rivers University
* [Coventry University Digital Leaders](http://digitalleaders.coventry.domains/who/)
* [Covid19 and LCC - Our Story In Images](https://livetogether.openlcc.net/covid19images/) Lansing Community Colleges
* [Cup of Tea With Ms E](https://cupofteawithmse.opened.ca/)
* [Digital Truth Making Conference](https://www2.hu-berlin.de/digitaltruthmaking/network/) used for participant intros, networking (password protected but [see tweeted screenshot](https://twitter.com/cogdog/status/1314619929006534657))
* [Do It Ourselves](https://dio.trubox.ca/) Meme images collected for GEOG 2221: Regional Geography of Canada (TRU)
* [DoOO Something](https://iwanna.dooosomething.org/) "Domain of One’s Own projects often start with domains/sites for individuals, but they can also include any other sites that individual or groups of staff and students might want the freedom to create for themselves."
* [EduHackathon](http://polito.eduhack.eu/)
* [Extraordinary Stories of Open and Online in the Covid-19 Era](https://splot.ca/extraordinary/)
* [Fanny Central](https://fannycentral.com/) for collecting inappropriately captioned old images. 
* [Femedtech Quilt of Care and Justice in Open Education](https://quilt.femedtech.net/) showcases quilt sections shared
* [Fleming Techbank](http://techbank.flemingdomains.ca/) Fleming College's Digital Learning Technologies Inventory
* [Flora and Fauna](https://florafauna.buffscreate.net/)
* [Fun Photos](https://camp2019.photos.learn4growth.com/)
* [Fungi Lab](https://fungilab.opened.ca/) for BISC 326 at SFU
* [GEOG 2221 Photo Collector](http://geog2221.trubox.ca/)  collecting images from participants in TRU’s GEOG 2221 Course: Regional Geography of Canada
* [Inclusive Design Un-Guide Reflection Bank](http://inclusivedesign.middcreate.net/reflect/)
* [Image Pool](http://imagepool.trubox.ca/) - used for TRU workshops on finding images on the web 
* [Imagery VISA 1101_01](http://imagery.trubox.ca/) collecting images from participants in TRU’s VISA 1110 Course: History of Art
* [Memes and Animated GIFs](https://memeworkshop.trubox.ca/) for a TRU workshop
* [Memes and GIFs Again](https://memeworkshop2.trubox.ca/)  second iteration of the TRU workshop
* [Mineral Books](https://mwynau.com/)
* [OER18 Photos](https://oer18.oerconf.org/media/) photos for April 2018 conference in Bristol, UK.
* [OER20 Who is Who](https://oer20.oerconf.org/socialbingo/)
* [Oh No Not the Followers of the Apocalypse](http://ohnonotthe.followersoftheapocalyp.se/) FOTA Images from David Kernohan
* [Online Resilience](https://onlineresilience.uni-med.net/) UNIMED collection of  member organization responses to COVID-19
* [OpemMed Album](http://oercollector.openmedproject.eu/) Open Images from OpenMed meetings
* [Online Ecosystem Maps](http://a202dmll.coventry.domains/maps/) a gallery of maps created by students enrolled in the A202DMLL Module (Develop your Online Presence & Own your Web Domain) at Coventry University.
* [Online Worlds](http://coventry.domains/online-worlds/) another Coventry activity for collecting images of how people see their online worlds. 
* [OWLTEH Catalogue](http://catalogue.owlteh.org/) Open Web For Learning & Teaching Expertise Hub -- a Catalogue of the Open Web: applications, platforms, techologies, or concepts
* [Learning on/with the Open Web Participants](https://conf.owlteh.org/participants/) 
* [Learning on/with the Open Web Conference Photos](https://www.conf.owlteh.org/photos/)
* [The Legend of Extend](https://play.learningnuggets.ca/cms) Terry Greene's card collection
* [LIB 100 Online](https://lib100.info/) Wake Forest University
* [Reclaim Roadshow Participants](https://roadshow.reclaimhosting.com/participants/)
* [Sommi Porta](https://somni.arganee.world/) am assignment collection for Networked Narratives
* [SPLOT Collector](http://splot.ca/collector/) - the development and very first site, stuff can and will break
* [Students Writing on Material Culture in China](https://hst137.tdh.bergbuilds.domains/projectfall20/) for HST 137 at Muhlenberg College
* [T3+BLENDLAC 2020](http://mariocandof.digital.brynmawr.edu/t3blendlac2020/) Conference participants
* [Tech Collect](https://techcollect.cbsinkinson.com/) CU Boulder
* [Templebreedy Archive](https://deochandoras.com/) Collecting tombstones!
* [Things That Make Me Smile](http://erikaab.ds.lib.uw.edu/smile/) Things great and small that brought joy to us in 2020.
* [This is Us!](https://www.socialbingo.coventry.domains/) people from Coventry University for Staff Conference & Excellence Awards 2020
* [UDG Agora Image Pool](http://udg.theagoraonline.net/imagepool) used by University of Guadalajara faculty and students sharing media in the UDG Agora Project
* [Virtual VSTE 2020](https://vste.org/vstevirtual2020/) Online Learning Virtual Showcase
* [VISA 1110 Gradating Studio](https://visa4910.trubox.ca/) from TRU course eVISA 1110 Course: History of Art
* [What's Happening?](http://whatshappeningart.com/) "An Experimental Journey of Artistic Inquiry"
* [Wicked Problems](https://wicked.liberatedlearner.ca/) student stories collected for the Liberated Learner Project
* [Wordbox](http://origin.coventry.domains/wordbox/) Coventry University Art and Design Skills Hub
* [Your Mineral Site](https://yourmineralsite.mineralcollective.com/)


### More TRU Collector Sightings

* Samantha Clarke presenting the way TRU Collector was used to build the [The Compendium of Bothersome Beasties](http://creditcontinue.coventry.domains/beasties/) at the ALTC 2018 Conference https://www.youtube.com/watch?v=e1f5PY-uREE

*  At Muhlenberg College Tineke D'Haeseleer <a href="https://tdh.bergbuilds.domains/pedagogy/splot/">published a guide to using SPLOTS and especially TRU Collector</a> for her history courses, but read to the bottom to learn how one our students put it to use for <a href="http://joliegirgis.bergbuilds.domains/cms/category/plants/">her project on plants used in traditional Chinese medicine</a>.

* [Summer Camp SPLOT](https://trubox.ca/splot/) a Thompson Rivers University 

### Experiment Lab

TRU Collector has a Custom REST API endpoint that is used to offer up random photos from a site. See [The SPLOT Image Truck is Here!](https://cogdogblog.com/2019/03/splot-truck/) and these demo sites

* [Inspire Me with a Random SPLOT Image](https://cogdog.github.io/splotlab/randysplot/)
* [Glitch a Random SPLOT Image](https://cogdog.github.io/splotlab/glitchsplot/) (see [blog post](https://cogdogblog.com/2019/04/glitch-a-splot/))


## New Features

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

## With Thanks

SPLOTs have no venture capital backers, no IPOs, no real funding at all. But they have been helped along by a few groups worth recognizing with an icon and a link.

The original TRU Collector was developed under a [Thompson Rivers University Open Learning Fellowship](http://cogdog.trubox.ca/) and further development was supported in part by a [Reclaim Hosting Fellowship](http://reclaimhosting.com), an [OpenETC grant](https://opened.ca), Coventry University's [Disruptive Media Learning Lab](https://dmll.org.uk/)  plus ongoing support by [Patreon patrons](https://patreon.com/cogdog).

[![Thompson Rivers University](https://cogdog.github.io/images/tru.jpg)](https://tru.ca) [![Reclaim Hosting](https://cogdog.github.io/images/reclaim.jpg)](https://reclaimhosting.com) [![OpenETC](https://cogdog.github.io/images/openetc.jpg)](https://opened.ca) [![Disruptive Media Learning Lab](https://cogdog.github.io/images/dmll.jpg)](https://dmll.org.uk/)   [![Supporters on Patreon](https://cogdog.github.io/images/patreon.jpg)](https://patreon.com/cogdog) 


