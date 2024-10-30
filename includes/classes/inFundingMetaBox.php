<?php
/*
 * @package Inwave Funding
 * @version 1.0.0
 * @created Mar 13, 2016
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of inFundingMetaBox
 *
 * @developer duongca
 */
class inFundingMetaBox {

    /**
     * Hook into the appropriate actions when the class is constructed.
     */
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'save'));
    }

    /**
     * Adds the meta box container.
     */
    public function add_meta_box($post_type) {
        if ($post_type == 'infunding') {
            add_meta_box(
                    'inf_imagegallery', __('Campaign Images', 'inwavethemes'), array($this, 'render_meta_box_campaign_images'), $post_type, 'advanced', 'high'
            );
            add_meta_box(
                    'inf_meta_box', __('Campaign Detail', 'inwavethemes'), array($this, 'render_meta_box_campaign_detail'), $post_type, 'advanced', 'high'
            );
        }
    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save($post_id) {
        
        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */

        // Check if our nonce is set.
        /* @var $_POST type */
        if (!isset($_POST['inf_post_metabox_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['inf_post_metabox_nonce'];

        // Verify that the nonce is valid.
        if (!wp_verify_nonce($nonce, 'inf_post_metabox')) {
            return $post_id;
        }

        // If this is an autosave, our form has not been submitted,
        //     so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // Check the user's permissions.
        $post_type = $_POST['post_type'];
        if ('page' == $post_type) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }
        }

        /* OK, its safe for us to save the data now. */

        $iw_information = $_POST['inf_information'];

        //Image gallery
        $image_gallery = isset($iw_information['image_gallery']) ? serialize($iw_information['image_gallery']) : serialize(array());


        //Event basic info
        $start_date = DateTime::createFromFormat(get_option('date_format'), $iw_information['inf_start_date'])->getTimestamp();
        $end_date = DateTime::createFromFormat(get_option('date_format'), $iw_information['inf_end_date'])->getTimestamp();
        $address = $iw_information['inf_address'];
        $donate_link = $iw_information['inf_donate_link'];
        $paypal_email = $iw_information['inf_paypal_email'];
        $goal = $iw_information['inf_goal'];
        $current = $iw_information['inf_current'];
        $currency = $iw_information['inf_currency'];
        $allow_volunteer = $iw_information['allow_volunteer'];
        $map_pos = isset($iw_information['map_pos'])?serialize($iw_information['map_pos']):  serialize(array());


        // Update the meta field.
        update_post_meta($post_id, 'inf_image_gallery', $image_gallery);
        update_post_meta($post_id, 'inf_start_date', $start_date);
        update_post_meta($post_id, 'inf_end_date', $end_date);
        update_post_meta($post_id, 'inf_address', $address);
        update_post_meta($post_id, 'inf_goal', $goal);
        update_post_meta($post_id, 'inf_current', $current);
        update_post_meta($post_id, 'inf_currency', $currency);
        update_post_meta($post_id, 'inf_map_pos', $map_pos);
        update_post_meta($post_id, 'inf_donate_link', $donate_link);
        update_post_meta($post_id, 'inf_paypal_email', $paypal_email);
        update_post_meta($post_id, 'inf_allow_volunteer', $allow_volunteer);
    }

    public function render_meta_box_campaign_images($post) {
        $value = get_post_meta($post->ID, 'inf_image_gallery', true);
        $image_gallery = unserialize($value);
        ?>
        <div class="inf-metabox-fields">
            <div class="list-image-gallery">
                <?php
                if ($image_gallery):
                    foreach ($image_gallery as $item) :
                    $img = wp_get_attachment_image_src($item,'large');
                        ?>
                        <div class="iw-image-item">
                            <div class="action-overlay">
                                <span class="remove-image">x</span>
                            </div>
                            <img width="150" src="<?php echo esc_url($img[0]); ?>"/>
                            <input type="hidden" name="inf_information[image_gallery][]" value="<?php echo esc_attr($item); ?>"/>
                        </div>
                        <?php
                    endforeach;
                endif;
                ?>
            </div>
            <div style="clear: both;"></div>
            <div class="button-add-image">
                <span class="button add-new-image"><?php echo __('Add new images', 'inwavethemes'); ?></span>
            </div>
        </div>
        <?php
    }

    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_campaign_detail($post) {
        // Add an nonce field so we can check for it later.
        global $inf_settings;
        $inFundingUtility = new inFundingUtility();
        wp_nonce_field('inf_post_metabox', 'inf_post_metabox_nonce');
        ?>
        <table class="list-table">
            <tbody class="the-list">
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Start date', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <?php
                        $time_start = htmlspecialchars(get_post_meta($post->ID, 'inf_start_date', true));
                        ?>
                        <input required="required" class="input-date start-date" value="<?php echo isset($time_start) && $time_start ? date(get_option('date_format'), $time_start) : ''; ?>" type="text" placeholder="<?php echo __(date('m/d/Y', time()), 'inwavethemes'); ?>" name="inf_information[inf_start_date]"/>
                        <div class="description"><?php echo sprintf(__('Campaign start date (%s). Ex: %s','inwavethemes'),get_option('date_format'), date(get_option('date_format'), time() + 864000)); ?></div>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <label><?php echo __('End date', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <?php
                        $time_end = htmlspecialchars(get_post_meta($post->ID, 'inf_end_date', true));
                        ?>
                        <input class="input-date end-date" value="<?php echo isset($time_end) && $time_end ? date(get_option('date_format'), $time_end) : ''; ?>" type="text" placeholder="<?php echo __(date('m/d/Y', time() + 864000), 'inwavethemes'); ?>" name="inf_information[inf_end_date]"/>
                        <div class="description"><?php echo sprintf(__('Campaign end date (%s). Ex: %s','inwavethemes'),get_option('date_format'), date(get_option('date_format'), time() + 864000)); ?></div>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Address', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <?php
                        $address = htmlspecialchars(get_post_meta($post->ID, 'inf_address', true));
                        ?>
                        <input required="required" type="text" value="<?php echo isset($address) ? $address : ''; ?>" name="inf_information[inf_address]"/>
                        <div class="description"><?php _e('Campaign address', 'inwavethemes'); ?></div>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Map latitude', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <?php
                        $map_pos = unserialize(get_post_meta($post->ID, 'inf_map_pos', true));
                        ?>
                        <input type="text" value="<?php echo isset($map_pos['latitude']) ? $map_pos['latitude'] : ''; ?>" name="inf_information[map_pos][latitude]"/>
                        <div class="description"><?php _e('Map Latitude Position. If not set, map position get by address', 'inwavethemes'); ?></div>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Map Longitude', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <input type="text" value="<?php echo isset($map_pos['longitude']) ? $map_pos['longitude'] : ''; ?>" name="inf_information[map_pos][longitude]"/>
                        <div class="description"><?php _e('Map Longitude Position. If not set, map position get by address', 'inwavethemes'); ?></div>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Goal', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <?php
                        $goal = htmlspecialchars(get_post_meta($post->ID, 'inf_goal', true));
                        ?>
                        <input class="goal" type="text" title="<?php echo __('Input a number', 'inwavethemes'); ?>" value="<?php echo isset($goal) ? $goal : ''; ?>" name="inf_information[inf_goal]"/>
                        <div class="description"><?php _e('Goal of campaign', 'inwavethemes'); ?></div>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Current', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <?php
                        $current = htmlspecialchars(get_post_meta($post->ID, 'inf_current', true));
                        ?>
                        <input type="text" pattern="^[0-9]*$" required="required" title="<?php echo __('Input a number', 'inwavethemes'); ?>" value="<?php echo isset($current) ? $current : '0'; ?>" name="inf_information[inf_current]"/>
                        <div class="description"><?php _e('The current amount of the campaign', 'inwavethemes'); ?></div>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Currency', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <?php
                        $currency = htmlspecialchars(get_post_meta($post->ID, 'inf_currency', true));
                        $inFundingUtility->selectFieldRender('', 'inf_information[inf_currency]', $currency?$currency:$inf_settings['general']['currency'], $inFundingUtility->getIWEventcurrencies(), '', '', FALSE);
                        ?>
                        <div class="description"><?php _e('Current value of campaign', 'inwavethemes'); ?></div>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Allow Register Volunteer', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <?php
                        $allow_volunteer = htmlspecialchars(get_post_meta($post->ID, 'inf_allow_volunteer', true));
                        $volunteers = array(
                            array('text'=>'No', 'value'=>0),
                            array('text'=>'Yes', 'value'=>1)
                        );
                        $inFundingUtility->selectFieldRender('', 'inf_information[allow_volunteer]', $allow_volunteer, $volunteers, '', '', FALSE);
                        ?>
                        <div class="description"><?php _e('Allow register volunteer for this campaign', 'inwavethemes'); ?></div>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <label><?php echo __('External Donation Link', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <?php
                        $external_link = htmlspecialchars(get_post_meta($post->ID, 'inf_donate_link', true));
                        ?>
                        <input type="url" title="<?php echo __('Input a valid link', 'inwavethemes'); ?>" value="<?php echo isset($external_link) ? $external_link : ''; ?>" name="inf_information[inf_donate_link]"/>
                        <div class="description"><?php _e('Enter your external donation link from external donation websites (justgiving.com, raisemoney.com)', 'inwavethemes'); ?></div>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Paypal email', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <?php
                        $paypal_email = htmlspecialchars(get_post_meta($post->ID, 'inf_paypal_email', true));
                        ?>
                        <input type="email" title="<?php echo __('Input a valid email', 'inwavethemes'); ?>" value="<?php echo isset($paypal_email) ? $paypal_email : ''; ?>" name="inf_information[inf_paypal_email]"/>
                        <div class="description"><?php _e('Paypal email is used to receive donations from this campaign.', 'inwavethemes'); ?></div>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
    }

}
