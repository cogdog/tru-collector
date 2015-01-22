# TRU Collector Wordpress Theme
by Alan Levine http://cogdog.info/ or http://cogdogblog.com/

## What is this?
This Wordpress Theme powers [TRU Collector](http://splot.ca/collector/) a site to allow collections of images where contributions can be made without any silly logins. This is done by using a generic author account that is silently activated. The user never sees any sign of the innards of wordpress.


## How to Install
I will make the big leap in that you have a self hosted Wordpress site and can install themes. The Comparator is a child theme based on [the free Fukawasa theme by Anders Noren](https://wordpress.org/themes/fukawasa) 

Very very crucial. Do not just use the zip of this repo as a theme upload. It will not work. If you are uploading in the wordpress admin, you will need to make separate zips of the two themes (tru-collector and fukawasa, the latter only if not installed via the Wordpress theme manager, and upload each.

In addition the site uses the [Remove Dashboard Access](https://wordpress.org/plugins/remove-dashboard-access-for-non-admins/) which can be installed directly in your site, but a copy is provide just for the sake of completedness. The theme will nudge you to install it. It is used to keep the logged in user from seeing the admin side of Wordpress.

Create a user account with Author capability. For now, you have to edit  **/includes/misc.php)** to provide credentials for the auto login.

Actual documentation will appear here at some indefinite time in the future. 
