=== Mailchimp as a Registration ===
Contributors: phkcorp2005
Tags: mailchimp, custom registration page, terms dialog
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=HMJDBZQKYKRFJ
Requires at least: 3.7
Tested up to: 5.5
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Integrate mailchimp with your blog new user registration.

== Description ==
Integrate mailchimp email campaign service with your wordpress blog new user registration form. When you allow anyone to register on your blog, their information is save to a mailchimp list and campaign automatically. This makes it easy to create your own mailing list to solicit your products and services through a recognize email marketer.

Additionally, mailchimp maintains a list of spammer emails and will automatically detect if the registered user is a spammer.

An added feature, includes a jquery popup modal dialog to show your custom terms of use with an Accept and Decline button. If the Accept button is not pressed, then the Register button remains disabled, thus preventing unwanted registered users. No need for capcha here as the user must click a popup and accept button.

Available on Amazon for the Kindle: Guide to Using Mailchimp-as-a-Registration, (http://www.amazon.com/dp/B00GAZP2V6)

You do not need a Kindle to read this book. Using the Kindle Free Reading applications for the Cloud, PC, MAC, and Smartphones,
http://www.amazon.com/gp/feature.html?docId=1000493771


== Installation ==
1. Create a new list and campaign on mailchimp with the additional fields (username, password, phone, and phoneext)
2. Upload mailchimp-as-a-registration to the /wp-content/plugins/ directory.
3. Activate the plugin through the Plugins menu in WordPress.
4. From the Admin page under the Settings menu, enter your mailchimp API and save
5. Create a Terms of Use page and give the terms dialog a title.
6. Select which list to automatically add the new users
7. Save your changes.

== Frequently Asked Questions == 

= How does this plugin differ from other plugin that use mailchimp =
This plugin will automatically add register users to a preconfigure mailchimp list and does not require a lot of configuration, only your mailchimp API key. In addition, you have a fully customized terms of user dialog that prevents any user from registering until they accept your site terms.

== Upgrade Notice ==
You should upgrade your wordpress core to version 3.7 or higher before activating this plugin.

== Screenshots ==
https://github.com/presspage2018/mailchimp-as-a-registration/blob/master/mailchimp-as-a-registration.jpg

== Changelog ==
= 1.1.1 =
* Fix for Methods with the same name as their class will not be constructors
* Updates for Mailchimp API version 3.0

= 1.1 =
* Fix some issues
* WP 4.6

= 1.0 =
* Initial release