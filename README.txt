=== SolPress Solana Login ===
Contributors: solpressplugins
Donate link: https://solpress.dev
Tags: solana pay, woocommerce, payment, payment gateway, solana, crypto, phantom
Requires at least: 4.7
Tested up to: 6.0
Stable tag: 1.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Register and login to WordPress with Solana blockchain wallets.

== Description ==

This plugin implements a Solana wallet connector for the registration and login of WordPress Users using only their Solana wallet. The plugin ties a WordPress user account to the public wallet address as the username. We use a standard WordPress plugin framework and a Node.js app (with Express framework) on Heroku for the verification of wallet signing.  We are also working on a feature for local verification with fallback to our Node app. All of the plugin code and our Node.js app code are available on our github. If you want to run your own verification, you can achieve that by hosting the Node.js app yourself.

This plugin bridges the open source communities of the Solana blockchain and WordPress. It allow WordPress website owners a new and fun way for users to register and login to their website without an email and password or social media.

Features include:

*Shortcode for use in any WordPress Page, Post, and Widget - [solpress_login_button]
*Open Source (code available on SolPress.dev and SolPress Github)
*Edit the Login button text
*Edit the Register button text
*Edit the Shortcode button text
*Edit the User Profile button text
*Edit the Sign In Message (prompted in Solana Wallet)
*Edit the Redirection URL
*Works with SolPress Payment Gateway Plugin & SolWall Plugin

== Installation ==

1. Upload `solpress-login.php` to the `/wp-content/plugins/` directory
or Use WordPress’ Add New Plugin feature, search “solpress login”,
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Adjust any settings you would like.

== Frequently Asked Questions ==

Q. Why Solana and WordPress? 
A. Both Solana & WordPress are open source projects used globally by developers, designers, artists and more. We aim to connect Web2 technologies like WordPress with Web3 blockchain technologies like Solana. Together we can create a free, global community of cryptocurrency wallet users who want to interact with websites in new and interesting ways.

== Screenshots ==

1. Frontend Display of Solana Wallet login on default WordPress Login Page
2. Settings of SolPress Login plugin in backend of WordPress

== Changelog ==

= 1.0 =
* Initial version launched