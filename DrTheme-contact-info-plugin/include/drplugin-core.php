<?php
/**
 * Class to generate a DrTheme Contact Info class.
 *
 * @package DrTheme Contact Info
 * @author Rachmat Yanuarsyah
 * @since 1.0
 */
final class DrThemeContactInfo {
	/**
	 * @var array The unserialized array with the stored options
	 */
	private $options = array();

	/**
	 * @var array The saved additional pages
	 */
	private $pages = array();


	/**
	 * @var bool True if init complete (options loaded etc)
	 */
	private $isInitiated = false;


	/**
	 * Holds the user interface object
	 *
	 * @since 1.0.0
	 * @var DrThemeContactInfoUI
	 */
	private $ui = null;

	/**
	 * @var bool Defines if the options have been loaded
	 */
	private $optionsLoaded = false;


	/*************************************** CONSTRUCTION AND INITIALIZING ***************************************/

	/**
	 * Initializes a new DrTheme Contact Info
	 *
	 * @since 1.0.0
	 */
	private function __construct() {

	}

	/**
	 * Returns the instance of the DrTheme Contact Info
	 *
	 * @since 1.0.0
	 * @return DrThemeContactInfo The instance or null if not available.
	 */
	public static function GetInstance() {
		if(isset($GLOBALS["DrPluginContactInfo_instance"])) {
			return $GLOBALS["DrPluginContactInfo_instance"];
		} else return null;
	}

	/**
	 * Enables the DrThemeContactInfo and registers the WordPress hooks
	 *
	 * @since 1.0.0
	 */
	public static function Enable() {
		if(!isset($GLOBALS["DrPluginContactInfo_instance"])) {
			$GLOBALS["DrPluginContactInfo_instance"] = new DrThemeContactInfo();
		}
	}
	/**
	 * Loads up the configuration and validates the prioity providers
	 *
	 * This method is only called if the DrthemeContactInfo needs to be build or the admin page is displayed.
	 *
	 * @since 1.0.0
	 */
	public function Initate() {
		if(!$this->isInitiated) {
			load_plugin_textdomain('DrPlugin_Contact_Info',false,dirname( DRTHEME_CONTACT_PLUGIN ) .  '/lang');
			$this->isInitiated = true;
		}
	}
	
	/**
	 * Group scripts (js & css)
	 * 
	 * @since 1.0.0
	 */
	function settings_scripts(){
	    if ('drtheme-plugins_page_'.DRTHEME_CONTACT_PLUGIN_BASENAME == get_current_screen() -> id || 'toplevel_page_'.DRTHEME_PLUGIN_SETTINGS == get_current_screen() -> id ) {
	    	wp_enqueue_style('plugin-style', DRTHEME_CONTACT_PLUGIN_STYLES . 'bootstrap.min.css');
	    }
	}
	/**
	 * Group scripts (js & css)
	 * 
	 * @since 1.0.0
	 */
	function add_scripts(){
	    wp_enqueue_style('contact-info-style', DRTHEME_CONTACT_PLUGIN_STYLES . 'contactinfo.css');
	}
	
	/*************************************** VERSION AND LINK HELPERS ***************************************/

	/**
	 * Returns the version of the DrTheme Contact Info
	 *
	 * @since 1.0.0
	 * @return int The version
	 */
	public static function GetVersion() {
		return DrThemeContactInfoLoader::GetVersion();
	}
			
	/**
	 * Helper function: Check for tabs and return the current tab name
	 * @Since 1.0.0
	 * @return string
	 */
	function get_current_tab() {
		// read the current tab when on our settings page
		$current_tab 	= (isset($_GET['tab']) ? $_GET['tab'] : 'general');
		
		return $current_tab;
	}
	
	/**
	 * Form Fields HTML
	 * All form field types share the same function!!
	 * @Since 1.0.0
	 * @param (array) $args The array of arguments to be used in creating html output
	 * @return echoes output
	 */
	function FormFieldsPage($args = array()) {
		extract( $args );
		$options 			= get_option(DRTHEME_CONTACT_PLUGIN_OPTIONS_NAME);

		// pass the standard value if the option is not yet set in the database
		if ( !isset( $options[$id] ) && 'type' != 'checkbox') {
			$options[$id] = $std;
		}
		// additional field class. output only if the class is defined in the create_setting arguments
		$field_class = ($class != '') ? ' ' . $class : '';
		// switch html display based on the setting type.
		
		switch ( $type ) {
			case 'text':
				$options[$id] = stripslashes($options[$id]);
				$options[$id] = esc_attr( $options[$id]);
				echo "<div class='input-group input-group-sm '>";
				if($field_class==="email"){					
					echo "<input class='form-control regular-text$field_class' type='email' id='$id' placeholder='$placeholder' name='" . DRTHEME_CONTACT_PLUGIN_OPTIONS_NAME . "[$id]' value='$options[$id]' />";
				}else{
					echo "<input class='form-control regular-text$field_class' type='text' id='$id' placeholder='$placeholder' name='" . DRTHEME_CONTACT_PLUGIN_OPTIONS_NAME . "[$id]' value='$options[$id]' />";
				}
				echo "</div>";
				echo ($desc != '') ? "<small>$desc</small>" : "";
			break;
			case'tel':
				$phone1=$id."1";
				$phone2=$id."2";
				$options[$phone1] = esc_attr(stripslashes($options[$phone1]));
				$options[$phone2] =  esc_attr(stripslashes($options[$phone2]));
				echo "<div class='input-group input-group-sm '>";
				echo "<input class='form-control regular-text$field_class' type='text' id='$phone1' name='" . DRTHEME_CONTACT_PLUGIN_OPTIONS_NAME  . "[$phone1]' placeholder='$placeholder' value='$options[$phone1]' />";
				echo "</div></td><td class='col-xs-3'><div class='input-group input-group-sm '>";
				echo "<input class='form-control regular-text$field_class' type='text' id='$phone2' name='" . DRTHEME_CONTACT_PLUGIN_OPTIONS_NAME  . "[$phone2]' placeholder='$placeholder' value='$options[$phone2]' /></div>";
				echo ($desc != '') ? "<small>$desc</small>" : "";
			break;
			case'wa':
				$options[$id] = stripslashes($options[$id]);
				$options[$id] = esc_attr( $options[$id]);
				echo "<div class='input-group input-group-sm '>";
				echo "<input class='form-control regular-text$field_class' type='text' id='$id' name='" . DRTHEME_CONTACT_PLUGIN_OPTIONS_NAME . "[$id]' placeholder='$placeholder' value='$options[$id]' />";
				echo "</div>";
				echo ($desc != '') ? "<small>$desc</small>" : "";
			break;
			case 'textarea':
				$options[$id] = stripslashes($options[$id]);
				$options[$id] = esc_html( $options[$id]);
				echo "<div class='input-group input-group-md '>";
				echo "<textarea class='form-control textarea$field_class' type='text' id='$id' name='" . DRTHEME_CONTACT_PLUGIN_OPTIONS_NAME . "[$id]' rows='5' cols='30'>$options[$id]</textarea>";
				echo "</div>";
				echo ($desc != '') ? "<small>$desc</small>" : ""; 		
			break;
		}
	}
	
	/**
	 * Validate input
	 * @Since 1.0.0
	 * @return array
	 */
	function ValidateOptions($input= array()) {
		// for enhanced security, create a new empty array
	    $valid_input = array();
		// get the settings sections array
		$options = $this->options_page_fields();
		if (isset($_POST['reset'])) {
			return $valid_input; 
		}
		// run a foreach and switch on option type
		foreach ($options as $option) {
			switch ( $option['type'] ) {
				case 'tel':
					for($i=1;$i<3;$i++){
						//accept the input only after the email has been validated
						$input[$option['id'].$i] 		= trim($input[$option['id'].$i]); // trim whitespace
						if($input[$option['id'].$i]!="") {
							$valid_input[$option['id'].$i] = (is_numeric( $input[$option['id'].$i])!== FALSE ) ? $input[$option['id'].$i] : __('Invalid Phone Number! Please re-enter!','drtheme');
						}else{
							$valid_input[$option['id'].$i] = (is_numeric( $input[$option['id'].$i])!== FALSE ) ? $input[$option['id'].$i] : "";
						}
						// register error
						if(is_numeric($input[$option['id'].$i])== FALSE ) {
							if($input[$option['id'].$i]!="") {
								add_settings_error(
									$option['id'].$i, // setting title
									DRTHEME_CONTACT_PLUGIN_OPTIONS_NAME . $option['id'].$i, // error ID
									sprintf(__('Please enter a valid phone number %1$s.','drtheme'),esc_attr($i)), // error message
									'error' // type of message
								);
							}
						}
					}
					
				break;
				case 'text':
					//switch validation based on the class!
					switch ( $option['class'] ) {
						//for no html
						case 'nohtml':
							//accept the input only after stripping out all html, extra white space etc!
							$input[$option['id']] 		= sanitize_text_field($input[$option['id']]); // need to add slashes still before sending to the database
							$valid_input[$option['id']] = addslashes($input[$option['id']]);
						break;
						
						//for email
						case 'email':
							//accept the input only after the email has been validated
							$input[$option['id']] 		= trim($input[$option['id']]); // trim whitespace
							if($input[$option['id']] != ''){
								$valid_input[$option['id']] = (is_email($input[$option['id']])!== FALSE) ? $input[$option['id']] : __('Invalid email! Please re-enter!','drtheme');
							}else{
								$valid_input[$option['id']] = (is_email( $input[$option['id']])!== FALSE ) ? $input[$option['id']] : "";
							}
							
							// register error
							if(is_email($input[$option['id']])== FALSE){
								if($input[$option['id']] != '') {
									add_settings_error(
										$option['id'], // setting title
										DRTHEME_SHORTNAME . $option['id'], // error ID
										__('Please enter a valid email address.','drtheme'), // error message
										'error' // type of message
									);
								}
							}
						break;
					}
				break;
				case 'textarea':
					//switch validation based on the class!
					switch ( $option['class'] ) {
						//for no html
						case 'nohtml':
							//accept the input only after stripping out all html, extra white space etc!
							$input[$option['id']] 		= sanitize_text_field($input[$option['id']]); // need to add slashes still before sending to the database
							$valid_input[$option['id']] = addslashes($input[$option['id']]);
						break;
						
						//for allowlinebreaks
						case 'allowlinebreaks':
							//accept the input only after stripping out all html, extra white space etc!
							$input[$option['id']] 		= wp_strip_all_tags($input[$option['id']]); // need to add slashes still before sending to the database
							$valid_input[$option['id']] = addslashes($input[$option['id']]);
						break;
					}
				break;
				case 'wa':
					//accept the input only after the email has been validated
					$input[$option['id']] 		= trim($input[$option['id']]); // trim whitespace
					if($input[$option['id']]!="") {
						$valid_input[$option['id']] = (is_numeric( $input[$option['id']])!== FALSE ) ? $input[$option['id']] : __('Invalid Phone Number! Please re-enter!','drtheme');
					}else{
						$valid_input[$option['id']] = (is_numeric( $input[$option['id']])!== FALSE ) ? $input[$option['id']] : "";
					}
					// register error
					if(is_numeric($input[$option['id']])== FALSE ) {
						if($input[$option['id']]!="") {
							add_settings_error(
								$option['id'], // setting title
								DRTHEME_CONTACT_PLUGIN_OPTIONS_NAME . $option['id'], // error ID
								__('Please enter a valid phone number Wa.','drtheme'), // error message
								'error' // type of message
							);
						}
					}
					
				break;
			}
		}
		return $valid_input; // return validated input
	}
	
	/**
	 * Helper function for creating admin messages
	 * src: http://www.wprecipes.com/how-to-show-an-urgent-message-in-the-wordpress-admin-area
	 *
	 * @param (string) $message The message to echo
	 * @param (string) $msgclass The message class
	 * @return echoes the message
	 */
	function ShowMsgPlugin($message, $msgclass = 'info') {
	    echo "<div id='setting-error-settings_updated' class='$msgclass notice is-dismissible'>$message</div>";
	}
	
	/** Call the function and collect in variable
	 * Should be used in template files like this:
	 * <?php echo $options['name of option']; ?>
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	function GetContactInfoOptions() {
		$option_names =get_option(DRTHEME_CONTACT_PLUGIN_OPTIONS_NAME);
		return $option_names;
	}
	
	/**
	 * Helper function for registering our form field Contact Info settings to shortcode
	 *
	 * @param (array) $args The array of arguments to be used in creating the field
	 * @return echos output
	 */
	function ContactInfoShortCode($args){
		$options = $this->GetContactInfoOptions();
		extract(shortcode_atts(array( 
			'email' 	=> "Email",
			'phone'		=> "Phone",
			'wa'		=> "WhatsApp",
			'bbm'		=> "BBM",
			'office'	=> "Head Office",
			'branch'	=> "Branch Office",
		), $args));?>
	
		<ul id="contact-info" class="contacts-list">
			<li class="email" >
				<span class="name"><?php echo esc_attr($email);?></span>
				<span>
					<a href="mailto:<?php echo $options[DRTHEME_CONTACT_PLUGIN_OPTIONS_VALUE.'_email_txt_input'];?>?Subject=Minta%20Daftar%20Harga%20Paket" target="_top">
						 <?php echo $options[DRTHEME_CONTACT_PLUGIN_OPTIONS_VALUE.'_email_txt_input'];?>
					</a>
				</span>
			</li>
			<li class="phone <?php echo (!empty($options[DRTHEME_CONTACT_PLUGIN_OPTIONS_VALUE.'_phone_2']))?'double-phone':'';?>" >
				<span class="name <?php echo (!empty($options[DRTHEME_CONTACT_PLUGIN_OPTIONS_VALUE.'_phone_2']))?'name-double':'';?>"><?php echo esc_attr($phone);?></span>
				
				<span>
					<?php echo $options[DRTHEME_CONTACT_PLUGIN_OPTIONS_VALUE.'_phone_1'];
					if(!empty($options[DRTHEME_CONTACT_PLUGIN_OPTIONS_VALUE.'_phone_2'])){
						echo " / ".$options[DRTHEME_CONTACT_PLUGIN_OPTIONS_VALUE.'_phone_2'];
					}?> 
				</span>
			</li>
			<li class="wa">
				<span class="name"><?php echo esc_attr($wa);?></span>
				<span>
					 <?php echo $options[DRTHEME_CONTACT_PLUGIN_OPTIONS_VALUE.'_wa_input'];?> 
				</span>					
			</li>
			<?php if(!empty($options[DRTHEME_CONTACT_PLUGIN_OPTIONS_VALUE.'_bbm_input'])):?>
			<li class="bbm" >
				<span class="name"><?php echo esc_attr($bbm);?></span>
				<span>
					<?php echo $options[DRTHEME_CONTACT_PLUGIN_OPTIONS_VALUE.'_bbm_input'];?>
				</span>
			</li>
			<?php endif;?>
			<li class="address">
				<span class="name" ><?php echo esc_attr($office);?></span>
				<?php echo $options[DRTHEME_CONTACT_PLUGIN_OPTIONS_VALUE.'_office_address_input'];?>.
				<?php if(!empty($options[DRTHEME_CONTACT_PLUGIN_OPTIONS_VALUE.'_branch_address_input'])):?>
				<br><br>
				<span class="name"><?php echo esc_attr($branch);?></span>
				<?php echo $options[DRTHEME_CONTACT_PLUGIN_OPTIONS_VALUE.'_branch_address_input'];?>.
				<?php endif; ?>
			</li>
		</ul>
	<?php
	}
	
	/*************************************** USER INTERFACE ***************************************/
	
	/**
	 * Plugin Admin Settings Page Tabs
	 *  
	 * @Since 1.0.0
	 */
	 function settings_page_tabs() {
	 	$tabs = array();
	 	$tabs['general'] = __('General','drtheme');
		$tabs['add_contact_info'] = __('Add Contact Info','drtheme');
		return $tabs;
	 } 
	
	/**
	 * Define our settings sections
	 *
	 * array key=$id, array value=$title in: add_settings_section( $id, $title, $callback, $page );
	 * @Since 1.0.0
	 * @return array
	 */
	function options_page_sections() {
		$tab=$this->get_current_tab();
		$sections = array();
		if($tab!='general'){
			$sections['contact_info'] 	= __('Contact Info', 'drtheme');
		}
		return $sections;
	}
	
	/**
	 * Define our form fields (options) 
	 *
	 * @Since 1.0.0
	 * @return array
	 */
	function options_page_fields() {
		// setting fields according to tab
		$options[] = array(
			"section" => "contact_info",
			"id"      => DRTHEME_CONTACT_PLUGIN_OPTIONS_VALUE . "_email_txt_input",
			"title"   => __( 'Email Address', 'drtheme' ),
			"desc"    => __( 'A text input field which can be used for email input.', 'drtheme' ),
			"type"    => "text",
			"std"     => "",
			"class"   => "email",
			"placeholder" => __('Please insert valid email address','drtheme')
		);
		
		$options[] = array(
			"section" => "contact_info",
			"id"      => DRTHEME_CONTACT_PLUGIN_OPTIONS_VALUE. "_phone_",
			"title"   => __( 'Phone Number', 'drtheme' ),
			"desc"    => "",
			"type"    => "tel",
			"std"     => '',
			"placeholder" =>__('Please insert valid phone number','drtheme')
		);
		
		$options[] = array(
			"section" => "contact_info",
			"id"      => DRTHEME_CONTACT_PLUGIN_OPTIONS_VALUE . "_bbm_input",
			"title"   => __( 'BBM Number', 'drtheme' ),
			"desc"    => __( 'A regular text input field for BBM number (Optional).', 'drtheme' ),
			"type"    => "text",
			"std"     => '',
			"class"   => "nohtml",
			"placeholder" => __('Please insert valid bbm number','drtheme')
		);
		
		$options[] = array(
			"section" => "contact_info",
			"id"      => DRTHEME_CONTACT_PLUGIN_OPTIONS_VALUE . "_wa_input",
			"title"   => __( 'WhatsApp Number', 'drtheme' ),
			"desc"    => __( 'A phone number input field for WhatsApp number.', 'drtheme' ),
			"type"    => "wa",
			"std"     => '',
			"placeholder" => __('Please insert valid phone number','drtheme')
		);
		
		$options[] = array(
			"section" => "contact_info",
			"id"      => DRTHEME_CONTACT_PLUGIN_OPTIONS_VALUE. "_office_address_input",
			"title"   => __( 'Head Office Address', 'drtheme' ),
			"desc"    => __( 'A textarea for an address!', 'drtheme' ),
			"type"    => "textarea",
			"std"     => '',
			"class"	  =>"allowlinebreaks"
		);
	
		$options[] = array(
			"section" => "contact_info",
			"id"      => DRTHEME_CONTACT_PLUGIN_OPTIONS_VALUE. "_branch_address_input",
			"title"   => __( 'Branch Office Address', 'drtheme' ),
			"desc"    => __( 'A textarea for an address!', 'drtheme' ),
			"type"    => "textarea",
			"std"     => '',
			"class"	  =>"allowlinebreaks"
		);
		return $options;
	}
	
	/**
	 * Includes the user interface class and initializes it
	 *
	 * @since 1.0.0
	 * @see DrThemeContactInfoUI
	 * @return DrThemeContactInfoUI
	 */
	private function GetUI() {

		if($this->ui === null) {
			if(!class_exists('DrThemeContactInfoUI')) {
				if(!file_exists(trailingslashit(dirname(__FILE__)) . 'drplugin-ui.php')) return false;
					require_once(trailingslashit(dirname(__FILE__)) . 'drplugin-ui.php');
			}
			$this->ui = new DrThemeContactInfoUI($this);
		}

		return $this->ui;
	}
	/**
	 * Shows the option page of the general plugin. this function was basically the UI, afterwards the UI was outsourced to another class
	 *
	 * @see DrThemeContactInfo
	 * @since 1.0.0
	 * @return bool
	 */
	public function HtmlShowOptionsPageSettings() {

		$ui = $this->GetUI();
		if($ui) {
			$ui->HtmlShowOptionsPageSettings();
			return true;
		}

		return false;
	}
	/**
	 * Shows the option page of the plugin. this function was basically the UI, afterwards the UI was outsourced to another class
	 *
	 * @see DrThemeContactInfo
	 * @since 1.0.0
	 * @return bool
	 */
	public function HtmlShowOptionsPage() {

		$ui = $this->GetUI();
		if($ui) {
			$ui->HtmlShowOptionsPage();
			return true;
		}

		return false;
	}
}