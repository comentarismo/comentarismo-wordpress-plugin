<?php

// Make sure we don't expose any info if called directly
if ( ! function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

if ( ! class_exists( 'ComentarismoWPFrontend' ) ) {

	class ComentarismoWPFrontend {

		protected static $instance = NULL;

		public function __construct() {

			add_action( 'wp_head', array( $this, 'hook_scripts' ) );
			add_action( 'wp_footer', array( $this, 'hook_scripts' ) );

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

			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Load necessary scripts/styles to header or footer area - according to plugin/script settings
		 *
		 * @access public
		 * @since 1.0
		 */
		public function hook_scripts() {

			global $post;
			$output = '';

			$scripts = get_option( ComentarismoWP::$plugin_slug . '_scripts' );
			$scripts = maybe_unserialize( $scripts );

			$post_meta = get_post_meta( $post->ID, ComentarismoWP::$plugin_slug . '_scripts_custom', true );
			$post_meta = maybe_unserialize( $post_meta );

			// Filter for pre script settings
			if( has_filter( ComentarismoWP::$plugin_slug . '_hook_scripts_settings' ) )
			{
				$post_meta = apply_filters( ComentarismoWP::$plugin_slug . '_hook_scripts_settings', $post_meta );
			}

			// Filter for pre scrips
			if( has_filter( ComentarismoWP::$plugin_slug . '_hook_scripts_scrips' ) )
			{
				$scripts = apply_filters( ComentarismoWP::$plugin_slug . '_hook_scripts_scrips', $scripts );
			}

			// Checking if there is any script
			if( isset( $scripts )
			&& is_array( $scripts )
			&& ! empty( $scripts ) )
			{

				foreach( $scripts as $key => $script ){

					// Checking if script should be loaded on header or footer
					if( isset( $script['item_destination'] )
					&& ( $script['item_destination'] === 'header'
						&& current_filter() === 'wp_head' )
						|| ( $script['item_destination'] === 'footer'
						&& current_filter() === 'wp_footer' ))
					{

						// Checking if this script is not excluded for current post/page
						if( isset( $post_meta['script_ID[' . $key . ']'] )
						&& $post_meta['script_ID[' . $key . ']'] != 'off'
						|| empty( $post_meta['script_ID[' . $key . ']'] ) )
						{

							// Checking if this script is included for current post/page
							if( isset( $post_meta['script_ID[' . $key . ']'] )
							&& $post_meta['script_ID[' . $key . ']'] === 'on' )
							{
								$output .= $this->add_script( $script['item_type'], $script['item_code'] );
								continue;
							}

							// Checking if script should be loaded on current post type
							if( isset( $script['item_load'] )
							&& ( $script['item_load'] === 'custom'
								&& isset( $script['item_load_cpt'] )
								&& in_array( get_post_type( $post->ID ), $script['item_load_cpt'] )
								&& is_singular( get_post_type( $post->ID ) )))
							{
								$output .= $this->add_script( $script['item_type'], $script['item_code'] );
								continue;
							}

							// Checking if script should be loaded on all pages
							if( isset( $script['item_load'] )
							&& ( $script['item_load'] === 'all' ))
							{
								$output .= $this->add_script( $script['item_type'], $script['item_code'] );
								continue;
							}

						}
					}
				}
			}

			// Filter for scripts html output
			if( has_filter( ComentarismoWP::$plugin_slug . '_hook_scripts_output' ) )
			{
				$output = apply_filters( ComentarismoWP::$plugin_slug . '_hook_scripts_output', $output );
            }

            $pos = strrpos($output, "%COMENTARISMO_PAGE%");
            if ($pos !== false) {
                $output = str_replace("%COMENTARISMO_PAGE%", get_permalink(), $output);
            }

            echo $output;

		}

		/**
		 * Generate scripts/styles HTML code
		 *
		 * @access public
		 * @since 1.0
		 */
		public function add_script( $type, $code ) {

			$code = stripslashes( json_decode( $code ) );
			$temp_code = preg_replace( '/\s+/S', '', $code );

			if( $type === 'css' )
			{
				if( strpos( $temp_code, '<style') === false && strpos( $temp_code, '</style>') === false )
					return '<style type="text/css">' . $code . '</style>';
				else
					return $code;
			}

			if( $type === 'js' )
			{
				if( strpos( $temp_code, '<script') === false && strpos( $temp_code, '</script>') === false )
					return '<script type="text/javascript">' . $code . '</script>';
				else
					return $code;
			}

		}

	}

}