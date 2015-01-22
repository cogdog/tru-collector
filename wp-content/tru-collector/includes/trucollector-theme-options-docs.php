<h4>Setting Up a New Collector site</h4>

<p>(1) Create a fresh Wordpress site. </p>

<p>(2) Create a User with login name of "collector" with Author role. No one actually uses the account, the site invisible logs in guests as this user. Make note of the password. Make it cryptic.</p>

<p>** SUPER IMPORTANT ** For the account to log a visitor in, you must edit the file /includes/misc.php to match the password of this account</p>

<p><em>Yes, I know this is ugly. There's not much this account can do, and one day I may find the way to store this more securely. Or you might fork this code, and show me how it can be done</em></p>

<p>(3) Install the <a href="https://wordpress.org/themes/fukawasa">Fukawasa theme</a>.</p>

<p>(4) Install the TRU Collector theme downloaded as part of this repo; either by uploading to your wp-content/themes directory or making  ZIP if just the tru-collector contents and uploading as a theme.</p>

<p>(5) Activate TRU Collector as the site's theme. In this theme, Posts are renamed <strong>collectables</strong> and thus on the dashboard:</p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/collectables.jpg" alt="Renamed Posts Menu" title="collectables menu"></p>

<p>(6) Install and activate the <a href="https://wordpress.org/plugins/remove-dashboard-access-for-non-admins/">Remove Dashboard Access plugin</a>.   In its settings, restrict access to <strong>Editors and Administrators</strong> or just <strong>Administrators</strong> depending if you will have a moderator user. The default redirect should be for the main URL of the site.</p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/dashboard-access-settings.jpg" alt="Remove Dashboard Access settings" title="Remove Dashboard Access settings"></p>

<p>(7) Set the TRU Collector Options (link is on Admin menubard and under Appearances in dashboard). 
Create a user account with Author capability. For now, you have to edit  <strong>/includes/misc.php)</strong> to provide credentials for the auto login.</p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/collector-options-1.jpg" alt="Options Screen" title="options"></p>

<p>Leave the access code blank to allow complete open access. Otherwise, enter a code that users must present to see the upload tools. Add a hint that will help in case someone gets the access code wrong. </p>

<p>Other settings should be easy to figure out. </p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/collector-options-2.jpg" alt="Options Screen 2" title="more options"></p>

<p>The remaining options allow you to designate who gets an email notification of new entries. The bottom line will provide an indication to show if the author account is correctly set up.</p>

<p>(8) The theme creates three Pages which custom templates. You can edit their content to provide additional information:</p>

<ul class="task-list">
<li>
<strong>Desk</strong> The screen where users must enter an access code</li>
<li>
<strong>Collect</strong> The page that includes the form people use to upload content</li>
<li>
<strong>Random</strong> No content needed, it just performs a redirect to a random collectable</li>
</ul>

<p>(9) Custom Menus as needed. Suggestion:</p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/menu.jpg" alt="Menu Configuration" title="menu options"></p>

<p>(10) Configure Widgets. Suggestion:</p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/widgets.jpg" alt="Widgets" title="suggested widget set up"></p>
