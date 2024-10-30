<?php
/*
  Plugin Name: InFunding
  Plugin URI: https://themeforest.net/item/incharity-wordpress-theme-for-charity-fundraising-nonprofit-organization/14802070
  Description: InFunding is an advanced WordPress plugin to create and manage charity campaigns, and appeal donation via Paypal or bank transfer.
  Version: 1.0
  Author: InwaveThemes
  Author URI: http://www.inwavethemes.com
  License: GNU General Public License v2 or later
 */

/**
 * Description of InFunding
 *
 * @developer duongca
 */
if (!defined('ABSPATH')) {
    exit();
} // Exit if accessed directly

global $inf_settings, $inf_order;
$inf_order = new stdClass();


// translate plugin
add_action('plugins_loaded', 'inf_load_textdomain');
add_action('plugins_loaded', 'initPluginSettings');

function inf_load_textdomain() {
    load_plugin_textdomain('inwavethemes', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

if (!$inf_settings['general']['google_api']) {

    function inf_googlemap_api_required() {
        ?>
        <div class="error">
            <p><?php _e(sprintf('Please input google api in plugin settings <a href="%s">%s</a>', admin_url('edit.php?post_type=infunding&page=settings'), __('Setting Now', 'inwavethemes')), 'inwavethemes'); ?></p>
        </div>
        <?php
    }

    //add_action('admin_notices', 'inf_googlemap_api_required');
}
include_once 'includes/function.admin.php';
include_once 'includes/function.front.php';

if (!defined('INFUNDING_THEME_PATH')) {
    define('INFUNDING_THEME_PATH', WP_PLUGIN_DIR . '/infunding/themes/');
}

if (!defined('INF_LIMIT_ITEMS')) {
    define('INF_LIMIT_ITEMS', 10);
}
$utility = new inFundingUtility();


register_activation_hook(__FILE__, 'inFundingInstall');
register_uninstall_hook(__FILE__, 'inFundingUninstall');

//Check plugin update
add_action('plugins_loaded', 'inFundingCheckUpdate');


//Add parralax menu
add_action('admin_menu', 'inFundingAddAdminMenu');

// Hook into the 'init' action
add_action('init', 'inFundingCreatePostType', 0);
add_action('init', 'inFundingAddCategoryTaxonomy', 0);
add_action('init', 'inFundingAddTagTaxonomy', 0);
add_action('init', array($utility, 'inFundingAddImageSize'));
add_action( 'current_screen', 'inFundingScreen' );

// Add scripts
add_action('admin_enqueue_scripts', 'inFundingAdminAddScript');

//init plugin theme
add_action('after_switch_theme', array($utility, 'initPluginThemes'));
add_action('admin_post_inFundingFilter', 'inFundingFilter');

//Add metabox
if (is_admin()) {
    add_action('load-post.php', 'inFundingAddMetaBox');
    add_action('load-post-new.php', 'inFundingAddMetaBox');
    add_filter('manage_edit-infunding_columns', 'inFundingHeaderColumnsHeader');
    add_action('manage_infunding_posts_custom_column', 'inFundingColumnsContent', 10, 2);
}

//Add action to process member
add_action('admin_post_inFundingSaveMember', 'inFundingSaveMember');
add_action('admin_post_inFundingDeleteMember', 'inFundingDeleteMember');
add_action('admin_post_inFundingDeleteMembers', 'inFundingDeleteMembers');

//Add action to process volunteer
add_action('admin_post_inFundingSaveVolunteer', 'inFundingSaveVolunteer');
add_action('admin_post_inFundingDeleteVolunteer', 'inFundingDeleteVolunteer');
add_action('admin_post_inFundingDeleteVolunteers', 'inFundingDeleteVolunteers');

//Add action to process Payment
add_action('admin_post_inFundingUpdateOrderInfo', 'inFundingUpdateOrderInfo');
add_action('admin_post_inFundingResendEmail', 'inFundingResendEmail');
add_action('admin_post_inFundingClearOrderExpired', 'inFundingClearOrderExpired');
add_action('admin_post_inFundingDeleteOrder', 'inFundingDeleteOrder');
add_action('admin_post_inFundingDeleteOrders', 'inFundingDeleteOrders');

//Add action to process Log
add_action('admin_post_inFundingClearLog', 'inFundingClearLog');
add_action('admin_post_inFundingDeleteLogs', 'inFundingDeleteLogs');
add_action('admin_post_inFundingDeleteLog', 'inFundingDeleteLog');

//Add action save settings
add_action('admin_post_infSaveSettings', 'infSaveSettings');

//Ajax acceptVolunter
add_action('wp_ajax_nopriv_acceptVolunter', 'acceptVolunter');
add_action('wp_ajax_acceptVolunter', 'acceptVolunter');



/* ----------------------------------------------------------------------------------
  FRONTEND FUNCTIONS
  ---------------------------------------------------------------------------------- */

/**
 * Register and enqueue scripts and styles for frontend.
 *
 * @since 1.0.0
 */
//Add site script
add_action('wp_enqueue_scripts', 'inFundingAddSiteScript');

//Define plugin shortcodes
add_shortcode('infunding_list', 'infunding_list_outhtml');
add_shortcode('infunding_map', 'infunding_map_outhtml');
add_shortcode('infunding_member_page', 'infunding_member_page_outhtml');
add_shortcode('infunding_check_order', 'inf_checkorder_page_outhtml');
add_shortcode('infunding_donate_form', 'infunding_donate_form_outhtml');
add_shortcode('inf_site_name', 'inf_site_name');
add_shortcode('inf_customer_name', 'inf_customer_name');
add_shortcode('inf_order_link', 'inf_order_link');
add_shortcode('inf_user_info', 'inf_user_info');
add_shortcode('inf_user_status', 'inf_user_status');
add_shortcode('inf_new_order_status', 'inf_new_order_status');
add_shortcode('inf_reason', 'inf_reason');

//Submit form
add_action('init', 'inFundingSubmitForm');

//Add action to save member
add_action('admin_post_iwePaymentProcess', 'iwePaymentProcess');

//Ajax load iwePaymentNotice
add_action('wp_ajax_nopriv_infPaymentNotice', 'infPaymentNotice');
add_action('wp_ajax_infPaymentNotice', 'infPaymentNotice');
