<?php

/*
 * @package Inwave Funding
 * @version 1.0.0
 * @created May 11, 2016
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of file: File contain all function to process in admin page
 *
 * @developer duongca
 */
require 'utility.php';

//Add plugin menu to admin sidebar
function inFundingAddAdminMenu() {
    //Donor menu
    add_submenu_page('edit.php?post_type=infunding', __('Members', 'inwavethemes'), __('Members', 'inwavethemes'), 'manage_options', 'donor', 'inFundingDonorRenderPage');
    add_submenu_page(NULL, __('Add Member', 'inwavethemes'), NULL, 'manage_options', 'donor/addnew', 'inFundingDonorAddNewPage');
    add_submenu_page(NULL, __('Edit Member', 'inwavethemes'), NULL, 'manage_options', 'donor/edit', 'inFundingDonorAddNewPage');
    //Volunteer menu
    add_submenu_page('edit.php?post_type=infunding', __('Volunteers', 'inwavethemes'), __('Volunteers', 'inwavethemes'), 'manage_options', 'volunteers', 'inFundingVolunteersRenderPage');
    add_submenu_page(NULL, __('Add Volunter', 'inwavethemes'), NULL, 'manage_options', 'volunteer/addnew', 'inFundingVolunteerAddNewPage');
    add_submenu_page(NULL, __('Edit Volunter', 'inwavethemes'), NULL, 'manage_options', 'volunteer/edit', 'inFundingVolunteerAddNewPage');
    //Payment menu
    add_submenu_page('edit.php?post_type=infunding', __('Donates', 'inwavethemes'), __('Donates', 'inwavethemes'), 'manage_options', 'payment', 'inFundingPaymentRenderPage');
    add_submenu_page(NULL, __('Edit donate', 'inwavethemes'), NULL, 'manage_options', 'payment/edit', 'inFundingAddPaymentRenderPage');
    add_submenu_page(NULL, __('Donate detail', 'inwavethemes'), NULL, 'manage_options', 'payment/view', 'inFundingViewPaymentRenderPage');
    //Log menu
    add_submenu_page('edit.php?post_type=infunding', __('Logs', 'inwavethemes'), __('Logs', 'inwavethemes'), 'manage_options', 'logs', 'inFundingLogRenderPage');
    add_submenu_page(NULL, __('Log view', 'inwavethemes'), NULL, 'manage_options', 'log/view', 'inFundingViewLogRenderPage');
    //Settings menu
    add_submenu_page('edit.php?post_type=infunding', __('Settings', 'inwavethemes'), __('Settings', 'inwavethemes'), 'manage_options', 'settings', 'inFundingSettingsRenderPage');
}

if (!function_exists('inFundingInstall')) {
    //1.0.0, 1.1.0
    global $inFundingVersion;
    $inFundingVersion = '1.0.0';

    /**
     *
     * @global type $wpdb
     * @global type $inFundingVersion
     */
    function inFundingInstall() {
        global $wpdb;
        global $inFundingVersion;
        $utility = new inFundingUtility();

        $charset_collate = '';
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        if (!empty($wpdb->charset)) {
            $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }

        if (!empty($wpdb->collate)) {
            $charset_collate .= " COLLATE {$wpdb->collate}";
        }


        $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "inf_donors (
		id int(11) NOT NULL AUTO_INCREMENT,
                user_id int(11) DEFAULT NULL,
                field_value longtext,
                social_links text NULL,
                member_type varchar(20) DEFAULT 'donor',
                PRIMARY KEY (`id`)
	) " . $charset_collate . ";";
        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "inf_orders (
		id int(11) NOT NULL AUTO_INCREMENT,
		member_id int(11) NOT NULL,
		campaign_id int(11) NOT NULL,
		note text NOT NULL,
		price int(11) NOT NULL,
		currentcy CHAR(3) NOT NULL,
		time_created int(11) NOT NULL,
		time_paymented int(11) NULL,
                payment_method varchar(50),
                status tinyint(1),
                PRIMARY KEY (`id`)
	) " . $charset_collate . ";";
        dbDelta($sql);


        $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "inf_logs (
		id int(11) NOT NULL AUTO_INCREMENT,
                log_type varchar(30) NOT NULL,
                scope varchar(30) NULL,
		timestamp int(11) NOT NULL,
                message text NOT NULL,
                link varchar(255) NULL,
                PRIMARY KEY (`id`)
	) " . $charset_collate . ";";
        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "inf_volunteer (
		id int(11) NOT NULL AUTO_INCREMENT,
                campaign_id int(11) NOT NULL,
                member_id int(11) NOT NULL,
                date_start int(11) NULL,
                date_end int(11) NULL,
		date_register int(11) NOT NULL,
                message text NOT NULL,
                status tinyint(2) NULL,
				PRIMARY KEY (`id`)
	) " . $charset_collate . ";";
        dbDelta($sql);

        //add $inFundingVersion table version
        add_option('inFundingVersion', $inFundingVersion);

        //Add themes
        $utility->initPluginThemes();

        $installed_ver = get_option("inFundingVersion");

        if ($installed_ver == '1.0.0' && $inFundingVersion > $installed_ver) {

            $sql = "CREATE TABLE " . $wpdb->prefix . "inf_donors (
		id int(11) NOT NULL AUTO_INCREMENT,
                user_id int(11) DEFAULT NULL,
                field_value longtext,
                social_links text NULL,
                member_type varchar(20) DEFAULT 'donor'
	);";
            dbDelta($sql);
            $sql = "CREATE TABLE " . $wpdb->prefix . "inf_volunteer (
		id int(11) NOT NULL AUTO_INCREMENT,
                campaign_id int(11) NOT NULL,
                member_id int(11) NOT NULL,
                date_start int(11) NULL,
                date_end int(11) NULL,
		date_register int(11) NOT NULL,
                message text NOT NULL,
                status tinyint(2) NULL
	);";
            dbDelta($sql);

            update_option("inFundingVersion", $inFundingVersion);
        }
    }

}

if (!function_exists('inFundingCheckUpdate')) {

    function inFundingCheckUpdate() {
        global $inFundingVersion;
        if (get_site_option('inFundingVersion') != $inFundingVersion) {
            inFundingInstall();
        }
    }

}

if (!function_exists('inFundingUninstall')) {

    function inFundingUninstall() {

        global $wpdb;
        $option_names = array('inFundingVersion', 'inf_settings', 'infunding_category_children');
        $tables = array($wpdb->prefix . 'inf_donors', $wpdb->prefix . 'inf_logs', $wpdb->prefix . 'inf_orders', $wpdb->prefix . 'inf_volunteer');

        foreach ($option_names as $option) {
            delete_option($option);
            delete_site_option($option);
        }

        //drop a custom db table
        foreach ($tables as $table) {
            $wpdb->query("DROP TABLE IF EXISTS " . $table);
        }

        $posts = new WP_Query(array('post_type' => 'infunding', 'post_status' => 'any'));
        //delete all infunding post meta
        $wpdb->query("DELETE FROM {$wpdb->prefix}postmeta WHERE meta_key LIKE 'inf_%'");
        //delete all post with post type 
        $wpdb->query("DELETE FROM {$wpdb->prefix}posts WHERE post_type = 'infunding'");
        //delete all infunding category and tag
        $wpdb->query("DELETE FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy LIKE 'infunding_%'");
    }

}

/**
 * Function to register Inwave Campaign Category with Wordpress
 */
function inFundingAddCategoryTaxonomy() {
    global $inf_settings;
    if ($inf_settings) {
        $general = $inf_settings['general'];
    }
    $labels = array(
        'name' => _x('Categories', 'Taxonomy General Name', 'inwavethemes'),
        'singular_name' => _x('Category', 'Taxonomy Singular Name', 'inwavethemes'),
        'menu_name' => __('Categories', 'inwavethemes'),
        'all_items' => __('All Categories', 'inwavethemes'),
        'parent_item' => __('Parent Category', 'inwavethemes'),
        'parent_item_colon' => __('Parent Category:', 'inwavethemes'),
        'new_item_name' => __('New Category Name', 'inwavethemes'),
        'add_new_item' => __('Add New Category', 'inwavethemes'),
        'edit_item' => __('Edit Category', 'inwavethemes'),
        'update_item' => __('Update Category', 'inwavethemes'),
        'separate_items_with_commas' => __('Separate categories with commas', 'inwavethemes'),
        'search_items' => __('Search categories', 'inwavethemes'),
        'add_or_remove_items' => __('Add or remove categories', 'inwavethemes'),
        'choose_from_most_used' => __('Choose from the most used categories', 'inwavethemes'),
        'not_found' => __('Not Found', 'inwavethemes'),
    );
    $rewrite = array(
        'slug' => isset($general['category_slug']) ? $general['category_slug'] : 'inf-category',
        'with_front' => true,
        'hierarchical' => true,
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'rewrite' => $rewrite,
    );
    register_taxonomy('infunding_category', array('infunding'), $args);
}

/**
 * Function to register Inwave Campaign Category with Wordpress
 */
function inFundingAddTagTaxonomy() {
    global $inf_settings;
    if ($inf_settings) {
        $general = $inf_settings['general'];
    }
    $labels = array(
        'name' => _x('Tags', 'Taxonomy General Name', 'inwavethemes'),
        'singular_name' => _x('Tag', 'Taxonomy Singular Name', 'inwavethemes'),
        'menu_name' => __('Tags', 'inwavethemes'),
        'all_items' => __('All Tags', 'inwavethemes'),
        'parent_item' => __('Parent Tag', 'inwavethemes'),
        'parent_item_colon' => __('Parent Tag:', 'inwavethemes'),
        'new_item_name' => __('New Tag Name', 'inwavethemes'),
        'add_new_item' => __('Add New Tag', 'inwavethemes'),
        'edit_item' => __('Edit Tag', 'inwavethemes'),
        'update_item' => __('Update Tag', 'inwavethemes'),
        'separate_items_with_commas' => __('Separate tags with commas', 'inwavethemes'),
        'search_items' => __('Search tags', 'inwavethemes'),
        'add_or_remove_items' => __('Add or remove tags', 'inwavethemes'),
        'choose_from_most_used' => __('Choose from the most used tags', 'inwavethemes'),
        'not_found' => __('Not Found', 'inwavethemes'),
    );
    $rewrite = array(
        'slug' => isset($general['tag_slug']) ? $general['tag_slug'] : 'inf-tag',
        'with_front' => true,
        'hierarchical' => true,
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'rewrite' => $rewrite,
    );
    register_taxonomy('infunding_tag', array('infunding'), $args);
}

/**
 * Function to register Inwave Funding Post_type with Wordpress
 */
function inFundingCreatePostType() {
    global $inf_settings;
    if ($inf_settings) {
        $general = $inf_settings['general'];
    }
    $labels = array(
        'name' => _x('Campaigns', 'Post Type General Name', 'inwavethemes'),
        'singular_name' => _x('Campaign', 'Post Type Singular Name', 'inwavethemes'),
        'menu_name' => __('Crowdfunding', 'inwavethemes'),
        'parent_item_colon' => __('Parent Campaign:', 'inwavethemes'),
        'all_items' => __('All Campaigns', 'inwavethemes'),
        'view_item' => __('View Campaign', 'inwavethemes'),
        'add_new_item' => __('Add New Campaign', 'inwavethemes'),
        'add_new' => __('Add New', 'inwavethemes'),
        'edit_item' => __('Edit Item', 'inwavethemes'),
        'update_item' => __('Update Item', 'inwavethemes'),
        'search_items' => __('Search Item', 'inwavethemes'),
        'not_found' => __('Not found', 'inwavethemes'),
        'not_found_in_trash' => __('Not found in Trash', 'inwavethemes'),
    );
    $rewrite = array(
        'slug' => isset($general['funding_slug']) ? $general['funding_slug'] : 'campaign',
        'with_front' => false,
        'pages' => true,
        'feeds' => true,
    );
    $args = array(
        'label' => __('infunding', 'inwavethemes'),
        'description' => __('Inwave Crowdfunding', 'inwavethemes'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'comments'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-calendar-alt',
        'can_export' => true,
        'has_archive' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'rewrite' => $rewrite,
        'capability_type' => 'page',
    );
    register_post_type('infunding', $args);
}

/**
 * Function to add script for admin page
 */
function inFundingAdminAddScript() {
    wp_enqueue_style('font-awesome', plugins_url('/infunding/assets/css/font-awesome/css/font-awesome.min.css'));
    wp_enqueue_style('select2', plugins_url('/infunding/assets/css/select2.min.css'));
    wp_enqueue_style('infadmin-style', plugins_url('/infunding/assets/css/infunding_admin.css'));
    wp_enqueue_script('select2', plugins_url() . '/infunding/assets/js/select2.min.js', array('jquery'), '1.0.0', true);
    wp_register_script('infadmin-script', plugins_url() . '/infunding/assets/js/infunding_admin.js', array('jquery'), '1.0.0', true);
    wp_localize_script('infadmin-script', 'inFundingCfg', array('siteUrl' => site_url(), 'adminUrl' => admin_url(), 'ajaxUrl' => admin_url('admin-ajax.php')));
    wp_enqueue_media();
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('infadmin-script');
}

function inFundingFilter() {
    $link = filter_input(INPUT_SERVER, 'HTTP_REFERER');
    $link_param = parse_url($link);
    $q_vars = array();
    parse_str($link_param['query'], $q_vars);
    $post = filter_input_array(INPUT_POST);
    unset($post['action']);
    $query_vars = array_merge($q_vars, $post);
    $new_params = array();
    foreach ($query_vars as $key => $value) {
        if ($value) {
            $new_params[$key] = $value;
        }
    }

    $params = http_build_query($new_params);
    wp_redirect($link_param['scheme'] . '://' . $link_param['host'] . $link_param['path'] . '?' . $params);
}

/* * *********************************************************
 * ******** CODE CAMPAIGN PAGE POST ********
 * ******************************************************** */

//Add metabox
function inFundingAddMetaBox() {
    new inFundingMetaBox();
}

function inFundingHeaderColumnsHeader($columns) {
    $columns = array('cb' => '<input type="checkbox" />',
        'title' => __('Title', 'inwavethemes'),
        'taxonomy-infunding_category' => __('Categories', 'inwavethemes'),
        'current_goal' => __('Funded', 'inwavethemes'),
        'date' => __('Date', 'inwavethemes'),
        'inf_status' => __('Status', 'inwavethemes')
    );
    return $columns;
}

function inFundingColumnsContent($column_name, $post_ID) {
    $utility = new inFundingUtility();
    $campaign = $utility->getCampaignInfo($post_ID);
    if ($column_name == 'current_goal') {
        if ($campaign->goal) {
            echo '<span title="' . $campaign->current . '/' . $campaign->goal . '">' . $campaign->percent . '%</span>';
        } else {
            echo '<span title="' . __('Unlimited Goal', 'inwavethemes') . '">' . __('Unlimited', 'inwavethemes') . '</span>';
        }
    }
    if ($column_name == 'inf_status') {
        echo ($campaign->status ? __('In Progress', 'inwavethemes') : ($campaign->days_to_start > 0 ? __('Upcoming', 'inwavethemes') : __('Ended', 'inwavethemes')));
    }
}

//function inFundingColumnSortable($columns) {
//    $columns['status'] = 'status';
//    $columns['funded'] = 'funded';
//    return $columns;
//}
//
//function inFundingEditLoad() {
//    add_filter('request', 'inFundingSortCustomColumns');
//}
//
///* Sorts the movies. */
//
//function inFundingSortCustomColumns($vars) {
//
//    /* Check if we're viewing the 'infunding' post type. */
//    if (isset($vars['post_type']) && 'infunding' == $vars['post_type']) {
//
//        /* Check if 'orderby' is set to 'status'. */
//        if (isset($vars['orderby']) && 'status' == $vars['orderby']) {
//
//            /* Merge the query vars with our custom variables. */
//            $vars = array_merge(
//                    $vars, array(
//                'meta_key' => 'inf_current',
//                'orderby' => 'meta_value_num'
//                    )
//            );
//        }
//        /* Check if 'orderby' is set to 'funded'. */
//        if (isset($vars['orderby']) && 'funded' == $vars['orderby']) {
//
//            /* Merge the query vars with our custom variables. */
//            $vars = array_merge(
//                    $vars, array(
//                'meta_key' => 'inf_current',
//                'orderby' => 'meta_value_num'
//                    )
//            );
//        }
//    }
//
//    return $vars;
//}

/* * **************************************************
 * ************ CODE SETTINGS PAGE ****************
 * ************************************************** */

function inFundingSettingsRenderPage() {
    include_once 'views/settings.php';
}

function infSaveSettings() {
    $data = $_POST;
    $form_field = isset($data['inf_settings']['register_form_fields']) ? $data['inf_settings']['register_form_fields'] : array();
    $fields = array();
    if (!empty($form_field['label'])) {
        foreach ($form_field['label'] as $key => $value) {
            $field = array();
            $field['label'] = $value;
            $field['name'] = sanitize_text_field($form_field['name'][$key]);
            $field['group'] = sanitize_text_field($form_field['group'][$key]);
            $field['type'] = sanitize_text_field($form_field['type'][$key]);
            if ($field['type'] == 'select') {
                $options = explode("\n", $form_field['values'][$key]);
                $dataf = array();
                foreach ($options as $option) {
                    $op = explode('|', $option);
                    $dataf[] = array('value' => $op[0], 'text' => $op[1]);
                }
                $field['values'] = $dataf;
            } else {
                $field['values'] = $form_field['values'][$key];
            }
            $field['default_value'] = $form_field['default_value'][$key];
            $field['show_on_list'] = $form_field['show_on_list'][$key];
            $field['require_field'] = $form_field['require_field'][$key];
            $fields[] = $field;
        }
    }

    $data['inf_settings']['register_form_fields'] = $fields;
    update_option('inf_settings', serialize($data['inf_settings']));
    wp_redirect(admin_url('edit.php?post_type=infunding&page=settings'));
}

/* * **************************************************
 * ************ CODE MEMBER PAGE ****************
 * ************************************************** */

function inFundingDonorRenderPage() {
    $member = new inFundingMember();
    $paging = new inFundingPaging();
    $start = $paging->findStart(INF_LIMIT_ITEMS);
    $count = $member->getCountMember();
    $pages = $paging->findPages($count, INF_LIMIT_ITEMS);
    $members = $member->getMembers($start, INF_LIMIT_ITEMS);

    $member_list = $member->getMemberRowData($members);

    include_once 'views/member.list.php';
}

function inFundingDonorAddNewPage() {
    $utility = new inFundingUtility();
    $id = intval($_GET['id']);
    $member = new inFundingMember();
    if ($id) {
        $member = $member->getMember($id);
        if (!$member->getId()) {
            printf($utility->getMessage(sprintf(__('No Member found with id = <strong>%d</strong>', 'inwavethemes'), $id), 'notice'));
        } else {
            $member_data = $member->getMemberRowData($id, true);
            include_once 'views/member.edit.php';
        }
    } else {
        printf($utility->getMessage(__('No id set or id invalid', 'inwavethemes'), 'error'));
    }
}

function inFundingSaveMember() {
    $session = new Inwave_Session();
    $member = new inFundingMember();
    $utility = new inFundingUtility();
    $member->setId($_REQUEST['id']);
    if (isset($_REQUEST['user_id'])) {
        $member->setUser_id($_REQUEST['user_id']);
    }
    $member->setField_value(serialize($utility->prepareMemberFieldValue($_REQUEST['member'])));
    if ($member->getId()) {
        $updateMember = unserialize($member->editMember($member));
    } else {
        $updateMember = unserialize($member->addMember($member));
    }
    if ($updateMember['success']) {
        $session->set('inwave_message', $utility->getMessage(__(sprintf('%s', $updateMember['msg']), 'inwavethemes')));
    } else {
        $session->set('inwave_message', $utility->getMessage(__('Can\'t update member: ', 'inwavethemes') . '<br/>' . __($updateMember['msg'], 'inwavethemes')));
    }
    wp_redirect($_SERVER['HTTP_REFERER']);
}

/**
 * Delete single type on list
 */
function inFundingDeleteMember() {
    $id = $_GET['id'];
    $utility = new inFundingUtility();
    $session = new Inwave_Session();
    $member = new inFundingMember();
    if ($id && is_numeric($id)) {
        $del = unserialize($member->deleteMember($id));
        if (!$del['success']) {
            $session->set('inwave_message', $utility->getMessage($del['msg'], 'error'));
        } else {
            $session->set('inwave_message', $utility->getMessage(__('Donor has been remove', 'inwavethemes')));
        }
    } else {
        $session->set('inwave_message', $utility->getMessage(__('No id set or id invalid', 'inwavethemes'), 'error'));
    }
    wp_redirect(admin_url('edit.php?post_type=infunding&page=donor'));
}

/**
 * Delete multiple Location type (selected Location types) on list
 */
function inFundingDeleteMembers() {
    $utility = new inFundingUtility();
    if (isset($_POST['fields']) && !empty($_POST['fields'])) {
        $member = new inFundingMember();
        $session = new Inwave_Session();
        $ids = $_POST['fields'];
        $msg = $member->deleteMembers($ids);
        if (isset($msg['error']) && isset($msg['success'])) {
            $session->set('inwave_message', $utility->getMessage(__($msg['error'] . $msg['success']), 'notice'));
        } elseif (isset($msg['error']) && !isset($msg['success'])) {
            $session->set('inwave_message', $utility->getMessage(__($msg['error']), 'error'));
        } elseif (!isset($msg['error']) && isset($msg['success'])) {
            $session->set('inwave_message', $utility->getMessage(__($msg['success'])));
        } else {
            $session->set('inwave_message', $utility->getMessage(__('Unknown error', 'inwavethemes')));
        }
    } else {
        $session->set('inwave_message', $utility->getMessage(__('Please select row(s) to delete', 'inwavethemes'), 'error'));
    }
    wp_redirect(admin_url('edit.php?post_type=infunding&page=donor'));
}

/* * **************************************************
 * ************ CODE VOLUNTEER PAGE ****************
 * ************************************************** */

function inFundingVolunteersRenderPage() {
    $member = new inFundingMember();
    $paging = new inFundingPaging();
    $start = $paging->findStart(INF_LIMIT_ITEMS);
    $count = $member->getCountMember('volunteer');
    $pages = $paging->findPages($count, INF_LIMIT_ITEMS);
    $members = $member->getMembers($start, INF_LIMIT_ITEMS, 'volunteer');

    $member_list = $member->getMemberRowData($members);

    include_once 'views/volunteer.list.php';
}

function inFundingVolunteerAddNewPage() {
    $utility = new inFundingUtility();
    $id = $_GET['id'];
    $member = new inFundingMember();
    if ($id) {
        $member = $member->getMember($id);
        if (!$member->getId()) {
            printf($utility->getMessage(sprintf(__('No Member found with id = <strong>%d</strong>', 'inwavethemes'), $id), 'notice'));
        } else {
            $member_data = $member->getMemberRowData($id, true);
            include_once 'views/volunteer.edit.php';
        }
    } else {
        printf($utility->getMessage(__('No id set or id invalid', 'inwavethemes'), 'error'));
    }
}

function inFundingSaveVolunteer() {
    $member = new inFundingMember();
    $utility = new inFundingUtility();
    $member->setId($_REQUEST['id']);
    $session = new Inwave_Session();
    if (isset($_REQUEST['user_id'])) {
        $member->setUser_id($_REQUEST['user_id']);
    }
    $member->setField_value(serialize($utility->prepareMemberFieldValue($_REQUEST['member'])));
    $member->setSocial_links(serialize($_POST['social']));
    if ($member->getId()) {
        $updateMember = unserialize($member->editMember($member));
    } else {
        $updateMember = unserialize($member->addMember($member));
    }
    if ($updateMember['success']) {
        $session->set('inwave_message', $utility->getMessage(__(sprintf('%s', $updateMember['msg']), 'inwavethemes')));
    } else {
        $session->set('inwave_message', $utility->getMessage(__('Can\'t update member: ', 'inwavethemes') . '<br/>' . __($updateMember['msg'], 'inwavethemes')));
    }
    wp_redirect($_SERVER['HTTP_REFERER']);
}

/**
 * Delete single type on list
 */
function inFundingDeleteVolunteer() {
    $id = $_GET['id'];
    $utility = new inFundingUtility();
    $session = new Inwave_Session();
    $member = new inFundingMember();
    if ($id && is_numeric($id)) {
        $del = unserialize($member->deleteMember($id));
        if (!$del['success']) {
            $session->set('inwave_message', $utility->getMessage($del['msg'], 'error'));
        } else {
            $session->set('inwave_message', $utility->getMessage(__('Donor has been remove', 'inwavethemes')));
        }
    } else {
        $session->set('inwave_message', $utility->getMessage(__('No id set or id invalid', 'inwavethemes'), 'error'));
    }
    wp_redirect(admin_url('edit.php?post_type=infunding&page=volunteers'));
}

/**
 * Delete multiple Location type (selected Location types) on list
 */
function inFundingDeleteVolunteers() {
    $utility = new inFundingUtility();
    if (isset($_POST['fields']) && !empty($_POST['fields'])) {
        $member = new inFundingMember();
        $ids = $_POST['fields'];
        $msg = $member->deleteMembers($ids);
        $session = new Inwave_Session();
        if (isset($msg['error']) && isset($msg['success'])) {
            $session->set('inwave_message', $utility->getMessage(__($msg['error'] . $msg['success']), 'notice'));
        } elseif (isset($msg['error']) && !isset($msg['success'])) {
            $session->set('inwave_message', $utility->getMessage(__($msg['error']), 'error'));
        } elseif (!isset($msg['error']) && isset($msg['success'])) {
            $session->set('inwave_message', $utility->getMessage(__($msg['success'])));
        } else {
            $session->set('inwave_message', $utility->getMessage(__('Unknown error', 'inwavethemes')));
        }
    } else {
        $session->set('inwave_message', $utility->getMessage(__('Please select row(s) to delete', 'inwavethemes'), 'error'));
    }
    wp_redirect(admin_url('edit.php?post_type=infunding&page=volunteers'));
}

/* * **************************************************
 * ************ CODE PAYMENT PAGE ****************
 * ************************************************** */

function inFundingPaymentRenderPage() {
    $utility = new inFundingUtility();
    $filter = '';
    $orderby = '';
    $request = filter_input_array(INPUT_GET);
    if (isset($request['status']) && $request['status']) {
        if ($filter) {
            $filter .= ' AND status=' . $request['status'];
        } else {
            $filter .= ' status=' . $request['status'];
        }
    }
    if (isset($request['campaign']) && $request['campaign']) {
        if ($filter) {
            $filter .= ' AND campaign_id=' . $request['campaign'];
        } else {
            $filter .= ' campaign_id=' . $request['campaign'];
        }
    }
    if (isset($request['keyword']) && $request['keyword']) {
        if ($filter) {
            $filter .= ' AND (note LIKE \'%' . htmlspecialchars($request['keywork']) . '%\' OR field_value LIKE \'%' . htmlspecialchars($request['keywork']) . '%\' OR post_title LIKE \'%' . htmlspecialchars($request['keywork']) . '%\') AND p.post_type=\'infunding\'';
        } else {
            $filter .= '  (note LIKE \'%' . htmlspecialchars($request['keywork']) . '%\' OR field_value LIKE \'%' . htmlspecialchars($request['keywork']) . '%\' OR post_title LIKE \'%' . htmlspecialchars($request['keywork']) . '%\') AND p.post_type=\'infunding\'';
        }
    }

    if (isset($request['orderby']) && $request['orderby']) {
        if ($request['orderby'] == 'campaign') {
            $orderby .= ' ORDER BY p.post_title ';
        } else {
            $orderby .= ' ORDER BY o.' . $request['orderby'] . ' ';
        }
        $orderby .= ($request['dir'] ? $request['dir'] : 'asc');
    }

    $server_data = filter_input_array(INPUT_SERVER);
    parse_str($server_data['QUERY_STRING'], $server_data['query']);
    unset($server_data['query']['dir']);
    unset($server_data['query']['orderby']);
    $order_link = $server_data['REQUEST_SCHEME'] . '://' . $server_data['HTTP_HOST'] . $server_data['SCRIPT_NAME'] . '?' . http_build_query($server_data['query']);
    $order = new inFundingOrder();
    $paging = new inFundingPaging();
    $start = $paging->findStart(INF_LIMIT_ITEMS);
    $count = $order->getCountOrder($filter);
    $pages = $paging->findPages($count, INF_LIMIT_ITEMS);
    $orders = $order->getOrders($start, INF_LIMIT_ITEMS, $filter, $orderby);
    $order_dir = (filter_input(INPUT_GET, 'dir') == 'asc');
    $sorted = filter_input(INPUT_GET, 'orderby');
    include_once 'views/payment.list.php';
}

function inFundingViewPaymentRenderPage() {
    $id = $_GET['id'];
    $session = new Inwave_Session();
    $utility = new inFundingUtility();
    if ($id && is_numeric($id)) {
        $order = new inFundingOrder();
        $order = $order->getOrder($id);
        include_once 'views/payment.view.php';
    } else {
        $session->set('inwave_message', $utility->getMessage(__('No id set or id invalid', 'inwavethemes'), 'error'));
        wp_redirect(admin_url('edit.php?post_type=infunding&page=payment'));
    }
}

/**
 * Function to render Addnew or Edit Payment page
 */
function inFundingAddPaymentRenderPage() {
    $utility = new inFundingUtility();
    $id = $_GET['id'];
    $order = new inFundingOrder();
    if ($id) {
        $order = $order->getOrder($id);
        if (!$order->getId()) {
            printf($utility->getMessage(sprintf(__('No Order found with id = <strong>%d</strong>', 'inwavethemes'), $id), 'notice'));
        } else {
            include_once 'views/payment.edit.php';
        }
    } else {
        printf($utility->getMessage(__('No id set or id invalid', 'inwavethemes'), 'error'));
    }
}

function inFundingUpdateOrderInfo() {
    global $inf_order;
    $data = $_REQUEST;
    $log = new inFundingLog();
    $utility = new inFundingUtility();
    $member = new inFundingMember();
    $orderobj = new inFundingOrder();
    $order = $orderobj->getOrder($data['order_id']);
    $session = new Inwave_Session();

    if ($order->getMember()->getId()) {
        $member->setId($data['order_member']);
        $member->setField_value(serialize($utility->prepareMemberFieldValue($data['member'])));
        $updateMember = unserialize($member->editMember($member));
        $order->setMember($data['order_member']);
    } else {
        $order->setMember(0);
        $updateMember['success'] = true;
    }

    $order->setNote(sanitize_textarea_field($data['order_note']));
    if ($data['order_time_paymented']) {
        $order->setTime_paymented(sanitize_text_field($data['order_time_paymented']));
    } else {
        $order->setTime_paymented(time());
    }
    $order->setStatus(sanitize_text_field($data['new_order_status']));
    $update = unserialize($orderobj->editOrder($order));

    if ($update['success'] && sanitize_text_field($data['new_order_status']) != sanitize_text_field($data['order_status'])) {
        $inf_order->order = $orderobj->getOrder(sanitize_text_field($data['order_id']));
        $current = htmlspecialchars(get_post_meta($order->getCampaign(), 'inf_current', true));
        if (($data['order_status'] == 1 || $data['order_status'] == 3 || $data['order_status'] == 4) && $data['new_order_status'] == 2) {
            update_post_meta($order->getCampaign(), 'inf_current', $current + $order->getSum_price());
        }
        if ($data['order_status'] == 2) {
            update_post_meta($order->getCampaign(), 'inf_current', $current - $order->getSum_price());
        }
        if (isset($data['sendmail_to_cutommer'])) {
            $mail_param = array(
                'full_name' => sanitize_text_field($data['member']['full_name']),
                'order_id' => sanitize_text_field($data['order_id']),
                'new_status' => sanitize_text_field($data['new_order_status']),
                'reason' => sanitize_textarea_field($data['reason'])
            );
            $sendmail = unserialize($utility->sendEmail(sanitize_email($data['member']['email']), $mail_param, 'order_change_status'));
            if ($sendmail['success']) {
                $log->addLog(new inFundingLog(NULL, 'success', time(), $sendmail['message']), $order->getLink($data['order_id'], 'View order'));
            } else {
                $log->addLog(new inFundingLog(NULL, 'error', time(), $sendmail['message'], $order->getLink($data['order_id'], 'View order')));
            }
        }
    }

    if ($update['success'] && $updateMember['success']) {
        $session->set('inwave_message', $utility->getMessage(__('Update order ' . $order->getOrderCode($data['order_id']) . ' success'), 'success'));
        $log->addLog(new inFundingLog(NULL, 'success', time(), __('Update order ' . $order->getOrderCode($data['order_id']) . ' success', 'inwavethemes'), $order->getLink($data['order_id'], 'View order')));
    } elseif ($update['success'] && !$updateMember['success']) {
        $session->set('inwave_message', $utility->getMessage(__('Order ' . $order->getOrderCode($data['order_id']) . ' update success but member error: ' . $updateMember['msg'], 'inwavethemes'), 'notice'));
        $log->addLog(new inFundingLog(NULL, 'notice', time(), __('Order ' . $order->getOrderCode($data['order_id']) . ' update success but member error: ' . $updateMember['msg'], 'inwavethemes'), $order->getLink($data['order_id'], 'View order')));
    } elseif (!$update['success'] && $updateMember['success']) {
        $session->set('inwave_message', $utility->getMessage(__('Order ' . $order->getOrderCode($data['order_id']) . ' update error: ' . $update['msg'], 'inwavethemes'), 'notice'));
        $log->addLog(new inFundingLog(NULL, 'notice', time(), __('Order ' . $order->getOrderCode($data['order_id']) . ' update error: ' . $update['msg'], 'inwavethemes'), $order->getLink($data['order_id'], 'View order')));
    } else {
        $session->set('inwave_message', $utility->getMessage(__('Can\'t update order ' . $order->getOrderCode($data['order_id']) . ': ' . $update['msg'] . '<br/>' . $updateMember['msg'], 'inwavethemes'), 'error'));
        $log->addLog(new inFundingLog(NULL, 'error', time(), __('Can\'t update order ' . $order->getOrderCode($data['order_id']) . ': ' . $update['msg'] . '<br/>' . $updateMember['msg'], 'inwavetenes'), $order->getLink($data['order_id'], 'View order')));
    }
    wp_redirect($order->getLink($data['order_id']));
}

function inFundingResendEmail() {
    global $inf_order;
    $utility = new inFundingUtility();
    $session = new Inwave_Session();
    $order = new inFundingOrder();
    $orderObj = $order->getOrder(filter_input(INPUT_GET, 'id'));
    $inf_order->order = $orderObj;
    $member = $orderObj->getMember()->getField_value();
    foreach ($member as $field) {
        switch ($field['name']) {
            case 'full_name':
                $customer_name = $field['value'];
                break;
            case 'email':
                $customer_email = $field['value'];
                break;
            default:
                break;
        }
    }
    $mail_param = array(
        'full_name' => $customer_name
    );
    $sendmail = unserialize($utility->sendEmail($customer_email, $mail_param, 'order_info'));
    if ($sendmail['success']) {
        $session->set('inwave_message', $utility->getMessage(__('An email had been sent to custommer.', 'inwavethemes')));
    } else {
        $session->set('inwave_message', $utility->getMessage($sendmail['message'], 'error'));
    }
    wp_redirect($order->getLink($orderObj->getId()));
}

function inFundingClearOrderExpired() {
    global $inf_settings;
    $utility = new inFundingUtility();
    $session = new Inwave_Session();
    $timeKill = $inf_settings['general']['order_time_expired'];
    if (!$timeKill) {
        $timeKill = 2;
    }

    $order_time_kill = time() - $timeKill * 3600;
    $order = new inFundingOrder();
    $kills = $order->killOrderExpired($order_time_kill);
    if ($kills > 0) {
        $session->set('inwave_message', $utility->getMessage('Have ' . $kills . ' orders killed'));
    } else {
        $session->set('inwave_message', $utility->getMessage(__('No order math to kill', 'inwavethemes')));
    }
    wp_redirect(admin_url('edit.php?post_type=infunding&page=payment'));
}

/**
 * Delete single order on list
 */
function inFundingDeleteOrder() {
    $id = $_GET['id'];
    $utility = new inFundingUtility();
    $order = new inFundingOrder();
    $session = new Inwave_Session();
    if ($id && is_numeric($id)) {
        $order->deleteOrder($id);
        $session->set('inwave_message', $utility->getMessage(__('Order has been remove', 'inwavethemes')));
    } else {
        $session->set('inwave_message', $utility->getMessage(__('No id set or id invalid', 'inwavethemes'), 'error'));
    }
    wp_redirect(admin_url('edit.php?post_type=infunding&page=payment'));
}

/**
 * Delete multiple orders (selected order) on list
 */
function inFundingDeleteOrders() {
    $utility = new inFundingUtility();
    $session = new Inwave_Session();
    if (isset($_POST['fields']) && !empty($_POST['fields'])) {
        $order = new inFundingOrder();
        $ids = $_POST['fields'];
        $order->deleteOrders($ids);
        $session->set('inwave_message', $utility->getMessage(__('All selected order(s) has been delete', 'inwavethemes')));
    } else {
        $session->set('inwave_message', $utility->getMessage(__('Please select row(s) to delete', 'inwavethemes'), 'error'));
    }
    wp_redirect(admin_url('edit.php?post_type=infunding&page=payment'));
}

/* * **************************************************
 * ************ CODE LOG PAGE ****************
 * ************************************************** */

function inFundingLogRenderPage() {
    $utility = new inFundingUtility();
    $paging = new inFundingPaging();
    $logs = new inFundingLog();
    $start = $paging->findStart(INF_LIMIT_ITEMS);
    $count = count($logs->getAllLogs());
    $pages = $paging->findPages($count, INF_LIMIT_ITEMS);
    $logOnPage = $logs->getLogsPerPage($start, INF_LIMIT_ITEMS);
    include_once 'views/logs.list.php';
}

function inFundingViewLogRenderPage() {
    $id = intval($_GET['id']);
    $utility = new inFundingUtility();
    $session = new Inwave_Session();
    if ($id && is_numeric($id)) {
        $log = new inFundingLog();
        $log = $log->getLog($id);
        include_once 'views/logs.view.php';
    } else {
        $session->set('inwave_message', $utility->getMessage(__('No id set or id invalid', 'inwavethemes'), 'error'));
        wp_redirect(admin_url('edit.php?post_type=infunding&page=logs'));
    }
}

function inFundingDeleteLog() {
    $id = intval($_GET['id']);
    $utility = new inFundingUtility();
    $session = new Inwave_Session();
    $log = new inFundingLog();
    if ($id && is_numeric($id)) {
        $del = unserialize($log->deleteLog($id));
        if (!$del['success']) {
            $session->set('inwave_message', $utility->getMessage($del['msg'], 'error'));
        } else {
            $session->set('inwave_message', $utility->getMessage(__('Log has been remove', 'inwavethemes')));
        }
    } else {
        $session->set('inwave_message', $utility->getMessage(__('No id set or id invalid', 'inwavethemes'), 'error'));
    }
    wp_redirect(admin_url('edit.php?post_type=infunding&page=logs'));
}

function inFundingDeleteLogs() {
    $utility = new inFundingUtility();
    $session = new Inwave_Session();
    if (isset($_POST['fields']) && !empty($_POST['fields'])) {
        $log = new inFundingLog();
        $ids = $_POST['fields'];
        $msg = $log->deleteLogs($ids);
        if (isset($msg['error']) && isset($msg['success'])) {
            $session->set('inwave_message', $utility->getMessage(__($msg['error'] . $msg['success']), 'notice'));
        } elseif (isset($msg['error']) && !isset($msg['success'])) {
            $session->set('inwave_message', $utility->getMessage(__($msg['error']), 'error'));
        } elseif (!isset($msg['error']) && isset($msg['success'])) {
            $session->set('inwave_message', $utility->getMessage(__($msg['success'])));
        } else {
            $session->set('inwave_message', $utility->getMessage(__('Unknown error', 'inwavethemes')));
        }
    } else {
        $session->set('inwave_message', $utility->getMessage(__('Please select row(s) to delete', 'inwavethemes'), 'error'));
    }
    wp_redirect(admin_url('edit.php?post_type=infunding&page=logs'));
}

function inFundingClearLog() {
    $utility = new inFundingUtility();
    $log = new inFundingLog();
    $session = new Inwave_Session();
    $msg = $log->emptyLog();
    if (isset($msg['error']) && isset($msg['success'])) {
        $session->set('inwave_message', $utility->getMessage(__($msg['error'] . $msg['success']), 'notice'));
    } elseif (isset($msg['error']) && !isset($msg['success'])) {
        $session->set('inwave_message', $utility->getMessage(__($msg['error']), 'error'));
    } elseif (!isset($msg['error']) && isset($msg['success'])) {
        $session->set('inwave_message', $utility->getMessage(__($msg['success'])));
    } else {
        $session->set('inwave_message', $utility->getMessage(__('Unknown error', 'inwavethemes')));
    }
    wp_redirect(admin_url('edit.php?post_type=infunding&page=logs'));
}

function inf_site_name($atts) {
    return get_option('blogname');
}

function inf_customer_name($atts) {
    global $inf_order;
    return $inf_order->member;
}

function inf_order_link($atts) {
    global $inf_order, $inf_settings;
    $order_link = get_permalink($inf_settings['general']['payment_check_page']);
    if (strpos('?', $order_link)) {
        $order_link .= '&ordercode=' . $inf_order->order->getId();
    } else {
        $order_link .= '?ordercode=' . $inf_order->order->getId();
    }
    return '<a href="' . $order_link . '">' . __('View donation info', 'inwavethemes') . '</a>';
}

function inf_reason($atts) {
    global $inf_order;
    return $inf_order->reason;
}

function inf_new_order_status($atts) {
    global $inf_order;
    $stext = '';
    switch ($inf_order->order->getStatus()) {
        case 1:
            $stext = __('Pending', 'inwavethemes');
            break;
        case 2:
            $stext = __('Paid', 'inwavethemes');
            break;
        case 3:
            $stext = __('Cancel', 'inwavethemes');
            break;
        case 4:
            $stext = __('Onhold', 'inwavethemes');
            break;
        default:
            break;
    }
    return $stext;
}

function inf_user_status() {
    global $inf_order;
    return $inf_order->order->getMember()->getStatus();
}

function inf_user_info($atts) {
    global $inf_order;
    $member = $inf_order->order->getMember();
    return;
}

if (!function_exists('acceptVolunter')) {

    function acceptVolunter() {
        $id = filter_input(INPUT_POST, 'id');
        $volunteer = new inFundingVolunteer();
        $acceptVolunter = unserialize($volunteer->acceptVolunter($id));
        if ($acceptVolunter['success']) {
            $acceptVolunter['text'] = __('Accepted', 'inwavethemes');
        }
        echo json_encode($acceptVolunter);
        exit;
    }

}

if (!function_exists('initPluginSettings')) {

    function initPluginSettings() {
        global $inf_settings;
        $inf_settings = unserialize(get_option('inf_settings'));

        if (!$inf_settings) {
            update_option('inf_settings', 'a:4:{s:7:"general";a:11:{s:8:"currency";s:3:"EUR";s:12:"currency_pos";s:4:"left";s:12:"funding_slug";s:8:"campaign";s:13:"category_slug";s:12:"inf-category";s:8:"tag_slug";s:7:"inf-tag";s:16:"inf_payment_page";s:3:"597";s:18:"payment_check_page";s:0:"";s:11:"member_page";s:3:"554";s:10:"google_api";s:0:"";s:14:"map_zoom_level";s:1:"8";s:22:"allow_anonymous_donate";s:1:"0";}s:20:"register_form_fields";a:6:{i:0;a:8:{s:5:"label";s:9:"Full Name";s:4:"name";s:9:"full_name";s:5:"group";s:0:"";s:4:"type";s:4:"text";s:6:"values";s:0:"";s:13:"default_value";s:0:"";s:12:"show_on_list";s:1:"1";s:13:"require_field";s:1:"1";}i:1;a:8:{s:5:"label";s:7:"Address";s:4:"name";s:7:"address";s:5:"group";s:0:"";s:4:"type";s:4:"text";s:6:"values";s:0:"";s:13:"default_value";s:0:"";s:12:"show_on_list";s:1:"1";s:13:"require_field";s:1:"1";}i:2;a:8:{s:5:"label";s:5:"Email";s:4:"name";s:5:"email";s:5:"group";s:0:"";s:4:"type";s:5:"email";s:6:"values";s:0:"";s:13:"default_value";s:0:"";s:12:"show_on_list";s:1:"1";s:13:"require_field";s:1:"1";}i:3;a:8:{s:5:"label";s:5:"Phone";s:4:"name";s:5:"phone";s:5:"group";s:0:"";s:4:"type";s:4:"text";s:6:"values";s:0:"";s:13:"default_value";s:0:"";s:12:"show_on_list";s:1:"1";s:13:"require_field";s:1:"1";}i:4;a:8:{s:5:"label";s:54:"I am a UK taxpayer and my gift qualifies for Gift Aid.";s:4:"name";s:8:"gift_aid";s:5:"group";s:0:"";s:4:"type";s:8:"checkbox";s:6:"values";s:0:"";s:13:"default_value";s:1:"0";s:12:"show_on_list";s:1:"0";s:13:"require_field";s:1:"0";}i:5;a:8:{s:5:"label";s:116:"Please subscribe me to InCharity newsletter, keeping me up-to-date with the projects my donation is helping to fund.";s:4:"name";s:9:"subscribe";s:5:"group";s:0:"";s:4:"type";s:8:"checkbox";s:6:"values";s:0:"";s:13:"default_value";s:1:"1";s:12:"show_on_list";s:1:"0";s:13:"require_field";s:1:"0";}}s:11:"inf_payment";a:2:{s:6:"paypal";a:3:{s:5:"email";s:0:"";s:9:"test_mode";s:1:"1";s:6:"status";s:1:"1";}s:14:"custom_payment";a:2:{s:7:"content";s:108:"Thanks for your donation!
You can send money via bank or direct to us at Angel Building, 407 St John Street";s:6:"status";s:1:"1";}}s:14:"email_template";a:3:{s:10:"order_info";a:2:{s:5:"title";s:17:"Order email title";s:7:"content";s:175:"Hello, [inf_customer_name]

Thanks for donation on my site.
This is the link of your donation, you can check status and info via this link: [inf_order_link]

Thanks again";}s:13:"register_info";a:2:{s:5:"title";s:41:"Thanks for registering on [inf_site_name]";s:7:"content";s:135:"Hi, [inf_customer_name]

Thanks for registering on [inf_site_name].
This is the registration info: 
[inf_user_info]

Thanks again";}s:18:"order_change_state";a:2:{s:5:"title";s:19:"Order change status";s:7:"content";s:123:"Hi [inf_customer_name],

Your order on [inf_site_name] has been change to [inf_new_order_status]

Because: [inf_reason]";}}}');
            $inf_settings = unserialize(get_option('inf_settings'));
        }
    }

}

function inFundingScreen($current_screen) {
    if ('infunding' == $current_screen->post_type) {
        new Inwave_Session();
    }
}
