=== Live Population for Gravity Forms ===
Contributors: travislopes
Tags: population, merge tags, replacements, gravity forms
Requires at least: 4.2
Tested up to: 4.8
License: GPL-3.0+
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Use merge tags to populate field values, labels and more without reloading the page.

== Description ==

Live Population makes it possible to use merge tags within form field labels, default values, placeholders, descriptions and HTML content.

When building out a form, a new Live Population tab is added under field settings. Inside this tab, you can select what field causes Live Population to occur and which field properties should be updated. Merge tags will be automatically replaced when the form is initially loaded and when the user makes changes when filling out a form.

Live Population requires [Gravity Forms](https://forgravity.com/gravityforms).

= Requirements =

1. [Purchase and install Gravity Forms](https://forgravity.com/gravityforms)
2. WordPress 4.2+
3. Gravity Forms 1.9.14+

= Support =

If you have any problems, please contact support: https://forgravity.com/support/

== Installation ==

1.  Download the zipped file.
1.  Extract and upload the contents of the folder to your /wp-contents/plugins/ folder.
1.  Navigate to the WordPress admin Plugins page and activate the "Live Population for Gravity Forms" plugin.

== ChangeLog ==

= 1.3.11 =
- Added support for List field descriptions and labels.
- Fixed special characters converting to HTML entities.

= 1.3.10 =
- Fixed List field values incorrectly populating as comma separated list when replacing choices.

= 1.3.9 =
- Fixed Live Population not triggering on Select fields using enhanced user interface. 

= 1.3.8 =
- Added "fg_livepopulation_pre_population" and "fg_livepopulation_post_population" Javascript actions.
- Fixed field choices replacement type setting being displayed when field choices replacement is disabled.

= 1.3.7 =
- Updated field replacement target choices to display admin label if set. If admin label and label are empty, field ID will be displayed.

= 1.3.6 =
- Fixed a fatal Javascript error when using an illegal merge tag.
- Fixed incorrect slug in automatic updater.

= 1.3.5 =
- Added "fg_livepopulation_suppress_empty_choices" filter to disable field choice removal where merge tags are used and the choice text or value is empty.
- Updated field choices to be removed if merge tags are used and the choice text or value is empty.

= 1.3.4 =
- Fixed Field Choices not appearing as available Live Population replacement for fields with choices.

= 1.3.3 =
- Added support for populating merge tags in field choices from non-List fields.

= 1.3.2 =
- Added support for Post fields.
- Fixed duplicate field appearing in Live Population target field setting.

= 1.3.1 =
- Fixed PHP notice when Gravitate Encryption plugin is not installed.

= 1.3 =
- Added support for Gravitate Encryption plugin.
- Added support for review pages.
- Added ability to select multiple target fields to trigger Live Population. 

= 1.2 =
- Added "fg_livepopulation_delay" Javascript filter to change delay time when using "keyup" Javascript event.
- Added "fg_livepopulation_event_type" Javascript filter to change Javascript event for non-checkbox/radio input fields.
- Added plugin capabilities.
- Added support for AJAX enabled forms.
- Added support for {all_fields} merge tag in HTML fields.
- Fixed a fatal error enqueuing front-end script when using versions of PHP prior to 5.5.
- Fixed fields being included for Live Population when no target field was selected.
- Fixed Live Population not running due to asynchronous AJAX requests being disabled.
- Fixed Live Population not taking place when selecting checkbox or radio choices.
- Updated population on form render to replace merge tags even when a target field is not selected.
- Updated Live Population field settings tab to more clearly explain functionality.

= 1.1 =
- Added population of checkbox, multi select, radio and select field choices from list fields.
- Added support for populating section field labels.
- Fixed description not populating if description was blank prior to population.
- Fixed missing Live Population field settings for file upload fields.
- Improved supported for placeholder population.

= 1.0.1 =
- Added support for shortcodes in field content and descriptions.

= 1.0 =
- It's all new!