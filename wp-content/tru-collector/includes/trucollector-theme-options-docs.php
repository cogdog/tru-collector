<p>These instructions are a reference for the settings within the TRU Collector; if you are reading this, you got as far as installing and activating the theme. Below are basic instructions for all of the theme options, which, if this were designed well, you likely do not need me to explain, but #BecauseDocumentation here we go.</p>

<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/collectables-menu.jpg" alt="" style="border:3px solid #000; margin-bottom:2em;" />

<p> In this theme Wordpress <code>Posts</code> are renamed <code>Collectables</code> but have all the attributes of garden variety blog posts.</p>

<h4>Access Code and Hint</h4>
<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/access-code.jpg" alt="" style="border:3px solid #000; margin-bottom:2em;" />

<p>Leave this field blank if you want any visitor to be able to access the <a href="<?php echo site_url(); ?>/collect">submission form on this site</a> (you can always make it less accessible by not having any links as menus for the form page. </p>

<p>If you want to provide an access code (a very weak password), just enter it. Any requests to access to form will be sent to the <a href="<?php echo site_url(); ?>/desk">front desk</a> form where a visitor must enter in the code you have entered here to see the form.</p>

<p>Enter a decent <strong>Access Hint</strong> that will be displayed if someone does not enter the correct code.</p>

<h4>Caption Fields</h4>
<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/caption-field.jpg" alt="" style="border:3px solid #000; margin-bottom:2em;" />

<p>Set this option to provide a field for visitors to enter a caption for their shared image, and whether to require that something be entered. By setting to <strong>No</strong> this will not appear on the submission form.</p>

<h4>Source, License, and Attribution</h4>
<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/source-rights.jpg" alt="" style="border:3px solid #000; margin-bottom:2em;" />

<p>The first two settings operate similarly the Caption field options above. The <strong>Source</strong> field is a single line text entry where a visitor can type in the source of the image (if it includes a URL it will be automatically hyperlinked when the image page is displayed).</p>

<p>The <strong>Rights License</strong> is a drop down menu offering a variety of Creative Commons licenses as well as <code>All Rights Reserved</code> (boo!) as well as <code>Usage Rights Unknown</code>.

<p>At this time, the only way to edit the licenses displayed (e.g. if you do not want certain ones) is (pathetically on the part of the programmer) to edit <code>functions.php</code> in the template directory. Look for the function <code>trucollector_get_licences</code> and comment out the lines containing license options to hide.</p>

<p>Enabling the <strong>Cut and Paste Attribution</strong> adds to the image display a field with an automatically formed attribution statement (based on the image's rights license choice) that can be copied after clicking on the text. Neat, eh?</p>


<h4>Publication Options</h4>
<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/source-rights.jpg" alt="" style="border:3px solid #000; margin-bottom:2em;" />

<p>The Status for New Collectable lets you set up moderation for new submissions (by choosing <code>Set to draft</code>) whereas <code>Publish immediately</code> provides instant gratification to your visitors though leaves open the problems of a site where anyone can publish (the latter option thus works for sites where you set up an <strong>Access Code</strong> as described above.</p>

<p>Check <strong>Enable Comments on Items</strong>  to add a standard blog comment field at the bottom of all published photos.</p>

<p>If you have not set up any categories, the <strong>Default Category for New Collectables</strong> menu will not do much. You might want to save your options, and edit your <a href="<?php echo admin_url( 'edit-tags.php?taxonomy=category')?>">Collectable Categories</a> (Standard Wordpress Categories). Hierarchal ones are not supported (e.g. don't waste your time, use a flat Category structure)</p>


<h4>Notification Emails</h4>
<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/notification.jpg" alt="" style="border:3px solid #000; margin-bottom:2em;" />
<p>Enter any email addresses who should be notified of new submissions; you can use multiple ones if you separate them by a comma.</p>




<h4>Author Account Setup</h4>
<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/author-account-none.jpg" alt="" style="border:3px solid #000; margin-bottom:2em;" />

<p>To provide access to the media uploader, this site uses a Wordpress Authoring Role account that is logged into invisibly to your site visitors (for anyone logged in with an Editor or Administrator account, like you this account is not used).. So your site needs an active user with a name of <strong>collector</strong> and a role of <strong>Author</strong>.</p>

<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/add-author.jpg" alt="" style="border:3px solid #000; margin-bottom:2em;" />

<p>You can follow the link to create an account; for an email you can use a fictitious one on your domain. We suggest using the strong password that Wordpress  suggests. Copy that password, and perhaps save it in a safe place. On a stand-alone Wordpress install of the Collector, you can just paste it into the option for the password field.</p>

<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/add-to-site.jpg" alt="" style="border:3px solid #000; margin-bottom:2em;" />

<p>If this site is on a mulitsite Wordpress install, and the TRU Collector has been used on another site, the <code>collector</code> account already exists, so you need to add it to the site via the Author tools. However, you still have to enter the password, so make sure you know the passord that was used on another site. If you do not have access to it, you will have to reset the password at the Network Admin level, and then update the password on the options of all sites using the TRU Collector.</p>

<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/authoring-account.jpg" alt="" style="border:3px solid #000; margin-bottom:2em;" />

<p>When everything is set up correctly, the options will indicate that you have been a good options configurator! </p>

<p>If the account is incorrectly set up, when trying the Collector on a site where you are <em>not</em> logged in as an Administrator or Editor, any attempts to upload an image will generate an error message in the Media Uploader.</p>


<h4>JetPack Post by Email (optional option)</h4>
<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/jetpack-not-installed.jpg" alt="" style="border:3px solid #000; margin-bottom:2em;" />

<p>As an option you can enable a feature that allows people to add an image to your site simply by sending it via email. This uses the Post By Email module that is part of the <a href="http://jetpack.me/" target="_blank">Wordpress Jetpack plugin</a>. The options will check that the plugin is installed and that the module is enabled.</p>

<p>The subject line of the email becomes the title, the body of the email the content, and the first image attached becomes the Collectable (we suggest using a plugin such as <a href="https://wordpress.org/plugins/auto-thumbnailer/" target="_blank">Auto Thumbailer</a> that converts the first image to the theme's featured image.</p>

<p>To create an email address that can post via this plugin, any authenticated user can generate the <code>*********@post.wordpress.com</code> address via their profile; you cannot add it to the Collector user as you can only generate it for an account you are logged in to Wordpress with.</p>

<p>The field here is just to keep the address as a reference; it is not used anywhere in the site-- you most likely do not want to have this email address in a public space as it allows direct posting to the site.</p>

<p>We (well I, the person writing this) recommend creating an email forwarding address to use as the one that you share; for example, if the email address I set up as a forwarder was <code>quickpost@splot.ca</code> and made to forward to <code>*********@post.wordpress.com</code> if I need to change the address, I can just do so at the Wordpress level, update my forwarder, and never have to tell people a new address to use.</p>

<p>Also, your site looks a tad more credible without use of a <code>wordpress.com</code> email address (no offense, Wordpress, we love ya).



