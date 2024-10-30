<?php
/*
 * @package Inwave Directory
 * @version 1.0.0
 * @created Mar 2, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of file: File contain all function to process in front page
 *
 * @developer duongca
 */
require_once 'utility.php';

function infunding_map_outhtml($atts) {
    extract(shortcode_atts(array(
        "category" => "0",
        "ids" => '',
        "show_des" => '1',
        "desc_text_limit" => 20,
        "show_location" => '1',
        "map" => '',
        "class" => ""
                    ), $atts));
    ob_start();
    $path = inFundingGetTemplatePath('infunding/infunding_map');
    if ($path) {
        include $path;
    } else {
        $inf_theme = INFUNDING_THEME_PATH . 'infunding_map.php';
        if (file_exists($inf_theme)) {
            include $inf_theme;
        } else {
            echo __('No theme was found', 'inwavethemes');
        }
    }
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function infunding_list_outhtml($atts) {
    wp_enqueue_script('custombox');
    extract(shortcode_atts(array(
        "category" => "0",
        "ids" => '',
        "order_by" => "date",
        "order_dir" => "desc",
        "limit" => 12,
        "show_des" => '1',
        "page" => 'page',
        "desc_text_limit" => 20,
        "style" => 'style1',
        "show_page_list" => '1',
        "show_time_icon" => '1',
        "show_location" => '1',
        "number_column" => '3',
        "show_filter_bar" => '1',
        "show_icon_action" => '1',
        "show_progress" => '1',
        "class" => ""
                    ), $atts));
    ob_start();
    $path = inFundingGetTemplatePath('infunding/list_campaigns');
    if ($path) {
        include $path;
    } else {
        $inf_theme = INFUNDING_THEME_PATH . 'list_campaigns.php';
        if (file_exists($inf_theme)) {
            include $inf_theme;
        } else {
            echo __('No theme was found', 'inwavethemes');
        }
    }
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function inf_checkorder_page_outhtml($atts) {
    //global $iwe_settings;
    $utility = new inFundingUtility();
    $order = new inFundingOrder();
    //$check_page = $iwe_settings['general']['payment_check_page'];
    $ord_code = filter_input(INPUT_GET, 'ordercode');
    ob_start();
    if ($ord_code) {
        $order = $order->getOrder($ord_code);
        if ($order->getId()) {
            include 'views/payment.view.php';
        } else {
            printf($utility->getMessage(__('No find order', 'inwavethemes'), 'notice'));
        }
    } else {
        printf($utility->getMessage(__('No order ID set', 'inwavethemes'), 'notice'));
    }
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function infunding_member_page_outhtml($atts) {
    global $inf_settings;
    $utility = new inFundingUtility();
    $member_page = $inf_settings['general']['member_page'];
    ob_start();
    echo '<div class="row">';
    if (get_current_user_id()) {
        $member = new inFundingMember();
        $member = $member->getMemberByUser(get_current_user_id());
        $action = isset($_REQUEST['pageview']) ? $_REQUEST['pageview'] : 'info';
        echo '<div class="iw-col-md-9">';
        switch ($action) {
            case 'edit':
                $member_data = $member->getMemberRowData($member->getId(), true);
                include 'views/member.edit.php';
                break;
            case 'orderview':
                $ordercode = filter_input(INPUT_GET, 'ordercode');
                echo do_shortcode('[infunding_check_order order="' . $ordercode . '"]');
                break;
            case 'order_list':
                $order = new inFundingOrder();
                $memberOrder = $order->getMemberOrder($member->getId());
                ?>
                <table class="order-list">
                    <tr>
                        <th><?php echo __('Order Code', 'inwavethemes'); ?></th>
                        <th><?php echo __('Money', 'inwavethemes'); ?></th>
                        <th><?php echo __('Note', 'inwavethemes'); ?></th>
                        <th><?php echo __('Paid Time', 'inwavethemes'); ?></th>
                        <th><?php echo __('Status', 'inwavethemes'); ?></th>
                    </tr>
                    <?php
                    if (!empty($memberOrder)) {
                        foreach ($memberOrder as $ord) {
                            echo '<tr>';
                            echo '<td><a href="' . get_permalink($member_page) . '?pageview=orderview&ordercode=' . $ord->getId() . '">' . $ord->getOrderCode($ord->getId()) . '</a></td>';
                            echo '<td>' . $ord->getSum_price() . '(' . $ord->getCurrentcy() . ')' . '</td>';
                            echo '<td>' . $ord->getNote() . '</td>';
                            echo '<td>' . ($ord->getTime_paymented() ? date('m/d/Y', $ord->getTime_paymented()) : __('Unpaid', 'inwavethemes')) . '</td>';
                            echo '<td>';
                            switch ($ord->getStatus()) {
                                case 1:
                                    _e('Pending', 'inwavethemes');
                                    break;
                                case 2:
                                    _e('Paid', 'inwavethemes');
                                    break;
                                case 3:
                                    _e('Cancel', 'inwavethemes');
                                    break;
                                case 4:
                                    _e('Onhold', 'inwavethemes');
                                    break;
                                default:
                                    break;
                            }
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5">' . __('You haven\'t order', 'inwavethemes') . '</td></tr>';
                    }
                    ?>
                </table>
                <?php
                break;

            default:
                $member_data = $member->getMemberRowData($member->getId());
                $member_info = $member_data['data'][0];
                foreach ($member_data['fields'] as $field):
                    if ($member_info[$field['name']]) {
                        echo '<div class="row-item">';
                        echo '<label class="label">' . $field['label'] . '</label>';
                        echo '<label class="value">' . $member_info[$field['name']] . '</label>';
                        echo '</div>';
                    }
                endforeach;
                break;
        }
        echo '</div>';
        echo '<div class="iw-col-md-3">';
        echo '<ul class="event-member-menu">';
        echo '<li><a href="' . get_permalink($member_page) . '">' . __('Accourt Info', 'inwavethemes') . '</a></li>';
        echo '<li><a href="' . get_permalink($member_page) . '?pageview=edit">' . __('Edit information', 'inwavethemes') . '</a></li>';
        echo '<li><a href="' . get_permalink($member_page) . '?pageview=order_list">' . __('Order List', 'inwavethemes') . '</a></li>';
        echo '</ul>';
        echo '</div>';
    } else {
        printf($utility->getMessage(__('You don\'t have permission to access this page. Please <a href="%s"><strong>Login</strong></a> to access this', 'inwavethemes'), 'notice'), wp_login_url(get_permalink($member_page)));
    }
    echo '</div>';
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function infunding_donate_form_outhtml($atts) {
    global $inf_settings;
    wp_enqueue_style('datetimepicker');
    wp_enqueue_script('datetimepicker');
    $utility = new inFundingUtility();
    extract(shortcode_atts(array(
        'popup' => 0,
        'fields' => 'all',
        'campaign' => '',
        'amount' => '',
        'method' => 'paypal',
        'anonymous' => 0
                    ), $atts));
    $fields = explode(',', $fields);
    $args = array(
        'post_type' => 'infunding',
        'numberposts' => '-1',
        'orderby' => 'meta_value_num',
        'meta_key' => 'inf_end_date',
        'post_status' => 'publish'
    );
    $campaigns = get_posts($args);
    $campaignData = array();
    foreach ($campaigns as $camp) {
        $campaignInfo = $utility->getCampaignInfo($camp->ID);
        if ($campaignInfo->status) {
            $campaignData[] = array('text' => $camp->post_title, 'value' => $camp->ID);
        }
    }
    $currency_symbol = inFundingUtility::getIWCurrencySymbol($inf_settings['general']['currency']);
    if (filter_input(INPUT_GET, 'campaign')) {
        $campaign = filter_input(INPUT_GET, 'campaign');
    }
    $amount = filter_input(INPUT_GET, 'amount');
    $method = filter_input(INPUT_GET, 'method');
    $anonymous = filter_input(INPUT_GET, 'anonymous');
    $newfields = array();
    if (!empty($fields)) {
        if (in_array('all', $fields)) {
            foreach ($inf_settings['register_form_fields'] as $field) {
                if ($field['group'])
                    $newfields[$field['group']][] = $field;
                else {
                    $newfields['default'][] = $field;
                }
            }
        } else {
            foreach ($inf_settings['register_form_fields'] as $field) {
                if (in_array($field['name'], $fields)) {
                    if ($field['group'])
                        $newfields[$field['group']][] = $field;
                    else {
                        $newfields['default'][] = $field;
                    }
                }
            }
        }
    }

    ob_start();
    $utility->getNoticeMessage();
    ?>
    <div class="inf-checkoutform <?php echo (isset($popup) && $popup == 1) ? 'popup' : ''; ?>">
        <form action="" method="post">
            <div class="no-float">
                <div class="donate-title iw-capital">
                    <h3 class="theme-color"><?php _e('Donate Details', 'inwavethemes'); ?></h3>
                    <?php
                    if ($popup) {
                        echo '<span class="close-donate"><i class="fa fa-times"></i></span>';
                    }
                    ?>
                </div>
                <div style="clear:both;"></div>
            </div>
            <div class="line"></div>
            <div class="field-group">
                <div class="inf-col-3">
                    <label class="control-label"><?php _e('Donation Amount*', 'inwavethemes') ?></label>
                </div>
                <div class="inf-col-9">
                    <input class="form-control" type="text" title="<?php _e('Input the number. eg: 123', 'inwavethemes') ?>" pattern="[0-9]*" name="amount" required="required" value="<?php echo esc_attr($amount); ?>"/>
                </div>
                <div style="clear:both;"></div>
            </div>
            <div class="field-group">
                <div class="inf-col-3">
                    <label class="control-label"><?php _e('Payment Method*', 'inwavethemes') ?></label>
                </div>
                <div class="inf-col-9">
                    <?php if ($inf_settings['inf_payment']['paypal']['status']): ?>
                        <input class="form-control" type="radio" required="required" name="payment_method" <?php echo (isset($method) && $method == 'paypal') ? 'checked="checked"' : ''; ?> value="paypal"/> <?php echo __('Paypal', 'inwavethemes'); ?>
                    <?php endif; ?>
                    <?php if ($inf_settings['inf_payment']['custom_payment']['status']): ?>
                        <input class="form-control" type="radio" required="required" name="payment_method" <?php echo (isset($method) && $method == 'offline') ? 'checked="checked"' : ''; ?> value="offline"/>  <?php echo __('Offline Payment', 'inwavethemes'); ?>
                    <?php endif; ?>
                    <?php if (!$inf_settings['inf_payment']['custom_payment']['status'] && !$inf_settings['inf_payment']['paypal']['status']): ?>
                        <?php echo __('Please enable one payment method in plugin settings', 'inwavethemes'); ?>
                    <?php endif; ?>
                </div>
                <div style="clear:both;"></div>
            </div>
            <div class="field-group">
                <div class="inf-col-3">
                    <label class="control-label"><?php _e('Campaign*', 'inwavethemes') ?></label>
                </div>
                <div class="inf-col-9">
                    <?php echo inFundingUtility::selectFieldRender('', 'campaign', $campaign, $campaignData, __('Select Campaign', 'inwavethemes'), 'form-control', false, 'required') ?>
                </div>
                <div style="clear:both;"></div>
            </div>
            <div class="field-group">
                <div class="inf-col-3">
                    <label class="control-label"><?php _e('Your Message', 'inwavethemes') ?></label>
                </div>
                <div class="inf-col-9">
                    <textarea name="message" class="form-control"></textarea>
                </div>
                <div style="clear:both;"></div>
            </div>
            <?php if ($inf_settings['general']['allow_anonymous_donate']) { ?>
                <div class="field-group">
                    <div class="inf-col-3">
                        <label class="control-label"><?php _e('Anonymous Donation?', 'inwavethemes') ?></label>
                    </div>
                    <div class="inf-col-9">
                        <input type="checkbox" value="1" <?php echo (isset($anonymous) && $anonymous == '1') ? 'checked="checked"' : ''; ?> name="anonymous"/> <?php _e('Check this box to hide personal info in our donators list', 'inwavethemes'); ?>
                    </div>
                    <div style="clear:both;"></div>
                </div>
            <?php } ?>
            <div class="personal-info">
                <div class="no-float">
                    <div class="donate-title iw-capital">
                        <h3 class="theme-color"><?php _e('Personal Info', 'inwavethemes'); ?></h3>
                    </div>
                    <div style="clear:both;"></div>
                </div>
                <div class="line"></div>
                <?php
                $curent_user = wp_get_current_user();
                $customer = new inFundingMember();
                if ($curent_user->ID) {
                    $customer = $customer->getMemberByUser($curent_user->ID);
                }
                $customer_data = array();
                if ($customer->getId()) {
                    $field_value = $customer->getField_value();
                    foreach ($field_value as $field) {
                        $customer_data[$field['name']] = $field['value'];
                    }
                }
                ?>
                <?php if (!empty($newfields)): ?>
                    <?php foreach ($newfields as $key => $fields) : ?>
                        <?php if (count($newfields) > 1): ?>
                            <fieldset>
                                <legend><?php echo esc_attr($key); ?></legend>
                            <?php endif; ?>
                            <?php
                            foreach ($fields as $field) :
                                $require = '';
                                if ($field['require_field']) {
                                    $require = 'required="required"';
                                }
                                if ($field['type'] != 'checkbox'):
                                    ?>
                                    <div class="field-group">
                                        <div class="inf-col-3">
                                            <label class="control-label"><?php echo stripslashes($field['label']) . ($field['require_field'] ? '*' : ''); ?></label>
                                        </div>
                                        <div class="inf-col-9">
                                            <?php
                                            switch ($field['type']):
                                                case 'select':
                                                    echo '<select class="form-control" name=' . $field['name'] . '>';
                                                    foreach ($field['values'] as $option) {
                                                        echo '<option ' . $require . ' value="' . $option['value'] . '" ' . (isset($customer_data[$field['name']]) ? ($option['value'] == $customer_data[$field['name']] ? 'selected="selected"' : '') : ($option['value'] == $field['default_value'] ? 'selected="selected"' : '')) . '>' . $option['text'] . '</option>';
                                                    }
                                                    echo '</select>';
                                                    break;
                                                case 'textarea':
                                                    echo '<textarea ' . $require . ' name="' . $field['name'] . '">' . (isset($customer_data[$field['name']]) ? $customer_data[$field['name']] : $field['default_value']) . '</textarea>';
                                                    break;
                                                case 'email':
                                                    echo '<input class="form-control" placeholder="' . stripslashes($field['label']) . '" ' . $require . ' type="email" value="' . (isset($customer_data[$field['name']]) ? $customer_data[$field['name']] : $field['default_value']) . '" name="' . $field['name'] . '"/>';
                                                    break;
                                                case 'date':
                                                    echo '<input class="datetimepicker-input form-control" placeholder="' . stripslashes($field['label']) . '" ' . $require . ' type="text" value="' . (isset($customer_data[$field['name']]) ? $customer_data[$field['name']] : $field['default_value']) . '" name="' . $field['name'] . '"/>';
                                                    break;
                                                default:
                                                    echo '<input class="form-control"  placeholder="' . stripslashes($field['label']) . '" ' . $require . ' type="text" value="' . (isset($customer_data[$field['name']]) ? $customer_data[$field['name']] : $field['default_value']) . '" name="' . $field['name'] . '"/>';
                                                    break;
                                            endswitch;
                                            ?>
                                        </div>
                                        <div style="clear:both;"></div>
                                    </div>
                                <?php else: ?>
                                    <div class="field-group">
                                        <div class="inf-col-3">
                                        </div>
                                        <div class="inf-col-9">
                                            <span class="field-input-checkbox"><input <?php echo esc_html($require); ?> value="1" <?php echo (isset($customer_data[$field['name']]) || $field['default_value'] ? 'checked="checked"' : '') ?> type="checkbox" name="<?php echo esc_html($field['name']); ?>" /></span><label class="control-label"><?php echo ($field['require_field'] ? '*' : '') . stripslashes($field['label']); ?></label>
                                        </div>
                                        <div style="clear:both;"></div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <?php if (count($newfields) > 1): ?>
                            </fieldset>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php
                endif;
                ?>
            </div>
            <input name="quantity" value="1" type="hidden"/>
            <div class="line"></div>
            <div class="field-group">
                <div class="inf-col-3">
                </div>
                <div class="inf-col-9">
                    <div class="buy-now donate-btn iw-button-effect">
                        <input type="hidden" value="infPaymentProcess" name="action"/>
                        <button type="submit" class="btn-event-checkout theme-bg"><span data-hover="<?php echo __('Donate', 'inwavethemes'); ?>" class="effect"><?php echo __('Donate', 'inwavethemes'); ?></span></button>
                        <?php
                        if ($popup) {
                            echo '<span class="close-donate button">' . __('Cancel', 'inwavethemes') . '</span>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div style="clear:both;"></div>
        </form>
    </div>
    <?php
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function infPaymentProcess() {
    global $inf_settings, $inf_order;
    $utility = new inFundingUtility();
    $session = new Inwave_Session();
    $inflog = new inFundingLog();
    $post = filter_input_array(INPUT_POST);
    $campaign = get_post(filter_input(INPUT_POST, 'campaign'));
    if ($post['quantity'] <= 0) {
        $session->set('inwave_message', $utility->getMessage(__('Purchase amount must be greater than 0', 'inwavethemes'), 'error'));
        wp_redirect($_SERVER['HTTP_REFERER']);
    } else {
        $anonymous = isset($post['anonymous']) ? sanitize_text_field($post['anonymous']) : 0;
        $member_process = array();
        $customer_id = 0;
        if (!$anonymous) {
            //Add member info
            $member = new inFundingMember();
            $memberinfo = $utility->prepareMemberFieldValue($post);
            $member->setField_value(serialize($memberinfo));
            $member->setMember_type('donor');
            $member_process = $member->updateMember($member);
            $customer_id = $member_process['data'];
        } else {
            $member_process['success'] = true;
        }

        if ($member_process['success']) {
            //Create tickets and order
            //order status: 1-pendding, 2-paid, 3-cancel, 4-onhold
            $order = new inFundingOrder();
            $order->setStatus('1');
            $order->setNote(filter_input(INPUT_POST, 'message'));
            $order->setTime_created(time());
            $order->setMember($customer_id);
            $order->setSum_price(filter_input(INPUT_POST, 'quantity') * filter_input(INPUT_POST, 'amount'));
            $order->setPayment_method(filter_input(INPUT_POST, 'payment_method'));
            $order->setCurrentcy($inf_settings['general']['currency']);
            $order->setCampaign(filter_input(INPUT_POST, 'campaign'));
            $addOrder = unserialize($order->addOrder($order));
            if ($addOrder['success']) {
                $inf_order->order = $order->getOrder($addOrder['data']);
                $inflog->addLog(new inFundingLog(NULL, 'success', time(), __('New order has been created', 'inwavethemes'), $order->getLink($addOrder['data'], 'View Order'), 'add_order'));
                $sendmail = unserialize($utility->sendEmail(filter_input(INPUT_POST, 'email'), array('full_name' => filter_input(INPUT_POST, 'full_name')), 'order_created'));
                if (!$sendmail['success']) {
                    $inflog->addLog(new inFundingLog(NULL, 'notice', time(), __('Can\'t send email to customer after creating order', 'inwavethemes')));
                }

                $orderCode = $order->getOrderCode($addOrder['data']);
                //create paypal request
                if (filter_input(INPUT_POST, 'payment_method') == 'paypal') {
                    $paypal = $inf_settings['inf_payment']['paypal'];
                    $paypal_email = $paypal['email'];
                    if (get_post_meta($campaign->ID, 'inf_paypal_email', true)) {
                        $paypal_email = get_post_meta($campaign->ID, 'inf_paypal_email', true);
                    }
                    if ($paypal['test_mode']) {
                        $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
                    } else {
                        $url = 'https://www.paypal.com/cgi-bin/webscr';
                    }
                    $params = array(
                        'cmd' => '_xclick',
                        'business' => $paypal_email,
                        'item_name' => $campaign->ID . '-' . $campaign->post_title . '-Donate',
                        'currency_code' => $inf_settings['general']['currency'],
                        'quantity' => $post['quantity'],
                        'notify_url' => admin_url('admin-ajax.php?action=infPaymentNotice'),
                        'return' => site_url(),
                        'cancel_return' => site_url(),
                        'amount' => $post['quantity'] * $post['amount'],
                        'item_number' => $orderCode
                    );
                    $query_string = http_build_query($params);
                    $url .= '?' . $query_string;
                } else {
                    if ($inf_settings['inf_payment']['custom_payment']['content']) {
                        $message_notice = $inf_settings['inf_payment']['custom_payment']['content'];
                    } else {
                        $message_notice = __('Thanks for your donation!', 'inwavethemes');
                    }
                    $session->set('inwave_message', $utility->getMessage($message_notice));
                    $sendmail = unserialize($utility->sendEmail(filter_input(INPUT_POST, 'email'), array('full_name' => filter_input(INPUT_POST, 'full_name')), 'offline_payment_notice'));
                    if (!$sendmail['success']) {
                        $inflog->addLog(new inFundingLog(NULL, 'notice', time(), __('Can\'t send email notice to custommer for ofline payment', 'inwavethemes')));
                    }
                    $url = $_SERVER['HTTP_REFERER'];
                }
                wp_redirect($url);
                return;
            } else {
                $inflog->addLog(new inFundingLog(NULL, 'error', time(), $addOrder['msg']));
            }
        } else {
            $inflog->addLog(new inFundingLog(NULL, 'error', time(), $member_process['msg']));
        }
        $session->set('inwave_message', $utility->getMessage(__('An error occurred in order progress, please contact site admin', 'inwavethemes'), 'error'));
        wp_redirect($_SERVER['HTTP_REFERER']);
    }
}

function infPaymentNotice() {
    global $inf_settings;
    $iwelog = new inFundingLog();

    $test_mode = $inf_settings['inf_payment']['paypal']['test_mode'];
// Read POST data
// reading posted data directly from $_POST causes serialization
// issues with array data in POST. Reading raw POST data from input stream instead.
    $raw_post_data = file_get_contents('php://input');
    $raw_post_array = explode('&', $raw_post_data);
    $myPost = array();
    foreach ($raw_post_array as $keyval) {
        $keyval = explode('=', $keyval);
        if (count($keyval) == 2)
            $myPost[$keyval[0]] = urldecode($keyval[1]);
    }
// read the post from PayPal system and add 'cmd'
    $req = 'cmd=_notify-validate';
    if (function_exists('get_magic_quotes_gpc')) {
        $get_magic_quotes_exists = true;
    }
    foreach ($myPost as $key => $value) {
        if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
            $value = urlencode(stripslashes($value));
        } else {
            $value = urlencode($value);
        }
        $req .= "&$key=$value";
    }
// Post IPN data back to PayPal to validate the IPN data is genuine
// Without this step anyone can fake IPN data
    if ($test_mode) {
        $paypal_url = "https://ipnpb.sandbox.paypal.com/cgi-bin/webscr";
    } else {
        $paypal_url = "https://ipnpb.paypal.com/cgi-bin/webscr";
    }

    $ch = curl_init($paypal_url);
    if ($ch == FALSE) {
        return FALSE;
    }
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    if (DEBUG == true) {
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
    }
// CONFIG: Optional proxy configuration
//curl_setopt($ch, CURLOPT_PROXY, $proxy);
//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
// Set TCP timeout to 30 seconds
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
// of the certificate as shown below. Ensure the file is readable by the webserver.
// This is mandatory for some environments.
//$cert = __DIR__ . "./cacert.pem";
//curl_setopt($ch, CURLOPT_CAINFO, $cert);
    $res = curl_exec($ch);
    if (curl_errno($ch) != 0) { // cURL error
        $msg = sprintf(__("Can't connect to PayPal to validate IPN message: %s", 'inwavethemes'), curl_error($ch));
        $iwelog->addLog(new inFundingLog(NULL, 'error', time(), $msg, ''));
        curl_close($ch);
        exit;
    }
// Inspect IPN validation result and act accordingly
// Split response headers and payload, a better way for strcmp
    $tokens = explode("\r\n\r\n", trim($res));
    $res = trim(end($tokens));
    if (strcmp($res, "VERIFIED") == 0) {
        $item_number = isset($_POST['item_number']) ? sanitize_text_field($_POST['item_number']) : null;
        $payment_status = isset($_POST['payment_status']) ? sanitize_text_field($_POST['payment_status']) : '';
        $order_id = intval(ltrim($item_number, '#'));
        $order = new inFundingOrder();
        $orderObj = $order->getOrder($order_id);
        $orderObj->setMember($orderObj->getMember()->getId());
        $old_status = $orderObj->getStatus();
        //order status: 1-pendding, 2-paid, 3-cancel, 4-onhold
        switch ($payment_status) {
            case 'Completed':
                if ($old_status != '2') {
                    $current = htmlspecialchars(get_post_meta($orderObj->getCampaign(), 'inf_current', true));
                    update_post_meta($orderObj->getCampaign(), 'inf_current', $current + $orderObj->getSum_price());
                }
                $orderObj->setStatus(2);
                $orderObj->setTime_paymented(time());
                $changeState = unserialize($order->editOrder($orderObj));
                if ($changeState['success']) {
                    $iwelog->addLog(new inFundingLog(NULL, 'success', time(), sprintf(__('Order %s has been changed status to Completed Paid', 'inwavethemes'), $item_number), $order->getLink($orderObj->getId(), __('View order', 'inwavethemes'))));
                } else {
                    $iwelog->addLog(new inFundingLog(NULL, 'error', time(), $changeState['msg'], $order->getLink($orderObj->getId(), __('View order', 'inwavethemes'))));
                }
                break;
            case 'Pending':
                if ($old_status == '2') {
                    $current = htmlspecialchars(get_post_meta($orderObj->getCampaign(), 'inf_current', true));
                    update_post_meta($orderObj->getCampaign(), 'inf_current', $current - $orderObj->getSum_price());
                }
                $orderObj->setStatus(1);
                $changeState = unserialize($order->editOrder($orderObj));
                if ($changeState['success']) {
                    $iwelog->addLog(new inFundingLog(NULL, 'notice', time(), sprintf(__('Order %s has been changed status to Pending', 'inwavethemes'), $item_number), $order->getLink($orderObj->getId(), __('View order', 'inwavethemes'))));
                } else {
                    $iwelog->addLog(new inFundingLog(NULL, 'error', time(), $changeState['msg'], $order->getLink($orderObj->getId(), __('View order', 'inwavethemes'))));
                }
                break;
            case 'Refunded':
                if ($old_status == '2') {
                    $current = htmlspecialchars(get_post_meta($orderObj->getCampaign(), 'inf_current', true));
                    update_post_meta($orderObj->getCampaign(), 'inf_current', $current - $orderObj->getSum_price());
                }
                $orderObj->setStatus(3);
                $changeState = unserialize($order->editOrder($orderObj));
                $ticket = new iwEventTicket();
                $ticket->updateTicketCancel($order_id);
                if ($changeState['success']) {
                    $iwelog->addLog(new inFundingLog(NULL, 'notice', time(), sprintf(__('Order %s was refunded, Status was changed to cancel', 'inwavethemes'), $item_number), $order->getLink($orderObj->getId(), __('View order', 'inwavethemes'))));
                } else {
                    $iwelog->addLog(new inFundingLog(NULL, 'error', time(), $changeState['msg'], $order->getLink($orderObj->getId(), __('View order', 'inwavethemes'))));
                }
                break;

            default:
                if ($old_status == '2') {
                    $current = htmlspecialchars(get_post_meta($orderObj->getCampaign(), 'inf_current', true));
                    update_post_meta($orderObj->getCampaign(), 'inf_current', $current - $orderObj->getSum_price());
                }
                $orderObj->setStatus(4);
                $changeState = unserialize($order->editOrder($orderObj));
                if ($changeState['success']) {
                    $iwelog->addLog(new inFundingLog(NULL, 'notice', time(), sprintf(__('Order %s has been changed status to Onhold', 'inwavethemes'), $item_number), $order->getLink($orderObj->getId(), __('View order', 'inwavethemes'))));
                } else {
                    $iwelog->addLog(new inFundingLog(NULL, 'error', time(), $changeState['msg'], $order->getLink($orderObj->getId(), __('View order', 'inwavethemes'))));
                }
                break;
        }
    } else if (strcmp($res, "INVALID") == 0) {
        $msg = "Invalid IPN request";
        $iwelog->addLog(new inFundingLog(NULL, 'error', time(), $msg, ''));
    }
}

function inFundingAddSiteScript() {
    global $inf_settings, $inwave_smof_data;
    wp_enqueue_style('font-awesome', plugins_url('/infunding/assets/css/font-awesome/css/font-awesome.min.css'));
    wp_enqueue_style('custombox', plugins_url('/infunding/assets/css/custombox.min.css'));
    wp_enqueue_style('iw-legacy', plugins_url('/infunding/assets/css/iw-legacy.css'));
    wp_enqueue_style('infsite-style', plugins_url('/infunding/assets/css/infunding_style.css'));
    wp_enqueue_style('infsite-slider', plugins_url('/infunding/assets/css/infunding_slider.css'));
    wp_register_style('datetimepicker', plugins_url('/infunding/assets/css/jquery.datetimepicker.css'));
    wp_enqueue_style('owl-carousel', plugins_url('/infunding/assets/css/owl.carousel.css'));
    wp_enqueue_style('owl-theme', plugins_url('/infunding/assets/css/owl.theme.css'));
    wp_enqueue_style('owl-transitions', plugins_url('/infunding/assets/css/owl.transitions.css'));

    if (isset($inf_settings['general']['google_api']) && $inf_settings['general']['google_api']) {
        $key = $inf_settings['general']['google_api'];
    } else {
        $key = $inwave_smof_data['google_api'];
    }
    wp_register_script('google-maps', '//maps.googleapis.com/maps/api/js' . ($key ? '?key=' . $key : ''), array());
    wp_register_script('infunding_map', plugins_url('/infunding/assets/js/infunding_map.js'), array(), null, true);
    wp_register_script('markerclusterer', plugins_url('/infunding/assets/js/markerclusterer.js'), array(), null, true);
    wp_enqueue_script('masonry-min', plugins_url('/infunding/assets/js/masonry.pkgd.min.js'), array());
    wp_register_script('owl-carousel', plugins_url('/infunding/assets/js/owl.carousel.min.js'), array('jquery'));
    wp_register_script('datetimepicker', plugins_url('/infunding/assets/js/jquery.datetimepicker.full.min.js'), array());
    wp_enqueue_script('infsite-script', plugins_url('/infunding/assets/js/infunding_script.js'), array(), null, true);
    wp_register_script('custombox', plugins_url('/infunding/assets/js/custombox.min.js'), array());
    //wp_enqueue_script('count-to', plugins_url('/infunding/assets/js/countTo.js'), array());
    //wp_enqueue_script('customjssss', plugins_url('/infunding/assets/js/customjs.js'), array());
}

function inFundingGetTemplatePath($name) {
    $parent_path = get_template_directory();
    $path = $parent_path . '/' . $name . '.php';
    if (get_stylesheet_directory() != get_template_directory()) {
        //Theme child active
        $child_path = get_stylesheet_directory();
        $file_path = $child_path . '/' . $name . '.php';
        if (file_exists($file_path)) {
            $path = $file_path;
        }
    }
    if (file_exists($path)) {
        return $path;
    } else {
        return false;
    }
}

function infVolunteerRegister() {
    $post = filter_input_array(INPUT_POST);
    $utility = new inFundingUtility();
    $member = new inFundingMember();
    $session = new Inwave_Session();
    $log = new inFundingLog();
    $memberinfo = $utility->prepareMemberFieldValue($post['contact_info']);
    $member->setField_value(serialize($memberinfo));
    $member->setSocial_links(isset($post['contact_info']['socials']) ? serialize($post['contact_info']['socials']) : serialize(array()));
    $member->setMember_type('volunteer');
    $member_process = $member->updateMember($member);
    if ($member_process['success']) {
        $volunteer = new inFundingVolunteer();
        $volunteer->setCampaign_id($post['volunteer_info']['campaign']);
        $volunteer->setDate_start(strtotime(str_replace('/', '-', $post['volunteer_info']['date_start'])));
        $volunteer->setDate_end(strtotime(str_replace('/', '-', $post['volunteer_info']['date_end'])));
        $volunteer->setDate_register(time());
        $volunteer->setMember_id($member_process['data']);
        $volunteer->setStatus(0);
        $volunteer->setMessage($post['volunteer_info']['message']);
        $addVolunteer = unserialize($volunteer->addVolunteer($volunteer));
        if ($addVolunteer['success']) {
            $log->addLog(new inFundingLog(NULL, 'success', time(), __('New volunteer has been added', 'inwavethemes'), '', 'memeber'));
            $session->set('inwave_message', $utility->getMessage(__('Thanks for signing up as a Volunteer. We will contact you soon.', 'inwavethemes')));
        } else {
            $log->addLog(new inFundingLog(NULL, 'error', time(), __('Can\'t add new volunteer in Volunteer register form', 'inwavethemes') . '<br/>' . $addVolunteer['msg'], '', 'memeber'));
            $session->set('inwave_message', $utility->getMessage(__('Sorry, can\'t register volunteer now. Please come back again.', 'inwavethemes'), 'error'));
        }
    } else {
        $log->addLog(new inFundingLog(NULL, 'error', time(), __('Can\'t add new member in Volunteer register form', 'inwavethemes') . '<br/>' . $member_process['msg'], '', 'memeber'));
        $session->set('inwave_message', $utility->getMessage(__('Sorry, can\'t register volunteer now. Please come back again.', 'inwavethemes'), 'error'));
    }
    wp_redirect($_SERVER['HTTP_REFERER']);
}

function inFundingSubmitForm() {
    $action = filter_input(INPUT_POST, 'action');
    if ($action) {
        switch ($action) {
            case 'infPaymentProcess':
                infPaymentProcess();
                exit;
                break;
            case 'infVolunteerRegister':
                infVolunteerRegister();
                exit;
                break;
            case 'inFundingSaveMember':
                inFundingSaveMember();
                exit;
                break;
            default:
                break;
        }
    }
}
