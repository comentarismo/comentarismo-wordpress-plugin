<?php

/*
    Plugin Name: Comentarismo Comment System
    Plugin URI: https://comentarismo.com/
    Description: The Comentarismo comment system replaces your WordPress comment system with your comments hosted and powered by Comentarismo. Head over to the Comments admin page to set up your Comentarismo Comment System.
    Version: 1.0.0
	License: (CreativeMindsSolutions) GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html

 */

if ( !function_exists( 'add_action' ) ) {
	echo 'Error, cant call plugin directly.';
	exit;
}

if ( !class_exists( 'ComentarismoWP' ) ) {

	class ComentarismoWP {

		protected static $instance = NULL;
		public static $version = '1.0';

		public static $plugin_text_domain = 'cm-handfsl';
		public static $plugin_slug = 'cm-handfsl';
		public static $plugin_name = 'Comentarismo';
		public static $plugin_user_guide = 'https://api.comentarismo.com/';

		/**
		 * The filesystem directory path (with trailing slash) for the plugin directory.
		 *
		 * @since 1.0
		 * @var string $plugin_dir_path
		 */
		public static $plugin_dir_path = NULL;

		/**
		 * The URL (with trailing slash) for the plugin directory.
		 *
		 * @since 1.0
		 * @var string $plugin_dir_url
		 */

		public static $plugin_dir_url = NULL;
		/**
		 * The relative path to a plugin directory (without the leading and trailing slashes).
		 *
		 * @since 1.0
		 * @var string $plugin_basename
		 */

		public static $plugin_basename = NULL;

		/**
		 * The full path and filename of the plugin main file.
		 *
		 * @since 1.0
		 * @var string $plugin_file
		 */
		public static $plugin_file = NULL;

		public function __construct() {

			$this->setVariables();

			// Load plugin textdomain
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
			// Multisite support, activate plugin on new blogs if $networkwide
			add_action( 'wpmu_new_blog', array( $this, 'activate_by_new_blog' ) , 10, 6 );

				include_once self::$plugin_dir_path . '/package/cminds-free.php';

			// Include backend class if admin area
			if ( is_admin() ) {

				include_once self::$plugin_dir_path . '/classes/class.cm-handfsl-backend.php';
				$ComentarismoWPBackend = ComentarismoWPBackend::instance();

			// Include frontend class otherwise, but only if license key is fine
			} else {

				include_once self::$plugin_dir_path . '/classes/class.cm-handfsl-frontend.php';
				$ComentarismoWPFrontend = ComentarismoWPFrontend::instance();

			}
		}

		/**
		 * Main Instance
		 *
		 * Insures that only one instance of class exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 * TODO: multisite support?
		 *
		 * @access public
		 * @since 1.0
		 */
		public static function instance() {

			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Plugin activation hook, triggers plugin_activation() method
		 *
		 * @access public
		 * @since 1.0
		 */
		public static function activate( $networkwide ) {
			do_action( self::$plugin_slug . '_activate' );
			self::install( 'plugin_activation', $networkwide );
		}

		/**
		 * Plugin deactivation hook, triggers plugin_deactivation() method
		 *
		 * @access public
		 * @since 1.0
		 */
		public static function deactivate( $networkwide ) {
			do_action( self::$plugin_slug . '_deactivate' );
			self::install( 'plugin_deactivation', $networkwide );
		}

		/**
		 * Plugin installation
		 *
		 * @access public
		 * @since 1.0
		 *
		 * @global type $wpdb
		 *
		 * @param type $networkwide
		 * @param string $callback - callback function name
		 */
		public static function install( $callback, $networkwide ) {

			global $wpdb;

			if( function_exists('is_multisite')
			&& is_multisite() )
			{
				// check if it is a network activation - if so, run the activation function for each blog id
				if( $networkwide )
				{
					$old_blog = $wpdb->blogid;
					// Get all blog ids
					$blog_ids = $wpdb->get_col( $wpdb->prepare( "SELECT blog_id FROM {$wpdb->blogs}") );
					foreach( $blog_ids as $blog_id )
					{
						switch_to_blog( $blog_id );
						call_user_func( array ( __CLASS__ , $callback ), $networkwide );
					}
					switch_to_blog( $old_blog );
					return;
				}
			}

			call_user_func( array ( __CLASS__ , $callback ), $networkwide );
		}

		/**
		 * Activate plugin while adding new blog to network, if it is $networkwide
		 *
		 * @access public
		 * @since 1.0
		 */
		public function activate_by_new_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {

			global $wpdb;

			if ( is_plugin_active_for_network( self::$plugin_basename ))
			{
				$old_blog = $wpdb->blogid;
				switch_to_blog( $blog_id );
				do_action( 'activate_' . self::$plugin_basename, false );
				do_action( 'activate_plugin', $plugin, false );
				switch_to_blog( $old_blog );
			}

		}

		/**
		 * Plugin deactivation
		 *
		 * @access public
		 * @since 1.0
		 */
		public static function plugin_activation() {
			// Code need to be executed on plugin activation
			do_action( self::$plugin_slug . '_plugin_activation' );
			return;
		}

		/**
		 * Plugin deactivation
		 *
		 * @access public
		 * @since 1.0
		 */
		public static function plugin_deactivation() {
			// Code need to be executed on plugin deactivation
			do_action( self::$plugin_slug . '_plugin_deactivation' );
			return;
		}

		/**
		 * Plugin unstallation
		 *
		 * @access public
		 * @since 1.0
		 */
		public function plugin_uninstall() {
			// Code need to be executed on plugin uninstall
			do_action( self::$plugin_slug . '_plugin_uninstall' );
			return;
		}

		/**
		 * Setup plugin variables
		 *
		 * @access private
		 * @since 1.0
		 */
		private function setVariables() {

			self::$plugin_dir_path = plugin_dir_path( __FILE__ );
			self::$plugin_dir_url = plugin_dir_url( __FILE__ );
			self::$plugin_basename = plugin_basename( __FILE__ );
			self::$plugin_file = __FILE__;

		}

		/**
		 * Load plugin textdomain
		 *
		 * @access public
		 * @since 1.0
		 */
		public function load_plugin_textdomain() {

			$domain = self::$plugin_text_domain;
			$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

			load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
			load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages' );

		}

	}
}

register_activation_hook( __FILE__, array( 'ComentarismoWP', 'activate' ));
register_deactivation_hook( __FILE__, array( 'ComentarismoWP', 'deactivate' ));

add_action( 'plugins_loaded', array( 'ComentarismoWP', 'instance' ));