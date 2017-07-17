<?php

/**
 * DrTheme Contact Info plugin: WP_Widget
 *
 * @Since 1.0.0
 * 
 */
 
 class DrThemeContactInfoWidget extends WP_Widget {
 	
 	public static function register() {
        register_widget( __CLASS__ );
    }
 	/**
	 * Sets up a new DrTheme ContactInfo plugin widget instance.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => DRTHEME_CONTACT_PLUGIN_BASENAME.'_Widget',
			'description' => __( 'Add a contact info to your sidebar.','drtheme' )
		);
		parent::__construct( DRTHEME_CONTACT_PLUGIN_BASENAME.'_Widget',__('Simple Contact Info','drtheme'), $widget_ops );
	}
	 
	/**
	 * Outputs the content for the current Contact Info widget instance.
	 *
	 * @since 1.0.0
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Custom Menu widget instance.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __('Contact us','drtheme_widget') : $instance['title'] );
		$email	= isset( $instance['email'] ) ? $instance['email'] : 'Email';
		$phone	= isset( $instance['phone'] ) ? $instance['phone'] : 'Phone Number';
		$wa		= isset( $instance['wa'] ) ? $instance['wa'] : 'WhatsApp';
		$bbm	= isset( $instance['bbm'] ) ? $instance['bbm'] : 'BBM';
		$office	= isset( $instance['office'] ) ? $instance['office'] : 'Head Office';
		$branch	= isset( $instance['branch'] ) ? $instance['branch'] : 'Branch Office';
		echo $args['before_widget'];
		if ( !empty($title) )
			echo $args['before_title'] . $title . $args['after_title'];
			echo do_shortcode('[ContactInfoShortCode email="'.$email.'" phone="'.$phone.'" wa="'.$wa.'" bbm="'.$bbm.'" office="'.$office.' branch="'.$branch.'""]');
		echo $args['after_widget'];
	}
	
	/**
	 * Outputs the settings form for the DrTheme Contact Info widget.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$title	= isset( $instance['title'] ) ? $instance['title'] : '';
		$email	= isset( $instance['email'] ) ? $instance['email'] : '';
		$phone	= isset( $instance['phone'] ) ? $instance['phone'] : '';
		$wa		= isset( $instance['wa'] ) ? $instance['wa'] : '';
		$bbm	= isset( $instance['bbm'] ) ? $instance['bbm'] : '';
		$office	= isset( $instance['office'] ) ? $instance['office'] : '';
		$branch	= isset( $instance['branch'] ) ? $instance['branch'] : '';
		?>
		<div class="nav-menu-widget-form-controls">
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','drtheme' ) ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php _e( 'Label for Email:','drtheme' ) ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" value="<?php echo esc_attr( $email ); ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'phone' ); ?>"><?php _e( 'Label for Phone:','drtheme' ) ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" value="<?php echo esc_attr( $phone ); ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'wa' ); ?>"><?php _e( 'Label for WA:','drtheme' ) ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'wa' ); ?>" name="<?php echo $this->get_field_name( 'wa' ); ?>" value="<?php echo esc_attr( $wa ); ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'bbm' ); ?>"><?php _e( 'Label for BBM:','drtheme' ) ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'bbm' ); ?>" name="<?php echo $this->get_field_name( 'bbm' ); ?>" value="<?php echo esc_attr( $bbm ); ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'office' ); ?>"><?php _e( 'Label for Office:','drtheme' ) ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'office' ); ?>" name="<?php echo $this->get_field_name( 'office' ); ?>" value="<?php echo esc_attr( $office ); ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'branch' ); ?>"><?php _e( 'Label for Branch:','drtheme' ) ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'branch' ); ?>" name="<?php echo $this->get_field_name( 'branch' ); ?>" value="<?php echo esc_attr( $branch ); ?>"/>
			</p>
		</div>
		<?php
	}
	
	/**
	 * Handles updating settings for the current Contact Info widget instance.
	 *
	 * @since 1.0.0
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		
		if ( ! empty( $new_instance['title'] ) ) {
			$instance['title'] = sanitize_text_field( $new_instance['title'] );
		}
		if ( ! empty( $new_instance['email'] ) ) {
			$instance['email'] = sanitize_text_field( $new_instance['email'] );
		}
		if ( ! empty( $new_instance['phone'] ) ) {
			$instance['phone'] = sanitize_text_field( $new_instance['phone'] );
		}
		if ( ! empty( $new_instance['wa'] ) ) {
			$instance['wa'] = sanitize_text_field( $new_instance['wa'] );
		}
		if ( ! empty( $new_instance['bbm'] ) ) {
			$instance['bbm'] = sanitize_text_field( $new_instance['bbm'] );
		}
		if ( ! empty( $new_instance['office'] ) ) {
			$instance['office'] = sanitize_text_field( $new_instance['office'] );
		}
		if ( ! empty( $new_instance['branch'] ) ) {
			$instance['branch'] = sanitize_text_field( $new_instance['branch'] );
		}
		return $instance;
	}
 }
