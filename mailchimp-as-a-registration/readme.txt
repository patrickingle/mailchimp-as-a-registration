== Mailchimp as a Registration ==
Contributors: Patrick Ingle
Tags: mailchimp, custom registration page, terms dialog
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=HMJDBZQKYKRFJ
Requires at least: 3.7
Tested up to: 3.7
Stable tag: 3.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Integrate mailchimp with your blog new user registration.

== Description ==
Integrate mailchimp email campaign service with your wordpress blog new user registration form. When you allow anyone to register on your blog, their information is save to a mailchimp list and campaign automatically. This makes it easy to create your own mailing list to solicit your products and services through a recognize email marketer.

Additionally, mailchimp maintains a list of spammer emails and will automatically detect if the registered user is a spammer.

An added feature, includes a jquery popup modal dialog to show your custom terms of use with an Accept and Decline button. If the Accept button is not pressed, then the Register button remains disabled, thus preventing unwanted registered users. No need for capcha here as the user must click a popup and accept button.


== Installation ==
1. Create a new list and campaign on mailchimp with the additional fields (username, password, phone, and phoneext)
2. Upload mailchimp-as-a-registration to the /wp-content/plugins/ directory.
3. Activate the plugin through the Plugins menu in WordPress.
4. From the Admin page under the Settings menu, enter your mailchimp API and save
5. Create a Terms of Use page and give the terms dialog a title.
6. Select which list to automatically add the new users
7. Save your changes.

== Changelog ==
= 1.0 =
* Initial release