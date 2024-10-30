<?php
/*
 * @package Inwave Infunding
 * @version 1.0.0
 * @created May 27, 2016
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
    <h2 class="in-title"><?php echo __('Order Detail', 'inwavethemes'); ?></h2>
</div>
<div class="iwe-wrap">
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
                    <span><?php echo ($order->getSum_price() . ' (' . $order->getCurrentcy() . ')'); ?></span>
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
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Note', 'inwavethemes'); ?></label>
                </td>
                <td colspan="3">
                    <span><?php echo esc_textarea($order->getNote()); ?></span>
                </td>
            </tr>
            <tr class="alternate">
                <td>
                    <label><?php echo __('Status', 'inwavethemes'); ?></label>
                </td>
                <td colspan="3">
                    <?php
                    switch ($order->getStatus()) {
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
                    ?>
                </td>
            </tr>
            <?php if (is_admin()): ?>
                <tr class="alternate t-heading">
                    <th colspan="4"><?php _e('Member Info', 'inwavethemes'); ?></th>
                </tr>
                <?php
                if ($order->getMember()->getId()) {
                    $member_data = $order->getMember()->getMemberRowData($order->getMember()->getId());
                    $member_info = $member_data['data'][0];
                    foreach ($member_data['fields'] as $field):
                        if ($member_info[$field['name']]) {
                            ?>
                            <tr class="alternate">
                                <td>
                                    <label><?php echo esc_attr($field['label']); ?></label>
                                </td>
                                <td colspan="3">
                                    <span><?php echo esc_attr($member_info[$field['name']]); ?></span>
                                </td>
                            </tr>
                            <?php
                        }
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
                        <a class="button" href="<?php echo admin_url("edit.php?post_type=infunding&page=payment"); ?>"><?php echo __('Back to list', 'inwavethemes'); ?></a>
                        <a class="button" href="<?php echo admin_url("edit.php?post_type=infunding&page=payment/edit&id=" . $order->getId()); ?>"><?php echo __('Edit order', 'inwavethemes'); ?></a>
                        <?php if ($order->getStatus() == 2): ?>
                            <a class="button" href="<?php echo admin_url("admin-post.php?action=inFundingResendEmail&id=" . $order->getId()); ?>"><?php echo __('Resend email', 'inwavethemes'); ?></a>
                        <?php else: ?>
                            <span class="button disabled" ><?php echo __('Resend email', 'inwavethemes'); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php if (!is_admin()): ?>
    <div class="notice">
        <?php
        if ($order->getStatus() == 2) {
            printf($utility->getMessage(__('Your donate has completed. Thanks for donate with us', 'inwavethemes')));
        } else {
            printf($utility->getMessage(__('Your donate don\'t completed.', 'inwavethemes'), 'notice'));
        }
        ?>
    </div>
    <?php endif;?>
</div>
