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
 * Description of payment
 *
 * @developer duongca
 */
$utility->getNoticeMessage();
?>
<div class="iwe-wrap wrap">
    <form action="<?php echo admin_url(); ?>admin-post.php" method="post">
        <h2 class="in-title"><?php echo __('Edit order', 'inwavethemes'); ?></h2>
        <table class="list-table">
            <tbody class="the-list">
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Order', 'inwavethemes'); ?></label>
                    </td>
                    <td colspan="3">
                        <span><?php echo esc_attr($order->getOrderCode($order->getId())); ?></span>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Total Price', 'inwavethemes'); ?></label>
                    </td>
                    <td colspan="3">
                        <span><?php echo esc_attr($order->getSum_price()) . ' (' . esc_attr($order->getCurrentcy()) . ')'; ?></span>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Created Time', 'inwavethemes'); ?></label>
                    </td>
                    <td colspan="3">
                        <span><?php echo date('m/d/Y', $order->getTime_created()); ?></span>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Paid Time', 'inwavethemes'); ?></label>
                    </td>
                    <td colspan="3">
                        <span><?php echo esc_attr($order->getTime_paymented()) ? date('m/d/Y', $order->getTime_paymented()) : __('Unpaid', 'inwavethemes'); ?></span>
                        <input type="hidden" value="<?php echo esc_attr($order->getTime_paymented()); ?>" name="order_time_paymented"/>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Note', 'inwavethemes'); ?></label>
                    </td>
                    <td colspan="3">
                        <textarea name="order_note"><?php echo esc_textarea($order->getNote()); ?></textarea>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>
                        <label><?php echo __('Status', 'inwavethemes'); ?></label>
                    </td>
                    <td colspan="3">
                        <?php
                        $status_data = array(
                            array('text' => __('Pending', 'inwavethemes'), 'value' => 1),
                            array('text' => __('Paid', 'inwavethemes'), 'value' => 2),
                            array('text' => __('Cancel', 'inwavethemes'), 'value' => 3),
                            array('text' => __('Onhold', 'inwavethemes'), 'value' => 4)
                        );
                        $utility->selectFieldRender('', 'new_order_status', $order->getStatus(), $status_data, '', '', FALSE)
                        ?>
                        <input type="hidden" value="<?php echo esc_attr($order->getStatus()); ?>" name="order_status"/>
                    </td>
                </tr>
                <tr class="alternate">
                    <td>

                    </td>
                    <td colspan="3">
                        <input class="sendmail_to_cutommer" style="width: auto;" type="checkbox" value="1" name="sendmail_to_cutommer"/> <?php echo __('Send email notice to customer', 'inwavethemes'); ?>
                    </td>
                </tr>
                <tr class="alternate status_reason">
                    <td>
                        <label><?php echo __('Reason change status', 'inwavethemes'); ?></label>
                    </td>
                    <td colspan="3">
                        <textarea name="reason"></textarea>
                    </td>
                </tr>
                <tr class="alternate t-heading">
                    <th colspan="4"><?php _e('Member Info', 'inwavethemes'); ?></th>
                </tr>
                <?php
                if ($order->getMember()->getId()) {
                    global $inf_settings;
                    $member_info = $order->getMember()->getField_value();
                    foreach ($member_info as $info):
                        foreach ($inf_settings['register_form_fields'] as $field):
                            if ($info['name'] == $field['name']):
                                ?>
                                <tr class="alternate">
                                    <td>
                                        <label><?php echo __($field['label'], 'inwavethemes'); ?></label>
                                    </td>
                                    <td colspan="3">
                                        <?php
                                        switch ($field['type']):
                                            case 'select':
                                                echo '<select name="member[' . $field['name'] . ']">';
                                                foreach ($field['values'] as $option) {
                                                    echo '<option value="' . $option['value'] . '" ' . ($option['value'] == $info['value']['value'] ? 'selected="selected"' : '') . '>' . $option['text'] . '</option>';
                                                }
                                                echo '</select>';
                                                break;
                                            case 'textarea':
                                                echo '<textarea name="member[' . esc_attr($field['name']) . ']">' . esc_attr($info['value']) . '</textarea>';
                                                break;
                                                break;
                                            case 'email':
                                                echo '<input type="email" value="' . esc_attr($info['value']) . '" name="member[' . esc_attr($field['name']) . ']"/>';
                                                break;
                                            case 'checkbox':
                                                $field_values = array(
                                                    array('text' => __('Checked', 'inwavethemes'), 'value' => '1'),
                                                    array('text' => __('Unchecked', 'inwavethemes'), 'value' => '0')
                                                );
                                                $utility->selectFieldRender('', 'member[' . esc_attr($field['name']) . ']', $info['value'], $field_values, '', '', FALSE);
                                                break;

                                            default:
                                                echo '<input type="text" value="' . ($info['value']) . '" name="member[' . ($field['name']) . ']"/>';
                                                break;
                                        endswitch;
                                        ?>
                                    </td>
                                </tr>
                                <?php
                                break;
                            endif;
                        endforeach;
                    endforeach;
                } else {
                    ?>
                    <tr class="alternate">
                        <td colspan="4" style="text-align: center;">
                            <span><?php echo __('Anonymous donor', 'inwavethemes'); ?></span>
                        </td>
                    </tr>
                <?php } ?>
                <tr class="alternate">
                    <td>
                        <input type="hidden" name="order_member" value="<?php echo esc_attr($order->getMember()->getId()); ?>"/>
                        <input type="hidden" name="order_id" value="<?php echo esc_attr($order->getId()); ?>"/>
                        <input type="hidden" name="action" value="inFundingUpdateOrderInfo"/>
                        <a class="button" href="<?php echo admin_url("edit.php?post_type=infunding&page=payment"); ?>"><?php echo __('Back to list', 'inwavethemes'); ?></a>
                        <input type="submit" value="<?php _e('Save Update', 'inwavethemes'); ?>" class="button"/>
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </form>
</div>