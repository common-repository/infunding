<?php
/*
 * @package Inwave Infunding
 * @version 1.0.0
 * @created May 15, 2016
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of settings
 *
 * @developer duongca
 */
$default_options = array(
    'register_form_fields' =>
    array(
        array(
            'label' => 'Full Name',
            'name' => 'full_name',
            'type' => 'text',
            'values' => '',
            'show_on_list' => 1,
            'require_field' => 1,
            'default_value' => '',
            'group' => ''
        ),
        array(
            'label' => 'Address',
            'name' => 'address',
            'type' => 'text',
            'values' => '',
            'show_on_list' => 1,
            'require_field' => 1,
            'default_value' => '',
            'group' => ''
        ),
        array(
            'label' => 'Email',
            'name' => 'email',
            'type' => 'email',
            'values' => '',
            'show_on_list' => 1,
            'require_field' => 1,
            'default_value' => '',
            'group' => ''
        ),
        array(
            'label' => 'Phone',
            'name' => 'phone',
            'type' => 'text',
            'values' => '',
            'show_on_list' => 1,
            'require_field' => 1,
            'default_value' => '',
            'group' => ''
        )
    ),
    'inf_paypal_email' => 'duongca@ymail.com',
    'email_template' => array(
        'order_info' =>
        array(
            'title' => 'Order email title',
            'content' => 'Order email content'
        ),
        'register_info' =>
        array(
            'title' => 'Member title',
            'content' => 'Member content'
        ),
        'order_change_state' =>
        array(
            'title' => 'State title',
            'content' => 'State content'
        )
    )
);
$utility = new inFundingUtility();
global $inf_settings;
if (!$inf_settings) {
    $inf_settings = $default_options;
}

wp_enqueue_script('jquery-ui-sortable');
?>
<form action="<?php echo admin_url(); ?>admin-post.php" method="post">
    <div id="" class="iw-tabs event-detail layout2">
        <div class="iw-tab-items">
            <div class="iw-tab-item active">
                <div class="iw-tab-title"><span><?php _e('General', 'inwavethemes'); ?></span></div>
            </div>
            <div class="iw-tab-item">
                <div class="iw-tab-title"><span><?php _e('Registration form', 'inwavethemes'); ?></span></div>
            </div>
            <div class="iw-tab-item">
                <div class="iw-tab-title"><span><?php _e('Payment', 'inwavethemes'); ?></span></div>
            </div>
            <div class="iw-tab-item">
                <div class="iw-tab-title"><span><?php _e('Message template', 'inwavethemes'); ?></span></div>
            </div>
            <div style="clear: both;"></div>
        </div>
        <div class="iw-tab-content">
            <div class="iw-tab-item-content">
                <?php
                $general = $inf_settings['general'];
                ?>
                <table class="list-table">
                    <tbody class="the-list">
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Currency', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <?php
                                $data = $utility->getIWEventcurrencies();
                                $utility->selectFieldRender('', 'inf_settings[general][currency]', ($general['currency'] ? $general['currency'] : 'USD'), $data, '', '', FALSE);
                                ?>
                            </td>
                            <td>
                                <span class="description"><?php _e('Currency for payment', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Currency Position', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <?php
                                $data_cpos = array(
                                    array('text' => __('Left', 'inwavethemes'), 'value' => 'left'),
                                    array('text' => __('Left with space', 'inwavethemes'), 'value' => 'left_space'),
                                    array('text' => __('Right', 'inwavethemes'), 'value' => 'right'),
                                    array('text' => __('Right with space', 'inwavethemes'), 'value' => 'right_space')
                                );
                                $utility->selectFieldRender('', 'inf_settings[general][currency_pos]', ($general['currency_pos'] ? $general['currency_pos'] : 'left'), $data_cpos, '', '', FALSE);
                                ?>
                            </td>
                            <td>
                                <span class="description"><?php _e('Currency position', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Campaign slug', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <input type="text" value="<?php echo esc_attr($general['funding_slug']) ? esc_attr($general['funding_slug']) : 'infunding'; ?>" name="inf_settings[general][funding_slug]"/>
                            </td>
                            <td>
                                <span class="description"><?php _e('Slug for Campaign', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Category slug', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <input type="text" value="<?php echo esc_attr($general['category_slug']) ? esc_attr($general['category_slug']) : 'inf-category'; ?>" name="inf_settings[general][category_slug]"/>
                            </td>
                            <td>
                                <span class="description"><?php _e('Slug for campaign category', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Tag slug', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <input type="text" value="<?php echo isset($general['tag_slug']) ? $general['tag_slug'] : 'inf-tag'; ?>" name="inf_settings[general][tag_slug]"/>
                            </td>
                            <td>
                                <span class="description"><?php _e('Slug for campaign tag', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Payment page', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <?php
                                $pages = get_pages();
                                $page_data = array();
                                foreach ($pages as $page) {
                                    $page_data[] = array('text' => $page->post_title, 'value' => $page->ID);
                                }
                                $utility->selectFieldRender('payment_page', 'inf_settings[general][inf_payment_page]', isset($general['inf_payment_page']) ? $general['inf_payment_page'] : '', $page_data, 'Select Page', '', false);
                                ?>
                            </td>
                            <td>
                                <span class="description"><?php _e('Payment page', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Payment check page', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <?php
                                $utility->selectFieldRender('payment_check_page', 'inf_settings[general][payment_check_page]', isset($general['payment_check_page']) ? $general['payment_check_page'] : '', $page_data, 'Select Page', '', false);
                                ?>
                            </td>
                            <td>
                                <span class="description"><?php _e('Page to check status of order', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Member page', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <?php
                                $utility->selectFieldRender('member_page', 'inf_settings[general][member_page]', isset($general['member_page']) ? $general['member_page'] : '', $page_data, 'Select Page', '', false);
                                ?>
                            </td>
                            <td>
                                <span class="description"><?php _e('Member page', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Google API', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <input type="text" value="<?php echo esc_attr($general['google_api']) ? esc_attr($general['google_api']) : ''; ?>" name="inf_settings[general][google_api]"/>
                            </td>
                            <td>
                                <span class="description"><?php _e('API to use google map embed. To get Google api, you can access via <a target="_blank" href="https://console.developers.google.com/">here</a>', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Map Zoom Level', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <input type="text" value="<?php echo isset($general['map_zoom_level']) ? $general['map_zoom_level'] : '8'; ?>" name="inf_settings[general][map_zoom_level]"/>
                            </td>
                            <td>
                                <span class="description"><?php _e('Google map zoom level init value', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                        <tr class="alternate">
                            <td>
                                <label><?php echo __('Allow anonymous donation', 'inwavethemes'); ?></label>
                            </td>
                            <td>
                                <?php
                                $utility->selectFieldRender('', 'inf_settings[general][allow_anonymous_donate]', (isset($general['allow_anonymous_donate']) ? $general['allow_anonymous_donate'] : '1'), array(array('text' => __('Yes', 'inwavethemes'), 'value' => '1'), array('text' => __('No', 'inwavethemes'), 'value' => '0')), '', '', FALSE);
                                ?>
                            </td>
                            <td>
                                <span class="description"><?php _e('Show/Hide anonymous donation field on donate form', 'inwavethemes'); ?></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="iw-tab-item-content iw-hidden">
                <table id="sortable" class="list-table iwe-field-manager">
                    <thead>
                        <tr class="field-mng-heading">
                            <th colspan="10"><?php _e('Field manager', 'inwavethemes'); ?></th>
                        </tr>
                        <tr>
                            <th><?php _e('Order', 'inwavethemes'); ?></th>
                            <th><?php _e('Label', 'inwavethemes'); ?></th>
                            <th><?php _e('Name', 'inwavethemes'); ?></th>
                            <th><?php _e('Group', 'inwavethemes'); ?></th>
                            <th><?php _e('Type', 'inwavethemes'); ?></th>
                            <th class="field-values"><?php _e('Values', 'inwavethemes'); ?></th>
                            <th><?php _e('Default Value', 'inwavethemes'); ?></th>
                            <th><abbr title="<?php _e('Show on List', 'inwavethemes'); ?>"><?php _e('SL', 'inwavethemes'); ?></abbr></th>
                            <th><abbr title="<?php _e('Require Field', 'inwavethemes'); ?>"><?php _e('RF', 'inwavethemes'); ?></abbr></th>
                            <th><?php _e('Actions', 'inwavethemes'); ?></th>
                        </tr>
                    </thead>
                    <tbody class="the-list">
                        <?php
                        if (!empty($inf_settings['register_form_fields'])):
                            foreach ($inf_settings['register_form_fields'] as $field) :
                                ?>
                                <tr class="alternate">
                                    <td class="iwe-sortable-cell">
                                        <span><i class="fa fa-arrows"></i></span>
                                    </td>
                                    <td>
                                        <input type="text" value="<?php echo stripslashes($field['label']) ?>" class="field-label" name="inf_settings[register_form_fields][label][]"/>
                                    </td>
                                    <td>
                                        <input type="text" readonly="readonly" value="<?php echo esc_attr($field['name']) ?>" class="field-name" name="inf_settings[register_form_fields][name][]"/>
                                    </td>
                                    <td>
                                        <input type="text" value="<?php echo esc_attr($field['group']) ?>" class="field-group" name="inf_settings[register_form_fields][group][]"/>
                                    </td>
                                    <td>
                                        <?php
                                        $data = array(
                                            array('text' => 'String', 'value' => 'text'),
                                            array('text' => 'Select', 'value' => 'select'),
                                            array('text' => 'Text', 'value' => 'textarea'),
                                            array('text' => 'Email', 'value' => 'email'),
                                            array('text' => 'Date', 'value' => 'date'),
                                            array('text' => 'Checkbox', 'value' => 'checkbox'),
                                            array('text' => 'Custom Regex', 'value' => 'custom_regex')
                                        );
                                        $utility->selectFieldRender('', 'register_form_fields_type', $field['type'], $data, '', 'select-field-type', FALSE, 'disabled="disabled"');
                                        ?>
                                        <input type="hidden" name="inf_settings[register_form_fields][type][]" value="<?php echo esc_attr($field['type']); ?>"/>
                                    </td>
                                    <td class="field-values">
                                        <?php
                                        switch ($field['type']) {
                                            case 'select':
                                                $options = array();
                                                foreach ($field['values'] as $value) {
                                                    $options[] = implode('|', $value);
                                                }
                                                $field_value = implode("\n", $options);
                                                echo '<textarea placeholder="value|Text" name="inf_settings[register_form_fields][values][]">' . $field_value . '</textarea><span class="description">' . __('Multiple value with new line', 'inwavethemes') . '</span>';
                                                break;
                                            case 'custom_regex':
                                                echo '<input class="custom-regex-patten" type="text" value="' . $field['values'] . '" placeholder="[A-Za-z0-9]{5}" name="inf_settings[register_form_fields][values][]"/><span class="description">Pattern to apply for field</span>';
                                                break;
                                            default:
                                                echo __('No default value for this field type.', 'inwavethemes');
                                                echo '<input type="hidden" name="inf_settings[register_form_fields][values][]" value=""/>';
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td class="default-value">
                                        <?php
                                        switch ($field['type']) {
                                            case 'select':
                                                $utility->selectFieldRender('', 'inf_settings[register_form_fields][default_value][]', $field['default_value'], $field['values'], '', '', FALSE);
                                                break;
                                            case 'textarea':
                                                echo '<textarea name="inf_settings[register_form_fields][default_value][]">' . esc_textarea($field['default_value']) . '</textarea>';
                                                break;
                                            case 'date':
                                                echo '<input type="date" value="' . esc_attr($field['default_value']) . '" name="inf_settings[register_form_fields][default_value][]"/>';
                                                break;
                                            case 'email':
                                                echo '<input type="email" value="' . esc_attr($field['default_value']) . '" name="inf_settings[register_form_fields][default_value][]"/>';
                                                break;
                                            case 'custom_regex':
                                                echo '<input type="text" class="custom-regex-val" pattern="' . esc_attr($field['values']) . '" value="' . esc_attr($field['default_value']) . '" name="inf_settings[register_form_fields][default_value][]"/>';
                                                break;
                                            case 'checkbox':
                                                $field_values = array(
                                                    array('text' => __('Checked', 'inwavethemes'), 'value' => '1'),
                                                    array('text' => __('Unchecked', 'inwavethemes'), 'value' => '0')
                                                );
                                                $utility->selectFieldRender('', 'inf_settings[register_form_fields][default_value][]', $field['default_value'], $field_values, '', '', FALSE);
                                                break;
                                            default:
                                                echo '<input type="text" value="' . $field['default_value'] . '" name="inf_settings[register_form_fields][default_value][]"/>';
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" name="show_on_list" <?php echo ($field['show_on_list']) ? 'checked="checked"' : ''; ?> class="show_on_list"/>
                                        <input class="iwe_field_val" type="hidden" value="<?php echo ($field['show_on_list']); ?>" name="inf_settings[register_form_fields][show_on_list][]"/>
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" name="require_field" <?php echo ($field['require_field']) ? 'checked="checked"' : ''; ?> class="require_field"/>
                                        <input class="iwe_field_val" type="hidden" value="<?php echo ($field['require_field']); ?>" name="inf_settings[register_form_fields][require_field][]"/>
                                    </td>
                                    <td>
                                        <?php if ($field['name'] != 'email' && $field['name'] != 'full_name' && $field['name'] != 'address'): ?>
                                            <span class="button remove_field"><?php _e('Remove', 'inwavethemes'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th><?php _e('Order', 'inwavethemes'); ?></th>
                            <th><?php _e('Label', 'inwavethemes'); ?></th>
                            <th><?php _e('Name', 'inwavethemes'); ?></th>
                            <th><?php _e('Group', 'inwavethemes'); ?></th>
                            <th><?php _e('Type', 'inwavethemes'); ?></th>
                            <th class="field-values"><?php _e('Values', 'inwavethemes'); ?></th>
                            <th><?php _e('Default Value', 'inwavethemes'); ?></th>
                            <th><abbr title="<?php _e('Show on List', 'inwavethemes'); ?>"><?php _e('SL', 'inwavethemes'); ?></abbr></th>
                            <th><abbr title="<?php _e('Require Field', 'inwavethemes'); ?>"><?php _e('RF', 'inwavethemes'); ?></abbr></th>
                            <th><?php _e('Actions', 'inwavethemes'); ?></th>
                        </tr>
                    </tfoot>
                </table>
                <div class="button-add-field">
                    <span class="button add-rgister-field"><?php _e('Add new field', 'inwavethemes'); ?></span>
                </div>
            </div>
            <div class="iw-tab-item-content iw-hidden">
                <div class="payment-setting-wrap">
                    <?php
                    $payment = $inf_settings['inf_payment'];
                    $paypal = $payment['paypal'];
                    ?>
                    <table class="list-table">
                        <tbody class="the-list">
                            <tr class="t-heading">
                                <th colspan="3"><?php _e('PAYPAL', 'inwavethemes'); ?></th>
                            </tr>
                            <tr class="alternate">
                                <td>
                                    <label><?php echo __('Paypal email', 'inwavethemes'); ?></label>
                                </td>
                                <td>
                                    <input class="iwe-paypal-email" value="<?php echo esc_attr($paypal['email']) ? esc_attr($paypal['email']) : ''; ?>" type="text" placeholder="<?php echo __('example@domain.com', 'inwavethemes'); ?>" name="inf_settings[inf_payment][paypal][email]"/>
                                </td>
                                <td>
                                    <span class="description"><?php _e('Paypal email to use in payment', 'inwavethemes'); ?></span>
                                </td>
                            </tr>
                            <tr class="alternate">
                                <td>
                                    <label><?php echo __('Test mode', 'inwavethemes'); ?></label>
                                </td>
                                <td>
                                    <select name="inf_settings[inf_payment][paypal][test_mode]">
                                        <option value="0"<?php echo esc_attr($paypal['test_mode']) == 0 ? ' selected="selected"' : ''; ?>><?php _e('No', 'inwavethemes'); ?></option>
                                        <option value="1"<?php echo esc_attr($paypal['test_mode']) == 1 ? ' selected="selected"' : ''; ?>><?php _e('Yes', 'inwavethemes'); ?></option>
                                    </select>
                                </td>
                                <td>
                                    <span class="description"><?php _e('Enable test mode for paypal checkout', 'inwavethemes'); ?></span>
                                </td>
                            </tr>
                            <tr class="alternate">
                                <td>
                                    <label><?php echo __('Status', 'inwavethemes'); ?></label>
                                </td>
                                <td>
                                    <select name="inf_settings[inf_payment][paypal][status]">
                                        <option value="0"<?php echo isset($paypal['status']) && $paypal['status'] == 0 ? ' selected="selected"' : ''; ?>><?php _e('Disable', 'inwavethemes'); ?></option>
                                        <option value="1"<?php echo isset($paypal['status']) && $paypal['status'] == 1 ? ' selected="selected"' : ''; ?>><?php _e('Enable', 'inwavethemes'); ?></option>
                                    </select>
                                </td>
                                <td>
                                    <span class="description"><?php _e('Enable or disable this payment method', 'inwavethemes'); ?></span>
                                </td>
                            </tr>
                            <tr class="t-heading">
                                <th colspan="3"><?php _e('OFFLINE PAYMENT', 'inwavethemes'); ?></th>
                            </tr>
                            <tr class="alternate">
                                <td>
                                    <label><?php echo __('Content', 'inwavethemes'); ?></label>
                                </td>
                                <td>
                                    <textarea class="inf-custompayment" placeholder="<?php echo __('Some text about Offline Payment', 'inwavethemes'); ?>" name="inf_settings[inf_payment][custom_payment][content]"><?php echo isset($payment['custom_payment']['content']) ? $payment['custom_payment']['content'] : ''; ?></textarea>
                                </td>
                                <td>
                                    <span class="description"><?php _e('Some content about custom payment', 'inwavethemes'); ?></span>
                                </td>
                            </tr>
                            <tr class="alternate">
                                <td>
                                    <label><?php echo __('Status', 'inwavethemes'); ?></label>
                                </td>
                                <td>
                                    <select name="inf_settings[inf_payment][custom_payment][status]">
                                        <option value="0"<?php echo isset($payment['custom_payment']['status']) && $payment['custom_payment']['status'] == 0 ? ' selected="selected"' : ''; ?>><?php _e('Disable', 'inwavethemes'); ?></option>
                                        <option value="1"<?php echo isset($payment['custom_payment']['status']) && $payment['custom_payment']['status'] == 1 ? ' selected="selected"' : ''; ?>><?php _e('Enable', 'inwavethemes'); ?></option>
                                    </select>
                                </td>
                                <td>
                                    <span class="description"><?php _e('Enable or disable this payment method', 'inwavethemes'); ?></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="iw-tab-item-content iw-hidden">
                <?php $email_template = $inf_settings['email_template']; ?>
                <div class="iw-tabs accordion day">
                    <div class="iw-accordion-item">
                        <div class="iw-accordion-header active">
                            <div class="iw-accordion-title"><span><?php echo __('ORDER INFO', 'inwavethemes'); ?></span></div>
                            <span class="iw-accordion-header-icon">
                                <i class="fa fa-times no-expand" style="display:none;"></i>
                                <i class="fa fa-times expand"></i>
                            </span>
                        </div>
                        <div class="iw-accordion-content">
                            <table class="list-table">
                                <tbody class="the-list">
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Title', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <input type="text" name="inf_settings[email_template][order_info][title]" value="<?php echo isset($email_template['order_info']['title']) ? $email_template['order_info']['title'] : ''; ?>" />
                                            <span class="description"><?php _e('Email title', 'inwavethemes'); ?></span>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Content', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <textarea rows="7" name="inf_settings[email_template][order_info][content]"><?php echo isset($email_template['order_info']['content']) ? $email_template['order_info']['content'] : ''; ?></textarea>
                                            <span class="description"><?php _e('Email content to send to customer when order success', 'inwavethemes'); ?></span>
                                        </td>
                                        <td>
                                            <strong><em><?php _e('Variables', 'inwavethemes'); ?></em></strong><br/>
                                            <strong>[inf_site_name]</strong>: <span><?php _e('Name of site', 'inwavethemes'); ?></span><br/>
                                            <strong>[inf_customer_name]</strong>: <span><?php _e('name of user or customer', 'inwavethemes'); ?></span><br/>
                                            <strong>[inf_order_link]</strong>: <span><?php _e('Order link view', 'inwavethemes'); ?></span><br/>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="iw-accordion-item">
                        <div class="iw-accordion-header">
                            <div class="iw-accordion-title"><span><?php _e('REGISTRATION SUCCESS', 'inwavethemes'); ?></span></div>
                            <span class="iw-accordion-header-icon">
                                <i class="fa fa-times no-expand" style="display:none;"></i>
                                <i class="fa fa-times expand"></i>
                            </span>
                        </div>
                        <div class="iw-accordion-content" style="display: none;">
                            <table class="list-table">
                                <tbody class="the-list">
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Title', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <input type="text" name="inf_settings[email_template][register_info][title]" value="<?php echo isset($email_template['register_info']['title']) ? $email_template['register_info']['title'] : ''; ?>" />
                                            <span class="description"><?php _e('Email title', 'inwavethemes'); ?></span>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Content', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <textarea rows="7" name="inf_settings[email_template][register_info][content]"><?php echo isset($email_template['register_info']['content']) ? $email_template['register_info']['content'] : ''; ?></textarea>
                                            <span class="description"><?php _e('Email content', 'inwavethemes'); ?></span>
                                        </td>
                                        <td>
                                            <strong><em><?php _e('Variables', 'inwavethemes'); ?></em></strong><br/>
                                            <strong>[inf_site_name]</strong>: <span><?php _e('Name of site', 'inwavethemes'); ?></span><br/>
                                            <strong>[inf_customer_name]</strong>: <span><?php _e('Name of user or customer', 'inwavethemes'); ?></span><br/>
                                            <strong>[inf_user_info]</strong>: <span><?php _e('User information', 'inwavethemes'); ?></span><br/>
                                            <strong>[inf_user_status]</strong>: <span><?php _e('User status', 'inwavethemes'); ?></span><br/>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="iw-accordion-item">
                        <div class="iw-accordion-header">
                            <div class="iw-accordion-title"><span><?php _e('CHANGING ORDER STATUS', 'inwavethemes'); ?></span></div>
                            <span class="iw-accordion-header-icon">
                                <i class="fa fa-times no-expand" style="display:none;"></i>
                                <i class="fa fa-times expand"></i>
                            </span>
                        </div>
                        <div class="iw-accordion-content" style="display: none;">
                            <table class="list-table">
                                <tbody class="the-list">
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Title', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <input type="text" name="inf_settings[email_template][order_change_state][title]" value="<?php echo isset($email_template['order_change_state']['title']) ? $email_template['order_change_state']['title'] : ''; ?>" />
                                            <span class="description"><?php _e('Email title', 'inwavethemes'); ?></span>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr class="alternate">
                                        <td>
                                            <label><?php echo __('Content', 'inwavethemes'); ?></label>
                                        </td>
                                        <td>
                                            <textarea rows="7" name="inf_settings[email_template][order_change_state][content]"><?php echo isset($email_template['order_change_state']['content']) ? $email_template['order_change_state']['content'] : ''; ?></textarea>
                                            <span class="description"><?php _e('Email content', 'inwavethemes'); ?></span>
                                        </td>
                                        <td>
                                            <strong><em><?php _e('Variables', 'inwavethemes'); ?></em></strong><br/>
                                            <strong>[inf_site_name]</strong>: <span><?php _e('Name of site', 'inwavethemes'); ?></span><br/>
                                            <strong>[inf_customer_name]</strong>: <span><?php _e('Name of user or customer', 'inwavethemes'); ?></span><br/>
                                            <strong>[inf_order_link]</strong>: <span><?php _e('Link to order information', 'inwavethemes'); ?></span><br/>
                                            <strong>[inf_new_order_status]</strong>: <span><?php _e('New Order status', 'inwavethemes'); ?></span><br/>
                                            <strong>[inf_reason]</strong>: <span><?php _e('Status change reason', 'inwavethemes'); ?></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="action" value="infSaveSettings"/>
    <div class="iwe-save-settings">
        <input disabled="disabled" class="button" type="submit" value="Save"/>
    </div>
</form>

<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $("#sortable tbody").sortable();
            $("#sortable tbody .iwe-sortable-cell").disableSelection();
        });
    })(jQuery);
</script>
