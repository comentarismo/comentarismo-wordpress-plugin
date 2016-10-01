<?php

$cminds_plugin_config = array(
	'plugin-is-pro'				 => FALSE,
	'plugin-is-addon'			 => FALSE,
	'plugin-version'			 => '1.0.7',
	'plugin-abbrev'				 => 'cmhandfsl',
	'plugin-file'				 => ComentarismoWP::$plugin_file,
	'plugin-dir-path'			 => plugin_dir_path( ComentarismoWP::$plugin_file ),
	'plugin-dir-url'			 => plugin_dir_url( ComentarismoWP::$plugin_file ),
	'plugin-basename'			 => plugin_basename( ComentarismoWP::$plugin_file ),
    'plugin-show-guide'                 => TRUE,
     'plugin-guide-text'                 => '    <div style="display:block">
        <ol>
         <li>Go to the plugin <strong>"Setting"</strong></li>
         <li>Add JavaScript or CSS code and choose if this will appear in the header or footer. </li>
         <li>You can also restrict if to choose this on pages or posts only</li>
        <li> You can add additional codes or pause specific code based on your needs.</li>
         </ol>
    </div>',
     'plugin-guide-video-height'         => 240,
     'plugin-guide-videos'               => array(
          array( 'title' => 'Installation tutorial', 'video_id' => '162714982' ),
     ),
	'plugin-icon'				 => '',
    'plugin-affiliate'               => '',
    'plugin-redirect-after-install'  => admin_url( 'admin.php?page=cm-handfsl' ),
	'plugin-name'				 => ComentarismoWP::$plugin_name,
	'plugin-license-name'		 => ComentarismoWP::$plugin_name,
	'plugin-slug'				 => '',
	'plugin-short-slug'			 => 'ecommerce-tracking',
	'plugin-menu-item'			 => ComentarismoWP::$plugin_slug,
	'plugin-textdomain'			 => ComentarismoWP::$plugin_text_domain,
	'plugin-userguide-key'		 => '452-header-and-footer-script-loader',
	'plugin-store-url'			 => 'https://www.comentarismo.com/wordpress-plugins-library/cm-header-and-footer-script-loader-plugin-for-wordpress',
	'plugin-support-url'		 => 'https://wordpress.org/support/plugin/comentarismo-wp-plugin',
	'plugin-review-url'			 => 'https://wordpress.org/support/view/comentarismo-wp-plugin',
	'plugin-changelog-url'		 => 'https://www.comentarismo.com/comentarismo-wp-plugin/#changelog',
	'plugin-licensing-aliases'	 => array(),
	'plugin-compare-table'	 => '<div class="pricing-table" id="pricing-table">
                <ul>
                    <li class="heading">Current Edition</li>
                    <li class="price">$0.00</li>
                    <li class="noaction"><span>Free Download</span></li>
                    <li>Unlimited scripts and styles</li>
                    <li>Support Post and pages</li>
                    <li>X</li>
                    <li>X</li>
                  
                 <li class="price">$0.00</li>
                    <li class="noaction"><span>Free Download</span></li>
                </ul>

                <ul>
                    <li class="heading">Pro</li>
                    <li class="price">$29.00</li>
                    <li class="action"><a href="https://www.comentarismo.com/wordpress-plugins-library/cm-header-and-footer-script-loader-plugin-for-wordpress" style="background-color:darkblue;" target="_blank">More Info</a> &nbsp;&nbsp;<a href="https://www.comentarismo.com/?edd_action=add_to_cart&download_id=59678&wp_referrer=https://www.comentarismo.com/checkout/&edd_options[price_id]=1" target="_blank">Buy Now</a></li>
                    <li>Unlimited scripts and styles</li>
                    <li>Support Post and pages</li>
                    <li>Support All Custom Posts</li>
                    <li>Control script loading per specific post</li>
                    <li class="price">$29.00</li>
                    <li class="action"><a href="https://www.comentarismo.com/wordpress-plugins-library/cm-header-and-footer-script-loader-plugin-for-wordpress" style="background-color:darkblue;" target="_blank">More Info</a> &nbsp;&nbsp;<a href="https://www.comentarismo.com/?edd_action=add_to_cart&download_id=59678&wp_referrer=https://www.comentarismo.com/checkout/&edd_options[price_id]=1" target="_blank">Buy Now</a></li>
                </ul>

            </div>',
);