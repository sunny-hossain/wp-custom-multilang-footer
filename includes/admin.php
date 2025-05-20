<?php
/**
 * Dynamic Multilang Footer Loader
 *
 * @package   Dynamic Multilang Footer Loader
 * @author    Sunny Hossain
 * @license   GPL-2.0+
 * @link      https://sunnyhossain.com
 */


class CMF_Submenu_Page {
    /**
     * Constructor
     * @return void
     */
	public function __construct() {
		add_action( 'admin_menu', array($this, 'register_submenu') );
	}

	/**
	 * Register submenu
	 * @return void
	 */
	public function register_submenu() {
		add_submenu_page(
            'options-general.php',
            __('Footer Page Settings', 'custom-multilang-footer'),
            __('Footer Page', 'custom-multilang-footer'),
            'manage_options',
            'custom-multilang-footer-settings',
            array($this, 'submenu_page_callback')
        );
	}

	/**
	 * Render submenu
	 * @return void
	 */
	public function submenu_page_callback() {
		if (isset($_POST['footer_page_id'])) {
            update_option('custom_footer_main_page_id', intval($_POST['footer_page_id']));
            echo '<div class="updated"><p>' . __('Footer page saved!', 'custom-multilang-footer') . '</p></div>';
        }
    
        $footer_page_id = get_option('custom_footer_main_page_id');
        ?>
        <div class="wrap">
            <h2><?php _e('Footer Page Settings', 'custom-multilang-footer'); ?></h2>
            <form method="post">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e('Select Footer Page (main language)', 'custom-multilang-footer'); ?></th>
                        <td>
                            <?php
                            wp_dropdown_pages([
                                'name' => 'footer_page_id',
                                'selected' => $footer_page_id,
                                'show_option_none' => __('Select a page', 'custom-multilang-footer'),
                            ]);
                            ?>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
            <p><?php _e('Make sure this page is translated using Polylang if multilingual. Gutenberg + Spectra blocks are supported.', 'custom-multilang-footer'); ?></p>
        </div>
        <?php
	}

}

// Initialize the submenu page
new CMF_Submenu_Page();

