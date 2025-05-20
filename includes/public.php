<?php
/**
 * Frontend functionality for the Dynamic Multilang Footer Loader plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Get the current language footer page ID.
 *
 * @return int|null Footer page ID or null if not set.
 */
function get_dynamic_footer_page_id() {
    $main_page_id = get_option('custom_footer_main_page_id');
    if (!$main_page_id) return null;

    if (function_exists('pll_get_post')) {
        $lang = function_exists('pll_current_language') ? pll_current_language() : pll_default_language();
        $footer_id = pll_get_post($main_page_id, $lang);
        if ($footer_id) return $footer_id;
    }

    return $main_page_id;
}

// Inject the dynamic footer content + scoped Spectra styles
add_action('wp_footer', function () {
    $footer_page_id = get_dynamic_footer_page_id();
    if (!$footer_page_id) return;

    $footer_post = get_post($footer_page_id);
    if (!$footer_post) return;

    $content = $footer_post->post_content;
    $rendered = do_blocks($content);

    echo '<div id="wp-custom-dynamic-footer-2">' . $rendered . '</div>';

    // Load Spectra CSS and scope it
    $uag_folder = floor($footer_page_id / 1000) * 1000;
    $uag_css_path = "/uag-plugin/assets/{$uag_folder}/uag-css-{$footer_page_id}.css";
    $uag_css_full_path = WP_CONTENT_DIR . '/uploads' . $uag_css_path;

    if (file_exists($uag_css_full_path)) {
        $raw_css = file_get_contents($uag_css_full_path);

        // Prefix all selectors with the container to scope styles
        $scoped_css = preg_replace_callback('/(^|}|;)\s*([^{@}]+)\s*{/', function ($matches) {
            $prefix = '#wp-custom-dynamic-footer-2 ';
            $selectors = explode(',', $matches[2]);
            $scoped_selectors = array_map(function ($selector) use ($prefix) {
                $selector = trim($selector);
                if (empty($selector)) return '';
                if (strpos($selector, '@') === 0 || strpos($selector, ':root') === 0) return $selector;
                if (strpos($selector, $prefix) === 0) return $selector;
                return $prefix . $selector;
            }, $selectors);
            return $matches[1] . implode(', ', $scoped_selectors) . ' {';
        }, $raw_css);

        // Minify the CSS
        $scoped_css = preg_replace('!/\*.*?\*/!s', '', $scoped_css);
        $scoped_css = preg_replace('/\s+/', ' ', $scoped_css);
        $scoped_css = trim($scoped_css);

        echo '<style id="scoped-uag-footer-css">' . $scoped_css . '</style>';
    }
}, 999);