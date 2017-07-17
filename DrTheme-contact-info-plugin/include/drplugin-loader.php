<?php

/**
 * Loader class for the Contact Info
 *
 * This class takes care of the DrTheme Contact Info Plugins and tries to load the different parts as late as possible.
 * On normal requests, only this small class is loaded. When the DrTheme Contact Info needs to be rebuild, the generator itself is loaded.
 * The last stage is the user interface which is loaded when the administration page is requested.
 *
 * @author Rachmat Yanuarsyah
 * @package DrTheme Contact Info
 */
 
class DrThemeContactInfoLoader {
	
	/**
	 * Enabled the DrTheme Contact Info plugin with registering all required hooks
	 *
	 * @since 1.0.0
	 */
	public static function Enable() {

		add_action('admin_menu', array(__CLASS__, 'RegisterAdminPage'));
		add_action('admin_init', array(__CLASS__,'RegisterAdminSectionPage' ));
		add_action('admin_notices', array(__CLASS__,'CallAdminMsgs' ) );
		add_action('admin_enqueue_scripts', array(__CLASS__,'RegisterAdminPageScripts' ) );
		add_action('wp_enqueue_scripts',array(__CLASS__,'RegisterPageScripts' ));
		add_filter('plugin_row_meta', array(__CLASS__, 'RegisterPluginLinks'), 10, 2);

		add_shortcode('ContactInfoShortCode', array(__CLASS__,'RegisterShortCode'));
	}

	/**
	 * Registers additional links for the DrTheme Contact Info plugin on the WP plugin configuration page
	 *
	 * Registers the links if the $file param equals to the DrTheme Contact Info Plugins
	 * @param $links Array An array with the existing links
	 * @param $file string The file to compare to
	 * @return string[]
	 */
	public static function RegisterPluginLinks($links, $file) {
		$base = DRTHEME_CONTACT_PLUGIN;
		if($file == $base) {
			$links[] = '<a href="admin.php?page=' . DRTHEME_CONTACT_PLUGIN_BASENAME  . '">' . __('Settings', 'drtheme') . '</a>';
			$links[] = '<a href="">' . __('Support', 'drtheme') . '</a>';
		}
		return $links;
	}
	
	/**
	 * Registers additional links for js and css file for the DrTheme Contact Info plugin on the WP plugin configuration page
	 *
	 * @since 1.0.0
	 */
	public static function RegisterAdminPageScripts() {
		if(self::LoadPlugin()) {
			DrThemeContactInfo::GetInstance()->settings_scripts();
		}
	}
	
	/**
	 * Registers additional links for js and css file for the DrTheme Contact Info plugin on the WP plugin configuration page
	 *
	 * @since 1.0.0
	 */
	public static function RegisterPageScripts() {
		if(self::LoadPlugin()) {
			DrThemeContactInfo::GetInstance()->add_scripts();
		}
	}
	/**
	 * Registers the plugin in the admin menu system
	 *
	 * @uses add_options_page()
	 */
	public static function RegisterAdminPage() {
		if(!defined('DRTHEME_PLUGIN_SETTINGS')){
			define('DRTHEME_PLUGIN_SETTINGS', 'DrTheme-Plugin-Settings'); // define the plugin settings page slug
			add_menu_page( __('DrTheme Plugins Settings','drtheme') , __('DrTheme Plugins','drtheme') , 'administrator',DRTHEME_PLUGIN_SETTINGS,array(__CLASS__, 'CallHtmlShowGeneralOptionsPage'),'dashicons-admin-settings',68);
		}
		add_submenu_page(DRTHEME_PLUGIN_SETTINGS, __('Contact Info', 'drtheme'),__('Contact Info Plugin', 'drtheme'),'administrator', DRTHEME_CONTACT_PLUGIN_BASENAME,array(__CLASS__, 'CallHtmlShowOptionsPage'),'',1);
	}
	
	/**
	 * Register our setting option
	 * 
	 * @since 1.0.0
	 */
	public static function RegisterAdminSectionPage(){
		if(self::LoadPlugin()) {
			$settings_page_sections_output=DrThemeContactInfo::GetInstance()->options_page_sections();
			//setting
			register_setting(DRTHEME_CONTACT_PLUGIN_OPTIONS_NAME, DRTHEME_CONTACT_PLUGIN_OPTIONS_NAME, array(__CLASS__, 'CallValidateOptionsPage') );
			foreach ( $settings_page_sections_output as $id => $title ) {
	            	add_settings_section( $id, $title, '__return_false',$id);
	        }
			foreach (DrThemeContactInfo::GetInstance()->options_page_fields() as $option) {
	            self::create_settings_field($option);
	        }
		}
	}

	/**
	 * Helper function for registering our form field settings
	 *
	 * @param (array) $args The array of arguments to be used in creating the field
	 *
	 */
	public static function create_settings_field( $args = array() ) {
		// default array to overwrite when calling the function
		$defaults = array(
			'id'      => 'default_field', 					// the ID of the setting in our options array, and the ID of the HTML form element
			'title'   => 'Default Field', 					// the label for the HTML form element
			'desc'    => '', 	// the description displayed under the HTML form element
			'std'     => '', 								// the default value for this setting
			'type'    => 'text', 							// the HTML form element to use
			'section' => 'general_section',					// the section this setting belongs to � must match the array key of a section in DrTheme_options_page_sections()
			'choices' => array(), 							// (optional): the values in radio buttons or a drop-down menu
			'class'   => '', 								// the HTML form element class. Is used for validation purposes and may be also use for styling if needed.
			'placeholder'=>'',
			'order'=>''
		);
		
		// "extract" to be able to use the array keys as variables in our function output below
		extract( wp_parse_args( $args, $defaults ) );
		
		// additional arguments for use in form field output in the function DrTheme_form_field_fn!
		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'desc'      => $desc,
			'std'       => $std,
			'choices'   => $choices,
			'label_for' => $id,
			'class'     => $class,
			'placeholder'=> $placeholder,
			'order'		=>$order
		);
	
		add_settings_field( $id, $title, array(__CLASS__, 'CallFormFieldsPage'), $section, $section, $field_args );
	}
	/**
	 * Helper function for registering our shortcode
	 *
	 * @param (array) $args 
	 * @return function call
	 */
	public static function RegisterShortCode($args){
		if(self::LoadPlugin()) {
			DrThemeContactInfo::GetInstance()->ContactInfoShortCode($args);
		}
	}
	/**
	 * Invokes the CallValidateOptionsPage method
	 * @uses DrThemeContactInfoLoader::LoadPlugin()
	 * @uses DrThemeContactInfo::ValidateOptions()
	 */
	public static function CallValidateOptionsPage($input) {
		
		if(self::LoadPlugin()) {
			return DrThemeContactInfo::GetInstance()-> ValidateOptions($input);
		}
	}
	/**
	 * Invokes the CallFormFieldsPage method
	 * @uses DrThemeContactInfoLoader::LoadPlugin()
	 * @uses DrThemeContactInfo::CallFormFieldsPage()
	 */
	public static function CallFormFieldsPage($args = array()) {
		if(self::LoadPlugin()) {
			DrThemeContactInfo::GetInstance()->FormFieldsPage($args);
			
		}
	}
	/**
	 * Invokes the CallHtmlShowGeneralOptionsPage method
	 * @uses DrThemeContactInfoLoader::LoadPlugin()
	 * @uses DrThemeContactInfo::HtmlShowOptionsPageSettings()
	 */
	public static function CallHtmlShowGeneralOptionsPage() {
		if(self::LoadPlugin()) {
			DrThemeContactInfo::GetInstance()->HtmlShowOptionsPageSettings();
		}
	}
	/**
	 * Invokes the CallHtmlShowOptionsPage method
	 * @uses DrThemeContactInfoLoader::LoadPlugin()
	 * @uses DrThemeContactInfo::HtmlShowOptionsPage()
	 */
	public static function CallHtmlShowOptionsPage() {
		if(self::LoadPlugin()) {
			DrThemeContactInfo::GetInstance()->HtmlShowOptionsPage();
		}
	}
	/**
	 * Invokes the CallAdminMsgs method for displaying admin messages
	 *
	 * @return CallAdminMsgs()
	 */
	function CallAdminMsgs() {
	     
	    // check for our settings page - need this in conditional further down
	    $drtheme_settings_pg = strpos($_GET['page'], DRTHEME_CONTACT_PLUGIN_BASENAME);
	    // collect setting errors/notices: //http://codex.wordpress.org/Function_Reference/get_settings_errors
	    $set_errors = get_settings_errors(); 
	     
	    //display admin message only for the admin to see, only on our settings page and only when setting errors/notices are returned! 
	    if(current_user_can ('manage_options') && $drtheme_settings_pg !== FALSE && !empty($set_errors)){
	 
	        // have our settings succesfully been updated? 
	        if($set_errors[0]['code'] == 'settings_updated' && isset($_GET['settings-updated'])){
	          DrThemeContactInfo::GetInstance()->ShowMsgPlugin("<p>" . $set_errors[0]['message'] . "</p>", 'updated');
	         
	        // have errors been found?
	        }else{
	            // there maybe more than one so run a foreach loop.
	            foreach($set_errors as $set_error){
	                // set the title attribute to match the error "setting title" - need this in js file
	                 DrThemeContactInfo::GetInstance()->ShowMsgPlugin("<p class='setting-error-message' title='" . $set_error['setting'] . "'>" . $set_error['message'] . "</p>", 'error');
	            }
	        }
	    }
	}
	/**
	 * Loads the actual generator class and tries to raise the memory and time limits if not already done by WP
	 *
	 * @uses DrThemeContactInfo::Enable()
	 * @return boolean true if run successfully
	 */
	public static function LoadPlugin() {

		if(!class_exists("DrThemeContactInfo")) {

			$mem = abs(intval(@ini_get('memory_limit')));
			if($mem && $mem < 128) {
				@ini_set('memory_limit', '128M');
			}

			$time = abs(intval(@ini_get("max_execution_time")));
			if($time != 0 && $time < 120) {
				@set_time_limit(120);
			}
			if(!file_exists(trailingslashit(dirname(__FILE__)). 'drplugin-core.php')) return false;
			require_once(trailingslashit(dirname(__FILE__)) . 'drplugin-core.php');
		}

		DrThemeContactInfo::Enable();
		return true;
	}
	
	/**
	 * Returns the name of this loader script
	 *
	 * @return string The PLUGIN_VERSION value
	 */
	public static function GetPluginFile() {
		return DRTHEME_CONTACT_PLUGIN_VERSION;
	}
	/**
	 * Returns the plugin version
	 *
	 * Uses the WP API to get the meta data from the top of this file (comment)
	 *
	 * @return string The version like 3.1.1
	 */
	public static function GetVersion() {
		if(!isset($GLOBALS["DrTheme_contact_info_version"])) {
			if(!function_exists('get_plugin_data')) {
				if(file_exists(ABSPATH . 'wp-admin/includes/plugin.php')) {
					require_once(ABSPATH . 'wp-admin/includes/plugin.php');
				}
				else return "0.ERROR";
			}
			$data = get_plugin_data(self::GetPluginFile(), false, false);
			$GLOBALS["DrTheme_contact_info_version"] = $data['Version'];
		}
		return $GLOBALS["DrTheme_contact_info_version"];
	}

	/**
	 * Handled the plugin activation on installation
	 *
	 * @since 1.0.0
	 */
	public static function ActivatePlugin() {
		flush_rewrite_rules();
	}

	/**
	 * Handled the plugin deactivation
	 *
	 * @since 1.0.0
	 */
	public static function DeactivatePlugin() {
		delete_option(DRTHEME_CONTACT_PLUGIN_OPTIONS_NAME);
	}
}
//Enable the plugin for the init hook, but only if WP is loaded. Calling this php file directly will do nothing.
if(defined('ABSPATH') && defined('WPINC') && !class_exists('DrThemeContactInfoWidget')) {
	add_action("init", array("DrThemeContactInfoLoader", "Enable"), 15, 0);
	if(!file_exists(trailingslashit(dirname(__FILE__)) . "drplugin-widget.php")) return false;
		require_once(trailingslashit(dirname(__FILE__))."drplugin-widget.php");
		add_action("widgets_init",array("DrThemeContactInfoWidget",'register') );
	
}