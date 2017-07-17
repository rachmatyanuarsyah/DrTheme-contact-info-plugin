<?php
/**
* Plugin Name: DrTheme contact info plugin
* Plugin URI: "https://github.com/rachmatyanuarsyah/DrTheme-contact-info-plugin"
* Description: A plugin to show contact info.
* Version: 1.0.0
* Author: Rachmat Yanuarsyah
* Author URI: "https://github.com/rachmatyanuarsyah"
* 
*/

//Don't do anything if this file was called directly
if (defined('ABSPATH') && defined('WPINC') && !class_exists("DrThemeContactInfoLoader", false)) {
	DrPlugin_Contact_Info_Setup();
}
/**
 * define our setting plugin
 * 
 * @since 1.0.0
 */

define('DRTHEME_CONTACT_PLUGIN_BASENAME', 'DrTheme-Contact-Info');//the settings Basename
define('DRTHEME_CONTACT_PLUGIN_OPTIONS_NAME','DrTheme_contact_info_options');//the option name
define('DRTHEME_CONTACT_PLUGIN_OPTIONS_VALUE','contact_info');//the option name
define('DRTHEME_CONTACT_PLUGIN_VERSION', __FILE__);// the plugin version
define('DRTHEME_CONTACT_PLUGIN', plugin_basename(__FILE__));// the settings Basename file
define('DRTHEME_CONTACT_PLUGIN_URL', plugins_url( '', __FILE__ ) );// used to prefix plugin folder
define('DRTHEME_CONTACT_PLUGIN_IMAGES', DRTHEME_CONTACT_PLUGIN_URL . '/assets/image/' ); //image folder
define('DRTHEME_CONTACT_PLUGIN_STYLES', DRTHEME_CONTACT_PLUGIN_URL . '/assets/css/' ); //style folder
define('DRTHEME_CONTACT_PLUGIN_SCRIPTS', DRTHEME_CONTACT_PLUGIN_URL . '/assets/js/' );//js folder

/**
 * Check if the requirements of the DrTheme contact info plugin are met and loads the actual loader
 *
 * @since 1.0.0
 */
function DrPlugin_Contact_Info_Setup() {

	$fail = false;

	//Check minimum PHP requirements, which is 5.2 at the moment.
	if (version_compare(PHP_VERSION, "5.0", "<")) {
		add_action('admin_notices', 'DrPlugin_AddPhpVersionError');
		$fail = true;
	}

	//Check minimum WP requirements, which is 4.8 at the moment.
	if (version_compare($GLOBALS["wp_version"], "4.7", "<")) {
		add_action('admin_notices', 'DrPlugin_AddWpVersionError');
		$fail = true;
	}

	if (!$fail) {
		require_once(trailingslashit(dirname(__FILE__))."include/drplugin-loader.php") ;
		register_activation_hook(__FILE__, array("DrThemeContactInfoLoader", "ActivatePlugin"));
		register_deactivation_hook(__FILE__, array("DrThemeContactInfoLoader", "DeactivatePlugin"));
	}
}
/**
 * Adds a notice to the admin interface that the WordPress version is too old for the plugin
 *
 *  @since 1.0.0
 */
if(!function_exists('DrPlugin_AddPhpVersionError')){
	function DrPlugin_AddWpVersionError() {
		echo "<div id='sm-version-error' class='error fade'><p><strong>" . __('Your WordPress version is too old for Contact Info Plugins.', 'drtheme') . "</strong><br /> " . sprintf(__('Unfortunately this release of Contact Info Plugins requires at least WordPress %3$s. You are using Wordpress %2$s, which is out-dated and insecure. Please upgrade or go to <a href="%1$s">active plugins</a> and deactivate the Contact Info Plugins to hide this message.', 'drtheme'), "plugins.php?plugin_status=active", $GLOBALS["wp_version"],"4.7") . "</p></div>";
	}
}
/**
 * Adds a notice to the admin interface that the WordPress version is too old for the plugin
 *
 *  @since 1.0.0
 */
if(!function_exists('DrPlugin_AddPhpVersionError')){
	function DrPlugin_AddPhpVersionError() {
		echo "<div id='sm-version-error' class='error fade'><p><strong>" . __('Your PHP version is too old for Contact Info Plugins.', 'drtheme') . "</strong><br /> " . sprintf(__('Unfortunately this release of Contact Info Plugins requires at least PHP %3$s. You are using PHP %2$s, which is out-dated and insecure. Please ask your web host to update your PHP installation or go to <a href="%1$s">active plugins</a> and deactivate the Contact Info Plugins to hide this message.', 'drtheme'), "plugins.php?plugin_status=active", PHP_VERSION,"5.2") . "</p></div>";
	}
}