=== A5 Recent Post Widget ===
Contributors: tepelstreel
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YGA57UKZQVP4A
Tags: sidebar, widget, post, newspaper, recent post, feature, spotlight, flexible
Requires at least: 2.7
Tested up to: 3.9.1
Stable tag: 2.4.1

With the A5 Recent Post Widget you can put your latest post in the focus and style it differently.

== Description ==

Yet another recent post widget. It shows the last post of your blog, where and how you want. You can define, on which pages of your site it shows and whether you want to show a post thumbnail or not. Decide, whether the links go to the post, to the attachment page or simply to a file. You can style the links in the plugins settings and each widget container differently. 

The plugin was tested up to WP 3.9 and should work with versions down to 2.7 but was never tested on those.

== Installation ==

1. Upload the `a5-recent-posts` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place and customize your widgets
4. Define the style of the links in the settings section

== Frequently Asked Questions ==

= I styled the widget container myself and i looks bad. What do I do? =

The styling of the widget requires some knowledge of css. If you are not familiar with that, try adding

`padding: 10px;
margin-bottom: 10px;`
 
to the style section.

= My widget should have rounded corners, how do I do that? =

Add something like

`-webkit-border-top-left-radius: 5px;
-webkit-border-top-right-radius: 5px;
-moz-border-radius-topleft: 5px;
-moz-border-radius-topright: 5px;
border-top-left-radius: 5px;
border-top-right-radius: 5px;`
 
= My widget should have a shadow, how do I do that? =

Add something like

`-moz-box-shadow: 10px 10px 5px #888888;
-webkit-box-shadow: 10px 10px 5px #888888;
box-shadow: 10px 10px 5px #888888;`
 
= I styled the links of the widget differently, but the changes don't show, what now? =

Most of the time you will have to use the styles like that:

'font-weight: bold !important;
color: #0000dd !important;'

Since the stylesheet of the theme will have highest priority, you will have to make your styles even more important in the hierarchy.

== Screenshots ==

1. The plugin's work on our testingsite
2. The widget's settings section
3. The plugin's settings section

== Changelog ==

= 2.4.1. =

* Added copressable DSS

= 2.4 =

* All 'Devided by Zero' errors should be eliminated

= 2.3 =

* Some Finetuning of the framework; more foolproof

= 2.2 =

* Framework adjusted, better recognition of images

= 2.1 =

* Features added, framework adjusted

= 2.0 =

* Release into the wild

= 1.0 =

* Initial release upon request

== Upgrade Notice ==

= 1.0 =

Initial release upon request

= 2.0 =

Realease into the wild

= 2.1 =

Features added, framework adjusted

= 2.2 =

Framework adjusted, better recognition of images

= 2.3 =

Some Finetuning of the framework; more foolproof

= 2.4 =

All 'Devided by Zero' errors should be eliminated

= 2.4.1. =

Added compressable DSS