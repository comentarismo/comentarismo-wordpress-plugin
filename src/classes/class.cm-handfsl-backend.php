<?php

// Make sure we don't expose any info if called directly
if ( ! function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

/**
 * Class for plugin backend.
 * @author CreativeMindsSolutions - Maciej Stróżyński
 */

if ( !class_exists( 'ComentarismoWPBackend' ) ) {

	class ComentarismoWPBackend {

		protected static $instance = NULL;

		public function __construct() {

			// Including scripts and styles for admin area
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

			// Adding plugin menu to admin menu
			add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );

			// Handling ajax request for unique script ID (triggered on Add New script button)
			add_action( 'wp_ajax_' . ComentarismoWP::$plugin_slug . '_get_unique_id', array( $this, 'ajax_get_unique_id' ));
		}

		/**
		 * Main Instance
		 *
		 * Insures that only one instance of class exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access public
		 * @since 1.0
		 */
		public static function instance() {

			if ( null == self::$instance )
			{
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Load plugin styles for admin area
		 *
		 * @access public
		 * @since 1.0
		 */
		public function enqueue_admin_styles() {

			$screen = get_current_screen();

			if ( ! isset( $screen->id ) || strpos( $screen->base, 'cm-handfsl' ) === false )
			{
				return;
			}

			// Including plugin style file
			wp_enqueue_style( 'cm-handfsl-admin-styles', plugins_url( 'css/admin.css', ComentarismoWP::$plugin_file ), array(), ComentarismoWP::$version );
		}

		/**
		 * Load plugin scripts for admin area
		 *
		 * @access public
		 * @since 1.0
		 */
		public function enqueue_admin_scripts() {

			$screen = get_current_screen();

			if ( ! isset( $screen->id ) || strpos( $screen->base, 'cm-handfsl' ) === false )
			{
				return;
			}

			// Registering jQuery Repeater script and plugin script
			wp_register_script( 'jquery-repeater', plugins_url( 'js/jquery.repeater.min.js', ComentarismoWP::$plugin_file ), array( 'jquery' ), '1.0.0' );
			wp_register_script( 'cm-handfsl-admin-script', plugins_url( 'js/admin.js', ComentarismoWP::$plugin_file ), array( 'jquery' ), ComentarismoWP::$version );

			// Including plugin script file
			wp_enqueue_script( 'cm-handfsl-admin-script' );

			// Localizing plugin script (passing variables from PHP code to JS code)
			wp_localize_script( 'cm-handfsl-admin-script', 'ajax_cm_handfsl_vars', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'get_unique_id_nonce' => wp_create_nonce( ComentarismoWP::$plugin_slug . '_ajax_get_unique_id' ),
				'get_unique_id_action' => ComentarismoWP::$plugin_slug . '_get_unique_id'
			));
		}

		/**
		 * Creating plugin menu
		 *
		 * @access public
		 * @since 1.0
		 */
		public function add_menu_pages() {

			global $submenu;

			// Adding plugin menu to admin area menu
			add_menu_page( __( 'Scripts & Styles', ComentarismoWP::$plugin_text_domain ), ComentarismoWP::$plugin_name, 'manage_options', ComentarismoWP::$plugin_slug, '');

			// Adding Settings subpage to plugin menu
			add_submenu_page( ComentarismoWP::$plugin_slug, __( 'Settings', ComentarismoWP::$plugin_text_domain ),	__( 'Settings', ComentarismoWP::$plugin_text_domain ),	'manage_options', ComentarismoWP::$plugin_slug,				array( $this, 'callbackAdminPage'));

			// Filter for submenu
			if( has_filter( ComentarismoWP::$plugin_slug . '_submenu' ) )
			{
				$submenu = apply_filters( ComentarismoWP::$plugin_slug . '_submenu', $submenu );
			}

		}

		/**
		 * Generating unordered list of checkboxes for each custom post type
		 *
		 * @access public
		 * @since 1.0
		 *
		 * @param array $args - parameterd for get_post_types()
		 * @param mixed empty/array $selected - array of already selected custom post types
		 *
		 * @return string - unordered list of checkboxes and custom post types names, or empty string
		 */
		public function get_custom_post_types_checkboxes( $args, $selected = '' ) {
			// TODO: move elsewhere? some seperate class/function file for further use

			// Default cpt parameters
			$args_default = array(
				'public' => true,
				'_builtin' => true
			);

			$result = '';

			// Filter for $args
			if( has_filter( ComentarismoWP::$plugin_slug . '_get_custom_post_types_checkboxes_args' ) )
			{
				$args = apply_filters( ComentarismoWP::$plugin_slug . '_get_custom_post_types_checkboxes_args', $args );
			}

			// Filter for $args_default
			if( has_filter( ComentarismoWP::$plugin_slug . '_get_custom_post_types_checkboxes_default' ) )
			{
				$args_default = apply_filters( ComentarismoWP::$plugin_slug . '_get_custom_post_types_checkboxes_default', $args_default );
			}

			// Merging default args with those passed as paremeter
			$args = array_merge( $args_default, $args );

			// Get custom post types by $args
			$post_types = get_post_types( $args, 'objects', 'and' );

			// If there are custom post types for $args conditions
			if( isset( $post_types )
			&& ! empty( $post_types ) )
			{
				$result = '<ul class="custom_post_types_checkboxes">';
				// Generate list of checkboxes for each custom post type
				foreach( $post_types as $post_type )
				{
					if( $post_type->name === 'attachment' )
						continue;

					$result .= '<li><input type="checkbox" value="' . $post_type->name . '" name="item-load-cpt" ' . $this->checked_array( $selected, $post_type->name, false ) . ' />' . $post_type->labels->singular_name . '</li>';
				}

				$result .= '</ul>';
			}

			// Filter for list of checkboxes output
			if( has_filter( ComentarismoWP::$plugin_slug . '_get_custom_post_types_checkboxes_output' ) )
			{
				$result = apply_filters( ComentarismoWP::$plugin_slug . '_get_custom_post_types_checkboxes_output', $result );
			}

			return $result;
		}

		/**
		 * Checked function for arrays
		 *
		 * @access public
		 * @since 1.0
		 *
		 * @param array $args - array of already selected custom post types
		 * @param string $current - current post type name
		 * @param boolean $echo - whether to echo or just return value
		 *
		 * @return string - checked='checked' or empty string
		 */
		public function checked_array( $array, $current, $echo = true ) {
			// TODO: move elsewhere?

			if( is_array( $array ) )
			{
				if( in_array( $current, $array ) )
				{
					$current = $array = 1;

					if( $echo === false ){
						return checked( $array, $current, $echo );
					}

					checked( $array, $current, $echo );
				} else {
					return '';
				}

			} else {
				checked( $array, $current, $echo );
			}

		}

		/**
		 * Save plugins settings
		 *
		 * @access private
		 * @since 1.0
		 */
		private function save_item_form(){

			// Check the user's permissions.
			if ( ! current_user_can( 'manage_options' ) )
				return false;

			// Confirm form submit and nonce for security reasons
			if( isset( $_POST[ 'add_item_form_submit' ] )
				&& $_POST[ 'add_item_form_submit' ] === 'form_submit'
				&& isset( $_POST[ComentarismoWP::$plugin_slug . '_save_scripts_nonce'] )
				&& wp_verify_nonce( $_POST[ComentarismoWP::$plugin_slug . '_save_scripts_nonce'], ComentarismoWP::$plugin_slug . '_save_scripts' ) )
			{

				$scripts = array();

				// Filter for pre loop of scripts sent by form
				if( has_filter( ComentarismoWP::$plugin_slug . '_save_item_form_pre_scripts' ) )
				{
					$_POST[ 'form-item'] = apply_filters( ComentarismoWP::$plugin_slug . '_save_item_form_pre_scripts', $_POST[ 'form-item'] );
				}

				// Check if any script was sent by form
				if( isset( $_POST[ 'form-item'] )
				&& is_array( $_POST[ 'form-item'] ) )
				{
					// For each sent script
					foreach( $_POST[ 'form-item'] as $key => $item )
					{
						// Execute only if there is script name and script code
						if( isset( $item[ 'item-code' ] )
							&& $item[ 'item-code' ] != 'Script Code'
							&& ! empty( $item[ 'item-code' ] ) )
						{

							// A little protection from ID duplicates (if getting unique ID by JS fails)
							while( isset( $scripts[ $item[ 'item-ID' ] ] )
							&& ! empty( $scripts[ $item[ 'item-ID' ] ] ) )
							{
								$item[ 'item-ID' ] = (int)$item[ 'item-ID' ] + 1;
							}

							$item_type = $item_destination = $item_load = '';
							$item_load_cpt = array();

							// Setting script parameters as variables + sanitization values
							$item_ID = intval( $item[ 'item-ID' ] );
							$item_name = 'script';
						//	$item_code = sanitize_text_field( $item[ 'item-code' ] );
							$item_code = json_encode( $item[ 'item-code' ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE );

							// Make sure it is compatible for all PHP versions and server configurations
							if( ! get_magic_quotes_gpc() )
							{
								// $item_code = addslashes( $item_code );
							}

							if( isset( $item[ 'item-type' ] ) && in_array( $item[ 'item-type' ], array( 'js', 'css' ) ) )
							{
								$item_type = sanitize_text_field( $item[ 'item-type' ] );
							}

							if( isset( $item[ 'item-destination' ] ) && in_array( $item[ 'item-destination' ], array( 'header', 'footer' ) ) )
							{
								$item_destination = sanitize_text_field( $item[ 'item-destination' ] );
							}

							if( isset( $item[ 'item-load' ] ) && in_array( $item[ 'item-load' ], array( 'all', 'custom', 'off' ) ) )
							{
								$item_load = sanitize_text_field( $item[ 'item-load' ] );
							}

							if( isset( $item[ 'item-load-cpt' ] ) )
							{
								$item_load_cpt = array_map( 'sanitize_text_field', $item[ 'item-load-cpt' ] );
							}

							// Add script to $scripts array
							$scripts[$item_ID] = array(
								'item_ID' => $item_ID,
								'item_name' => $item_name,
								'item_code' => $item_code,
								'item_type' => $item_type,
								'item_destination' => $item_destination,
								'item_load' => $item_load,
								'item_load_cpt' => $item_load_cpt
							);

						}
					}
				}

				// Filter for scripts before adding saving them into database
				if( has_filter( ComentarismoWP::$plugin_slug . '_save_item_form_scripts' ) )
				{
					$scripts = apply_filters( ComentarismoWP::$plugin_slug . '_save_item_form_scripts', $scripts );
				}

				// Save serialized $scripts array into database
				$scripts = maybe_serialize( $scripts );
				update_option( ComentarismoWP::$plugin_slug . '_scripts', $scripts, 'yes' );

			}
		}

		/**
		 * Generates unique ID for script
		 *
		 * @access public
		 * @since 1.0
		 */
		public function get_unique_id(){

			$ID = 1;

			// Save $scripts array from database and unserialize it
			$scripts = get_option( ComentarismoWP::$plugin_slug . '_scripts' );
			$scripts = maybe_unserialize( $scripts );

			if( isset( $scripts )
			&& is_array( $scripts )
			&& ! empty( $scripts ) )
			{

				// Get last ID(key) and increment it
				$last = max( array_keys( $scripts ) );
				$ID = (int)$last + 1;

			}

			return $ID;
		}

		/**
		 * Generates unique ID for script for ajax request
		 *
		 * @access public
		 * @since 1.0
		 */
		public function ajax_get_unique_id(){

			// Checking if it's ajax request
			if( defined( 'DOING_AJAX' )
			&& DOING_AJAX )
			{

				// Checking nonce for security reasons
				if( ! isset( $_POST['nonce'] )
				|| ! wp_verify_nonce( $_POST['nonce'], ComentarismoWP::$plugin_slug . '_ajax_get_unique_id' ) )
				{
					wp_die();
				}

				echo $this->get_unique_id();
			}

			wp_die();
		}


		/**
		 * Generates horizontal navigation for plugin subpages
		 *
		 * @access public
		 * @since 1.0
		 */
		public static function getAdminNav() {

			global $self, $parent_file, $submenu_file, $plugin_page, $typenow, $submenu;

			$submenus = array();
			$menuItem = ComentarismoWP::$plugin_slug;

			ob_start();

			if( isset( $submenu[$menuItem] ) )
			{

				$thisMenu = $submenu[$menuItem];

				foreach( $thisMenu as $sub_item )
				{

					$slug = $sub_item[2];

					// Handle current for post_type=post|page|foo pages, which won't match $self.
					$self_type = !empty($typenow) ? $self . '?post_type=' . $typenow : 'nothing';

					$isCurrent = FALSE;

					$subpageUrl = get_admin_url( '', 'admin.php?page=' . $slug );

					if(	( !isset( $plugin_page )
						&& $self == $slug)
							|| ( isset( $plugin_page )
								&& $plugin_page == $slug
								&& ( $menuItem == $self_type
									|| $menuItem == $self
									|| file_exists( $menuItem ) === false )))
					{
						$isCurrent = TRUE;
					}

					$url = ( strpos( $slug, '.php' ) !== false || strpos( $slug, 'http://' ) !== false || strpos( $slug, 'https://' ) !== false ) ? $slug : $subpageUrl;

					$isExternal = ( $slug === $url ) ? TRUE : FALSE;

					$submenus[] = array(
						'link' => $url,
						'title' => $sub_item[0],
						'current' => $isCurrent,
						'external' => $isExternal
					);
				}

				include ComentarismoWP::$plugin_dir_path . 'views/nav.phtml';
			}

			$nav = ob_get_contents();
			ob_end_clean();
			return $nav;
		}

		/**
		 * Callback function to generate plugin admin area subpages
		 *
		 * @access public
		 * @since 1.0
		 */
		public function callbackAdminPage() {

			global $wpdb;
			$pageId = filter_input( INPUT_GET, 'page' );

			$content = '';
			$title = '';

			switch( $pageId )
			{
				case ComentarismoWP::$plugin_slug:
				{

					// If save changes button was submitted, trigger save_item_form() method
					if( isset( $_POST[ 'add_item_form_submit' ] )
						&& $_POST[ 'add_item_form_submit' ] === 'form_submit' )
					{
						do_action( ComentarismoWP::$plugin_slug . '_save_item_form' );
						$this->save_item_form( $_POST );
					}

					// If clean database button was submitted, trigger cleanup() method
					if( isset( $_POST[ComentarismoWP::$plugin_slug . '_cleanup'] )
					&& isset( $_POST[ComentarismoWP::$plugin_slug . '_cleanup_nonce'] )
					&& wp_verify_nonce( $_POST[ComentarismoWP::$plugin_slug . '_cleanup_nonce'], ComentarismoWP::$plugin_slug . '_cleanup' ) )
					{
						do_action( ComentarismoWP::$plugin_slug . '_cleanup' );
						$this->cleanup();
					}

					// Include jQuery Repeater library
					wp_enqueue_script( 'jquery-repeater' );

					$title = __('Settings');
					ob_start();
					require_once ComentarismoWP::$plugin_dir_path . 'views/settings.phtml';
					$content = ob_get_contents();
					ob_end_clean();
					break;
				}
				case ComentarismoWP::$plugin_slug . '-about':
				{
					$title = __('About');
					ob_start();
					require_once ComentarismoWP::$plugin_dir_path . 'views/about.phtml';
					$content = ob_get_contents();
					ob_end_clean();
					break;
				}
				case ComentarismoWP::$plugin_slug . '-pro':
				{
					$title = __('Pro');
					ob_start();
					require_once ComentarismoWP::$plugin_dir_path . 'views/pro.phtml';
					$content = ob_get_contents();
					ob_end_clean();
					break;
				}
			}

			$this->renderAdminPage( $content, $title );
		}

		/**
		 * Render plugin admin area subpages
		 *
		 * @access public
		 * @since 1.0
		 *
		 * @param mixed string/boolean $content - The subpage content.
		 * @param string $title - The subpage title.
		 */
		public function renderAdminPage( $content, $title ) {
			$nav = self::getAdminNav();
			include_once ComentarismoWP::$plugin_dir_path . 'views/template.phtml';
		}

		/**
		 * Removes all plugin data stored inside database
		 *
		 * @access protected
		 * @since 1.0
		 */
		protected function cleanup() {

			// Check the user's permissions.
			if ( ! current_user_can( 'manage_options' ) )
				return;

			global $wpdb;

			/*
			 * Remove the data from the other tables
			 */
			do_action('cmodsar_do_cleanup');

			/*
			 * Remove the options
			 */
			delete_option( ComentarismoWP::$plugin_slug . '_scripts' );

			/*
			 * Remove custom post meta
			 */
			$meta_key = ComentarismoWP::$plugin_slug . '_scripts_custom';
			$posts_meta = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE meta_key = '$meta_key'" );

			foreach ( $posts_meta as $post_meta ) {
				delete_post_meta( $post_meta->post_id, ComentarismoWP::$plugin_slug . '_scripts_custom' );
			}

			// $wpdb->query( "DELETE FROM $wpdb->postmeta WHERE meta_key = '$meta_key'" );

		}

	}

}