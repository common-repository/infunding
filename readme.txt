=== InFunding - Plugin for Charity & Crowdfunding Website ===
Contributors: inwavethemes
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=inwavethemes@gmail.com&item_name=Donation+for+InFunding
Tags: fundraising, crowdfunding, donate, charity, campaign, volunteer
Requires at least: 4.3
Tested up to: 4.7
Stable tag: 4.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

InFunding is an advanced WordPress plugin to create and manage charity campaigns, and appeal donation via Paypal or bank transfer.

== Description ==

= Introduction =

InFunding is a WordPress plugin to create charitable program and appeal for donation through Paypal or bank transfer.
With this plugin, you easily create and manage all charity programs, donors and donations.

= Useful links =

Demo link: [http://inwavethemes.com/wordpress/incharity/](http://inwavethemes.com/wordpress/incharity/)

Documentation: [Crowdfunding manual](http://inwavethemes.com/plugins/Crowdfunding_manual_1.0.pdf)

Premium theme: [InCharity - WordPress theme for Charity / Fundraising / Non-profit organization](https://themeforest.net/item/incharity-wordpress-theme-for-charity-fundraising-nonprofit-organization/14802070?rel=inwavethemes)

= Features =

1. Charity program management
	* Create and manage charity campaigns
	* Manage donations (donates) and donors
	* Manage volunteer: Allow volunteer register for a charity campaign.
	* Offer many easy-to-use shortcodes
	* Provide logging system to control system and situation of donates
	
2. Flexibility
 
	* Responsive: Support responsive template
	* Customizable: Template design allows to overwrite for easy displayed layout customization
	* Flexible: A lot of shortcodes support many styles and parameters to create and customize content easily

== Installation ==

= System requirement =

* Server requirements
* PHP 5.0 or above 
* MySQL 5.0 or above 
* WordPress 4.3.x or above;  
* Client requirements 	Firefox 3.5.x, Internet Explorer 7 or above... 

= Install Steps =

This section describes how to install the plugin and get it working.

1. Click "Plugins" > "Add New" in the WordPress admin menu.
2. Search for "InFunding".
3. Click "Install Now".
4. Click "Activate Plugin".

Alternatively, you can manually upload the plugin to your wp-content/plugins directory.

= Setup PayPal IPN for the plugin =

You need setting IPN for your paypal account to make system automatic update campaign status.

1. Log in to your PayPal business account at www.paypal.com.
2. Click the profile icon [Profile menu](https://www.paypalobjects.com/webstatic/en_US/developer/docs/admin/hawk-profile-icon.png) on the top right side of the page. From the **Business Profile** menu, select **Profile and Settings**, then select **My selling tools**.
> _**Note:** If you do not see the profile icon on the top right, navigate to **My Account** > **Profile** > **My Selling Tools**._
3. Click the **Update** link in the Instant payment notifications row, in the Getting paid and managing my risk section.
4. Click Choose **IPN Settings** to specify your listener's URL and activate the listener. The following page opens:
![ProfileIPNEdit](https://www.paypalobjects.com/webstatic/en_US/developer/docs/ipn/ProfileIPNEdit.gif)
5. Specify the URL for your listener in the Notification URL field.
6. Click **Receive IPN messages (Enabled)** to enable your listener.
7. Click **Save**. The following page opens:
8. Click **Back to Profile Summary** to return to the Profile after activating your listener. You also can click Edit settings to modify your notification URL or disable your listener. You can click Turn Off IPN to reset your IPN preferences.

You IPN url is: http://your_site_url/wp-admin/admin-ajax.php?action=infPaymentNotice

== Frequently Asked Questions ==

= Google Map don't work on charity detail =

You should provide **Google API** in **plugin Settings** follow path **Crowdfunding** > **Settings** > **General**

== Screenshots ==

1. Campaign manage
2. Plugin settings: General setting
3. Plugin settings: Registration form - Make custom form field for member.
4. Plugin settings: Payment setting
5. Plugin settings: Email template
6. Campaign listing page - Grid view
7. Campaign listing page - List view
8. Campaign detail

== Changelog ==

= 1.0 =

* Initial release

== Upgrade Notice ==

= 1.0 =

Initial release
