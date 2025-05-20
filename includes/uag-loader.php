<?php
/**
 * UAG Enqueue Scripts Loader
 * Enqueue UAG JavaScript if Spectra blocks exist in the footer page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Enqueue UAG JavaScript if Spectra blocks exist
add_action('wp_enqueue_scripts', function () {
    if (is_admin()) return;

    $footer_page_id = get_dynamic_footer_page_id();
    if (!$footer_page_id) return;

    $footer_post = get_post($footer_page_id);
    if ($footer_post && strpos($footer_post->post_content, 'uag-') !== false) {
        wp_enqueue_script(
            'uag-frontend-js',
            content_url('/uploads/uag-plugin/assets/js/uag-js.js'),
            [],
            null,
            true
        );
    }
});
