<?php
/*
 * Plugin Name: Custom Multilang Footer
 * Description: Display a Gutenberg+Spectra-designed page as your footer. Loads styles/scripts and supports Polylang.
 * Plugin URI: https://sunnyhossain.com
 * Version: 1.2.4
 * Author: Sunny Hossain
 * Author URI: https://sunnyhossain.com
 * Text Domain: custom-multilang-footer
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$plugin_name = plugin_basename( __FILE__ );

require_once( 'includes/admin.php' );
require_once( 'includes/public.php' );
require_once( 'includes/uag-loader.php' );


add_filter( "plugin_action_links_$plugin_name", 'dmfl_plugin_settings_url' );



// Define Plugin Settings Link
function dmfl_plugin_settings_url( $links ) {
    // Settings link.
    $settings_link = '<a href="edit.php?post_type=st_events&page=st-event-settings">' . esc_attr__( 'Settings', 'custom-multilang-footer' ) . '</a>';

    array_unshift( $links, $settings_link );
    if ( ! function_exists( 'dmfl_plugin_settings_url' ) ) {
        array_unshift( $links );
    }
    return $links;
}
