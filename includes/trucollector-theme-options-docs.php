  <style>
      code{white-space: pre-wrap;}
      span.smallcaps{font-variant: small-caps;}
      span.underline{text-decoration: underline;}
      div.column{display: inline-block; vertical-align: top; width: 50%;}
      img {max-width:90%; }
  </style>



<p><em>For complete setup documentation that includes suggestions for setup, plugins, <a href="https://github.com/cogdog/tru-collector"  target="_blank">see the theme repository on GitHub</a>. That is also <a href="https://github.com/cogdog/tru-collector/issues"  target="_blank">a good place to ask question or toss accolades</a>.</em></p>


<h2 id="setting-up-a-new-collector-site">Setting Up a New Collector site</h2>
<p><em>In this theme Wordpress <code>Posts</code> are renamed <code>Collectables</code> but have all the attributes of garden variety blog posts.</em></p>
<ol type="1">
<li><p>Create a fresh new Wordpress site.</p></li>
<li><p>Create a user with login name of “collector” with Author role. No one actually uses the account, the site invisible logs in guests as this user. Make note of and save somewhere the password that Wordpress generates. Leaving it cryptic is fine, no one needs to use it.</p></li>
<li><p>Install the <a href="https://wordpress.org/themes/fukasawa">Fukasawa theme</a> from the Wordpress Dashboard (I’ll gamble that you know how to install themes, search on <code>Fukasawa</code> from Appearances – Themes. It does not need to be activated, it just needs to be present.</p></li>
<li><p>Install the TRU Collector theme downloaded as part of this repo (use the green <strong>clone or download</strong> button above or <a href="https://github.com/cogdog/tru-collector/archive/master.zip">download directly</a>).</p></li>
<li><p>Activate TRU Collector as the site’s theme. In this theme, Posts are renamed <strong>collectables</strong> and thus on the dashboard:</p></li>
</ol>
<figure>
<img src="<?php echo get_stylesheet_directory_uri()?>/images/collectables.jpg" title="collectables menu" alt="" /><figcaption>Renamed Posts Menu</figcaption>
</figure>
<ol start="6" type="1">
<li>Install and activate the <a href="https://wordpress.org/plugins/remove-dashboard-access-for-non-admins/">Remove Dashboard Access plugin</a>. The tru-collector theme’s options panel will nudge you to install it. This plugin is used to keep the logged in <code>collector</code> user from seeing the Wordpress dashboard. Any attempt to reach the dashboard outside of Administrators or editors results in a redirect to the front of the site. The site will work without it, but it makes your site a tad more bullet proof.</li>
</ol>
<p>In this plugins settings, restrict access to <strong>Editors and Administrators</strong> or just <strong>Administrators</strong> depending if you will have a moderator user. The default redirect should be for the main URL of the site.</p>
<figure>
<img src="<?php echo get_stylesheet_directory_uri()?>/images/dashboard-access-settings.jpg" title="Remove Dashboard Access settings" alt="" /><figcaption>Remove Dashboard Access settings</figcaption>
</figure>
<ol start="7" type="1">
<li>You might want to set up in advance some Wordpress Categories for your Collectables; in the options you will choose one as default (and for the love of all that is holy, <em>change the name of the Uncategorized category</em>!</li>
</ol>
<h2 id="setting-up-the-site-via-tru-collector-options">Setting Up The Site Via TRU Collector options</h2>
<p>These instructions are a reference for the settings within the TRU Collector; if you are reading this, you got as far as installing and activating the theme. Below are basic instructions for all of the theme options, which, if this were designed well, you likely do not need me to explain, but #BecauseDocumentation here we go.</p>
<h3 id="access-to-collector">Access to Collector</h3>
<p><img src="<?php echo get_stylesheet_directory_uri()?>/images/access-code.jpg" /></p>
<p>Leave this field blank if you want any visitor to be able to access the submission form on your site (you can always make it less accessible by not having any links as menus for the form.</p>
<p>If you want to provide an access code (a very weak password), just enter it. Any requests to access to form will be sent to the <strong>Welcome Desk</strong> page where a visitor must enter in the code you have entered here to see the form.</p>
<p>Enter a decent <strong>Access Hint</strong> that will be displayed if someone does not enter the correct code.</p>
<h3 id="special-pages-setup">Special Pages Setup</h3>
<p><img src="<?php echo get_stylesheet_directory_uri()?>/images/special-pages.jpg" /></p>
<p>This theme has three pages that must be created; each is associated with a specific template that provide it’s functionality. Activating the theme <em>should</em> create these all for you when the theme is activated, but if not, create them as described below. You can edit the content of the pages to customize the prompt seen by writers on your site.</p>
<p>If the theme does not do so automatically (and it should) create these Wordpress <strong>Pages</strong>. You can modify the titles, and add whatever content you want to appear at the top as instructions. Unlike previous versions, there is no need for a specific url for the page.</p>
<ul>
<li><p><strong>Collect</strong> – The page that provides the collection form, see <a href="http://splot.ca/collector/collect">http://splot.ca/collector/collect</a>. Whatever you include in the body (not required) is added to the top of the form, maybe for extra instructions.e.g. for a site at <code>http://coolest.site.org/</code> the page can be published at <code>http://coolest.site.org/collecting</code> When you create a Collection Form page, under <strong>Page Atributes</strong>, select the Template named <code>Add to Collection</code>.</p></li>
<li><p><strong>Welcome Desk</strong> – The page that provides users will see first if they must enter an access code to access the writing tool (see below). If no access code is required, it redirects to the page above for the writing form– like <a href="http://splot.ca/collector/desk">http://splot.ca/collector/desk</a>. Whatever you include in the body (not required) is added to the top of the form, maybe for a friendly prompt .e.g. for a site at <code>http://coolest.site.org/</code> the page might be published at <code>http://coolest.site.org/guard_dog</code> When you create a Welcome Desk page, under <strong>Page Atributes</strong>, select the Template named <code>Welcome Desk</code>.</p></li>
<li><p><strong>Browse Items By License</strong> – If you ask visitors to select a reuse license, this page provides a way to see all items that share the same license– like <a href="http://splot.ca/collector/licensed/">http://splot.ca/collector/licensed/</a>. When you create a Browse By License page, under <strong>Page Atributes</strong>, select the Template named <code>Items by License</code>.</p></li>
</ul>
<p>In the theme options, make sure you have selected the Page that is designated for these purposes.</p>
<h3 id="publish-settings">Publish Settings</h3>
<p><img src="<?php echo get_stylesheet_directory_uri()?>/images/publish-settings.jpg" /></p>
<p>The Status for New Collectable lets you set up moderation for new submissions (by choosing <code>Set to draft</code>) whereas <code>Publish immediately</code> provides instant gratification to your visitors though leaves open the problems of a site where anyone can publish (the latter option thus works for sites where you set up an <strong>Access Code</strong> as described above).</p>
<p>Enter any email addresses who should be notified of new submissions; you can use multiple ones if you separate them by a comma.</p>
<h3 id="sort-options">Sort Options</h3>
<p>The default settings are for typical blogs where newest content is displayed first. The sort options allow you to change that across the site- the home page, category/tag archives, and search results.</p>
<p><img src="<?php echo get_stylesheet_directory_uri()?>/images/index-sorting.jpg" /></p>
<p><strong>Date Published</strong> is the default option, the order is <strong>Descending</strong>, or newest first, change to <strong>Ascending</strong> to have oldest items appear first.</p>
<p>Change the sort otder to <strong>Title</strong> to… yes… sort items alphabetically by each item’s title. Use <strong>Ascending</strong> for alphabetical order (A-Z) or <strong>Descending</strong> to reverse (Z-A).</p>
<h3 id="fields-and-options-for-items">Fields and Options for Items</h3>
<p><img src="<?php echo get_stylesheet_directory_uri()?>/images/description-options.jpg" /></p>
<p>Set the description options to choose whether provide a field for visitors to enter a caption/description for their shared item, and whether to require that something be entered. By setting to <strong>No</strong> this will not appear on the submission form or will any descriptions be displayed..</p>
<p>Next is the option to enable a rich text editor in place of the default simple text area input. Use this if you want a site where people create formatted blog=post like content or plain text captions.</p>
<p>You can also populate the editor (other type) with default content, for example, if you wanted descriptions to have certain headings.</p>
<p><img src="<?php echo get_stylesheet_directory_uri()?>/images/items1.jpg" /></p>
<p>Enabling <strong>Display Name of Person Sharing</strong> provides the input field to the form, and enables the display of its value on a single view.</p>
<p>The <strong>Source</strong> field is a single line text entry where a visitor can type in the source of the image (if it includes a URL it will be automatically hyperlinked when the image page is displayed).</p>
<p>Enabling <strong>Show URL for media item</strong> enables the display of a link for the uploaded media on a single view.</p>
<p><img src="<?php echo get_stylesheet_directory_uri()?>/images/items2.jpg" /></p>
<p>The <strong>Rights License</strong> is a drop down menu offering a variety of Creative Commons licenses as well as <code>All Rights Reserved</code> (boo!), and a <code>Usage Rights Unknown</code> choice.</p>
<p>Enabling the <strong>Cut and Paste Attribution</strong> adds to the image display a field with an automatically formed attribution statement (based on the image’s rights license choice) that can be copied after clicking on the text.</p>
<p>Check <strong>Enable Comments on Items</strong> to add a standard blog comment field at the bottom of all published items.</p>
<p>If you want users to choose categories, enable it on the form (setting to “no” will hide categories on the view page).</p>
<p>If you have not set up any categories, the <strong>Default Category for New Collectables</strong> menu just give you a choice of <code>Uncategorized</code>. If you want to modify this setting, first save your options, edit your <strong>Collectable Categories</strong> (standard Wordpress Categories accessed under Collectables in the Dashboard), and return here to set up the desired default category. Please, don’t leave <code>Uncategorized</code> on your site!</p>
<p>If you want users to enter tags, enable it on the form (setting to “no” will hide tags on the view page).</p>
<p>The last option here creates field on the input form for users to send information that is not displayed.</p>
<h3 id="email-options">Email Options</h3>
<p><img src="<?php echo get_stylesheet_directory_uri()?>/images/email-options.jpg" /></p>
<p>Activating the email form field creates a means for visitors who wish to edit their entry later to get a special edit link sent via email. This feature can be disabled; enabled as optional, or as a required entry.</p>
<p>If you use this option, you can also enter a specific domain (or a comma separated list) to say require email addresses to be official work or school ones.</p>
<p>The third setting here can activate a checkbox on the entry form that let’s a visitor choose to receive comment notifications via email.</p>
<h3 id="author-account-setup">Author Account Setup</h3>
<p><img src="<?php echo get_stylesheet_directory_uri()?>/images/author-account-none.jpg" /></p>
<p>To provide access to the media uploader, this site uses a Wordpress Authoring Role account that is logged into invisibly to your site. So your site needs a user account with a name of <strong>collector</strong> and a role of <strong>Author</strong>. If this site is on a mulitsite Wordpress install, and the TRU Collector has been used on another site, the <code>collector</code> account already exists, so you need to add it to the site via the Author tools.</p>
<p><img src="<?php echo get_stylesheet_directory_uri()?>/images/add-author.jpg" /></p>
<p>You can follow the link to create an account; for an email you can use a fictitious one on your domain. We suggest using the strong password that Wordpress suggests.</p>
<p>** Note: As of version 1.1 of TRU Collector you no longer need to copy the password into the options page. ** If you are using a version of the theme that has a password field in the options, maybe it’s a good time to update? Otherwise, <a href="https://github.com/cogdog/tru-collector/blob/a8637ef4739a6aa64210fee5ddffe8426cfa62b3/README.md#author-account-setup">see an older version of this ReadMe</a> for instructions.</p>
<h2 id="customize-the-add-collect-form">Customize the Add / Collect form</h2>
<p>You can now customize the field labels and the descriptions of the form where people submit new items to a TRU Collector site. On your site navigate to the collect form, and activate the Wordpress Customizer from the admin bar.</p>
<p>There is a special section TRU Collector tab to open:</p>
<p><img src="<?php echo get_stylesheet_directory_uri()?>/images/customizer-tab.jpg" /></p>
<p>Then from this pane, open “Collect Form”</p>
<p><img src="<?php echo get_stylesheet_directory_uri()?>/images/customizer-collect-tab.jpg" /></p>
<p>And then you will see a series of fields to edit for all form field elements. For each, you can edit the title/label of the field and the prompt that appears below. As you type in the customizer fields on the left, you will see a live preview on the right (ignore the silly pizza references in the screenshot, the author was just hungry):</p>
<p><img src="<?php echo get_stylesheet_directory_uri()?>/images/customizer-edit-form.jpg" /></p>
<p>A new feature is a menu item under <strong>Customize</strong> that allows you to open the customizer with the current collection form displayed.</p>
<p><img src="<?php echo get_stylesheet_directory_uri()?>/images/customize-form.jpg" /></p>
<h2 id="customize-the-collection">Customize the collection</h2>
<p>A new section for the TRU Collector Customizer <strong>Collection Info</strong> allows you to specify the name for the kinds of things in your collection as it is displayed on the sidebar below the site name.</p>
<p><img src="<?php echo get_stylesheet_directory_uri()?>/images/customize-collection-info.jpg" /></p>
<h2 id="browse-by-licenses">Browse by Licenses</h2>
<p>The TRU Collector provides archives of content that have the same reuse license (if the feature is activated via the TRU Collector options). Just make sure there is a Wordpress Page that uses the template <code>Browse by license</code> – and the Page is selected in the theme options (see above). This page is created automatically on new sites, or simply by activating the theme again (activate the parent Fukasawa, then activate TRU Collector again).</p>
<p><img src="<?php echo get_stylesheet_directory_uri()?>/images/browse-licenses.jpg" /></p>
<p>This page (see <a href="http://splot.ca/collector/licensed/">example</a>) provides links to view all content with the licenses available on the collect form. The url including <code>licensed/cc-by</code> yields all items with a Creative Commons CC By Attribution license <a href="http://splot.ca/collector/licensed/cc-by">example</a>.</p>
<p>Use the page to find links to the ones you wish to use and add to your site’s menus or widgets. If the links do not work, you may have to go to <strong>Settings</strong> – <strong>Permalinks</strong> and just click save to regenerate the settings.</p>
<p><img src="<?php echo get_stylesheet_directory_uri()?>/images/license-shortcode.jpg" /></p>
<p>For extra flexibility there is a <code>[licensed]</code> shortcode that can be used in a widget or any page to display the same index list in a sidebar. By default, it lists only licenses used (e.g. it skips licenses with zero uses); to show all licenses, use the code <code>[licensed show="all"]</code>.</p>
<h2 id="updating-the-theme">Updating the Theme</h2>
<p>If you have ftp/sftp access to your site (or this can be done in a cpanel file manager), simply upload the new theme files to the <code>wp-content/themes</code> directory that includes the older version theme.</p>
<p>For those that lack direct file upload access or maybe that idea sends shivers down the spine, upload and activate the <a href="https://wordpress.org/plugins/easy-theme-and-plugin-upgrades/">Easy Theme and Plugin Upgrades</a> plugin – this will allow you to upload a newer version of a theme as a ZIP archive, the same way you add a theme by uploading.</p>
<h2 id="shortcode-adding-hyperlinks-to-simple-captions">Shortcode Adding Hyperlinks to Simple Captions</h2>
<p>Any HTML put into the plain text editor for the caption is stripped out. But a new feature allows hyperlinks to be created using a “shortcode” format:</p>
<p><img src="<?php echo get_stylesheet_directory_uri()?>/images/shortcode-link.jpg" /></p>
<p>Links show be entered as a <code>[link]</code> shortcode where the link <code>url</code> is specified as well as the <code>text</code> to be used as hypertext:</p>
<p><code>[link url="http://www.themostamazingwebsiteontheinternet.com/" text="the coolest site on the internet"]</code></p>
<p>This will create a hyperlink like <a href="http://www.themostamazingwebsiteontheinternet.com/">the coolest site on the internet</a></p>
<p>To present the link just as a URL, simply use</p>
<p><code>[link url="http://www.themostamazingwebsiteontheinternet.com/"]</code></p>
<p>which will produce the hyperlink like http://www.themostamazingwebsiteontheinternet.com/ – all links will open in a new window.</p>
<h2 id="add-public-ratings">Add Public Ratings</h2>
<p>Installing the <a href="https://wordpress.org/plugins/wp-postratings/">WP-Ratings Plugin</a> enables public star (or other scale) ratings on items from the front page, archives, and single items. Visitors can rate content using a variety of ratings (1-5 stars, thumbs up/down, etc).</p>
<p><img src="<?php echo get_stylesheet_directory_uri()?>/images/with-ratings.jpg" /></p>
<p>See it in action on the SPLOT demo site <a href="http://splot.ca/collector/">front page</a> or <a href="http://splot.ca/collector/393/">single item</a>.</p>
<h2 id="tiled-displays">Tiled Displays</h2>
<p>Install the <a href="https://wordpress.org/plugins/wp-tiles/">WP-Tiles plugin</a> to create alternative views of your collections as a tiled gallery. The plugin provides way to create a variety of grid styles, and can be added to a Page in your site using a shortcode. This can even be set to be the FRONT page of your site using <strong>Settings</strong> -&gt; <strong>Reading</strong> in your WordPress dashboard as was down for the <a href="https://www.conf.owlteh.org/photos/">#OWLTEH18 Conference Photos site</a>:</p>
<p><img src="<?php echo get_stylesheet_directory_uri()?>/images/mural-owlteh18.jpg" /></p>
<p>Or see examples as internal pages from the <a href="http://splot.ca/collector/mural/">SPLOT demo site</a> or the <a href="https://muraludg.org/acumulador/mural/">Mural UDG project Accumulador site</a>.</p>
<p>Learn more about using this plugin https://cogdogblog.com/2018/01/tiling-splots/</p>
<h2 id="fix-rotated-mobile-phone-photos">Fix Rotated Mobile Phone Photos</h2>
<p>If contributors to your collection will be uploading photos directly from a smart phone, install the <a href="https://wordpress.org/plugins/ios-images-fixer/">iOS Image Fixer plugin</a> to fix <a href="https://wordpress.org/plugins/ios-images-fixer/">problems associated with portrait mode photos that end up uploaded as sideways images</a>.</p>
<h2 id="other-wordpressy-things-you-might-want-to-do">Other Wordpressy Things You Might Want to Do</h2>
<p>I like short links, so I typically use a Custom Permalink setting (under <code>Settings -- Permalinks</code>) of `/%post_id%/’</p>
<figure>
<img src="<?php echo get_stylesheet_directory_uri()?>/images/permalink.jpg" title="custom permalink" alt="" /><figcaption>Simplest Permalink</figcaption>
</figure>
<p>The theme creates a special URL <code>/random</code> to provide a link that shows a random item, e.g. http://splot.ca/collector/random</p>
<p>Set up your own menus as needed; make sure that you click the <code>Location</code> tab to tell Wordpress to use the menu you create.</p>
<figure>
<img src="<?php echo get_stylesheet_directory_uri()?>/images/menu.jpg" title="menu options" alt="" /><figcaption>Menu Configuration</figcaption>
</figure>
<p>Get rid of the default widgets on the site; make it your own</p>
<figure>
<img src="<?php echo get_stylesheet_directory_uri()?>/images/widgets.jpg" title="suggested widget set up" alt="" /><figcaption>Widgets</figcaption>
</figure>
<p>Go collect stuff!</p>
