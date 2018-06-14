=== Add Paragraphs Option to Text Widget ===
Contributors: fullworks
Tags: text widget
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=TRZZ9YQWLAG6N
Requires at least: 4.7
Tested up to: 4.8
Stable tag: 1.6
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html

Add Paragraphs Option to Text Widget in version 4.8 like it was before 4.8

== Description ==

WordPress 4.8  introduced the visual editor to the Text Widget, but in doing so remove the checkbox to add paragraphs.
Whilst this is backwardly compatible, as soon as you edit the text widget the paragraph tag is introduced. This can have disastrous impact to your design and break
scripts that have blank lines.

Also, the visual editor if used will remove code it thinks is invalid, including empty tags, which is also a bit problem.
By default this plugin also turns off the visual editor.

There is a setting added to settings>general that allows you to turn on the visual editor. You can also turn it back on by adding define ('VISUAL_TEXT_WIDGET',true); to your wp-config.php. Defining
VISUAL_TEXT_WIDGET to true or false remove the setting option from settings>general so you can hide the option.

If installed pre 4.8 the plugin will do nothing, not even a warning, so you can install this before a 4.8 upgrade and things should be better.
Of course upgrading is your responsibility and always take a backup.

Version: 1.6 only : Wordpress 4.8.1 ( currently in beta ) has introduced a legacy mode that makes this plugin redundant, so if the WordPress version is greater than 4.8 the plugin does nothing and is silent. You may safely remove it if you are on 4.8.1 or above.




== Installation ==

**Through Dashboard**

1. Log in to your WordPress admin panel and go to Plugins -> Add New
1. Type widget for Add Paragraphs Option to Text Widget in the search box and click on search button.
1. Find Widget for Add Paragraphs Option to Text Widget plugin.
1. Then click on Install Now after that activate the plugin.
1. That is it, next time you open a Text Widget in admin you should see no visual editor and see the add paragraphs check box
1. If the Visual Editor is required either go to Settings>General and tick the setting or add define ('VISUAL_TEXT_WIDGET',true); to your wp-config.php

**Installing Via FTP**

1. Download the plugin to your hardisk.
2. Unzip.
3. Upload the **add-paragraphs** folder into your plugins directory.
4. Log in to your WordPress admin panel and click the Plugins menu.
5. Then activate the plugin.
6. That is it, next time you open a Text Widget in admin you should see no visual editor and see the add paragraphs check box
7. If the Visual Editor is required either go to Settings>General and tick the setting or add define ('VISUAL_TEXT_WIDGET',true); to your wp-config.php

== Frequently Asked Questions ==

= Will it remember settings prior to 4.8? =
Yes, the change by WordPress is backwardly compatible and remembers the prior setting, this plugin just re-introduces the checkbox.

= Can I install it before upgrading to 4.8 =
Yes.

= How do I get the visual editor =
Either go to Settings>General  and find the option or
add this line to your wp-config.php
define ('VISUAL_TEXT_WIDGET',true);
before this line
/* That's all, stop editing! Happy blogging. */

Adding either define ('VISUAL_TEXT_WIDGET',true); or define ('VISUAL_TEXT_WIDGET',false); will hide the Settings>General option

== Thank the developer==
Did this plugin get you out of trouble?
Please consider [making a small donation](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=TRZZ9YQWLAG6N) to thank the developer for the the time he has saved you.

Hall of fame
* 28 June 2017   - STW - $25
* 4 July 2017    - AS - $1
* 17 July 2017   - M11 - $20

Total thanks to date  $46

== Changelog ==

= 1.6 =
* set to do nothing for WordPress 4.8.1 as legacy mode will work

= 1.5 =
* fix to remove some extra spaces shown in text box
* addition of settings option to Settings>General

= 1.4 =
* Error in code corrected

= 1.3 =
* Removed visual editor by default


= 1.2 =
* minor code tidy up

= 1.1 =
* Remastered to use filters / hooks

= 1.0 =
* First Release
