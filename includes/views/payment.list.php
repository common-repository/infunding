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
    <h2 class="in-title"><?php echo __('Orders', 'inwavethemes'); ?>
        <!--<a class="bt-button add-new-h2" href ="<?php echo admin_url('edit.php?post_type=infunding&page=payment/addnew'); ?>"><?php echo __("Add New"); ?></a>-->
        <a class="bt-button add-new-h2" href ="javascript:void(0);" onclick="javascript:document.getElementById('payment-form').submit();
                return false;"><?php echo __("Delete"); ?></a>
        <!--<a class="bt-button add-new-h2" href ="<?php echo admin_url('admin-post.php?action=inFundingClearOrderExpired'); ?>"><?php echo __("Kill Order Expired"); ?></a>-->
    </h2>
    <form action="<?php echo admin_url(); ?>admin-post.php" method="post" name="filter">
        <div class="iwe-filter tablenav top"><div class="alignleft">
                <label><?php _e('Filter', 'inwavethemes'); ?></label>

                <input type="text" name="keyword" value="<?php echo filter_input(INPUT_GET, 'keyword'); ?>" placeholder="<?php echo __('Input keyword to search', 'inwavethemes'); ?>"/>

            </div><div class="alignleft"><?php
                $args = array(
                    'post_type' => 'infunding',
                    'numberposts' => '-1',
                    'orderby' => 'meta_value_num',
                    'meta_key' => 'inf_end_date'
                );
                $campaigns = get_posts($args);
                $campaignData = array();
                foreach ($campaigns as $camp) {
                    $campaignData[] = array('text' => $camp->post_title, 'value' => $camp->ID);
                }
                $utility->selectFieldRender('filter_campaign', 'campaign', filter_input(INPUT_GET, 'campaign'), $campaignData, __('Any', 'inwavethemes'), '', false);
                ?>
            </div>
            <div class="alignleft">
                <?php
                $status_select_data = array(
                    array('value' => '', 'text' => __('All', 'inwavethemes')),
                    array('value' => '1', 'text' => __('Pending', 'inwavethemes')),
                    array('value' => '2', 'text' => __('Completed', 'inwavethemes')),
                    array('value' => '3', 'text' => __('Cancel', 'inwavethemes')),
                    array('value' => '4', 'text' => __('Onhold', 'inwavethemes'))
                );
                $utility->selectFieldRender('filter_status', 'status', filter_input(INPUT_GET, 'status'), $status_select_data, null, '', false);
                ?>
            </div>
            <div class="alignleft">
                <input type="hidden" value="inFundingFilter" name="action"/>
                <input class="button" type="submit" value="<?php _e('Search', 'inwavethemes'); ?>"/>
            </div>
        </div>
    </form>
    <form id="payment-form" action="<?php echo admin_url(); ?>admin-post.php" method="post">
        <input type="hidden" name="action" value="inFundingDeleteOrders"/>
        <table class="wp-list-table widefat fixed posts">
            <thead>
                <tr>
                    <th class="manage-column column-cb check-column">
                        <label class="screen-reader-text" for="cb-select-all-1"><?php echo __('Select All', 'inwavethemes'); ?></label>
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <th class="column-primary <?php echo ($sorted == 'id') ? 'sorted' : 'sortable'; ?> <?php echo ($order_dir ? 'desc' : 'asc'); ?>" scope="col"><a href="<?php echo esc_url($order_link) . '&orderby=id&dir=' . ($order_dir ? 'desc' : 'asc') ?>"><span><?php echo __('Order #', 'inwavethemes'); ?></span><span class="sorting-indicator"></span></a></th>
                    <th class="column-primary <?php echo ($sorted == 'campaign') ? 'sorted' : 'sortable'; ?> <?php echo ($order_dir ? 'desc' : 'asc'); ?>" scope="col"><a href="<?php echo esc_url($order_link) . '&orderby=campaign&dir=' . ($order_dir ? 'desc' : 'asc') ?>"><span><?php echo __('Campaign', 'inwavethemes'); ?></span><span class="sorting-indicator"></span></a></th>
                    <th><?php echo __('Member', 'inwavethemes'); ?></th>
                    <th class="column-primary <?php echo ($sorted == 'price') ? 'sorted' : 'sortable'; ?> <?php echo ($order_dir ? 'desc' : 'asc'); ?>" scope="col"><a href="<?php echo esc_url($order_link) . '&orderby=price&dir=' . ($order_dir ? 'desc' : 'asc') ?>"><span><?php echo __('Money', 'inwavethemes'); ?></span><span class="sorting-indicator"></span></a></th>
                    <th><?php echo __('Note', 'inwavethemes'); ?></th>
                    <th class="column-primary <?php echo ($sorted == 'status') ? 'sorted' : 'sortable'; ?> <?php echo ($order_dir ? 'desc' : 'asc'); ?>" scope="col"><a href="<?php echo esc_url($order_link) . '&orderby=status&dir=' . ($order_dir ? 'desc' : 'asc') ?>"><span><?php echo __('Status', 'inwavethemes'); ?></span><span class="sorting-indicator"></span></a></th>
                </tr>
            </thead>

            <tbody id="the-list">
                <?php
                if (!empty($orders)) {
                    foreach ($orders as $order) {
                        ?>
                        <tr>
                            <th scope="row" class="check-column">
                                <input id="cb-select-1" type="checkbox" name="fields[]" value="<?php echo esc_attr($order->getId()); ?>"/>
                                <div class="locked-indicator"></div>
                            </th>
                            <td>
                                <a href="<?php echo admin_url('edit.php?post_type=infunding&page=payment/view&id=' . esc_attr($order->getId())); ?>" title="<?php echo __('View order', 'inwavethemes'); ?>">
                                    <strong><?php echo esc_attr($order->getOrderCode($order->getId())); ?></strong>
                                </a>
                                <div class="row-actions">
                                    <a href="<?php echo admin_url('edit.php?post_type=infunding&page=payment/edit&id=' . esc_attr($order->getId())); ?>" title="<?php echo __('Edit this item', 'inwavethemes'); ?>"><?php echo __('Edit', 'inwavethemes'); ?></a> |
                                    <a class="submitdelete" title="<?php echo __('Delete this order', 'inwavethemes'); ?>" href="<?php echo admin_url("admin-post.php?action=inFundingDeleteOrder&id=" . esc_attr($order->getId())); ?>"><?php echo __('Delete', 'inwavethemes'); ?></a> |
                                    <a class="submitdelete" title="<?php echo __('View order', 'inwavethemes'); ?>" href="<?php echo admin_url("edit.php?post_type=infunding&page=payment/view&id=" . esc_attr($order->getId())); ?>"><?php echo __('View', 'inwavethemes'); ?></a>
                                </div>
                            </td>
                            <td>
                                <?php
                                $campaign = get_post($order->getCampaign());
                                ?>
                                <a href="<?php echo get_edit_post_link($order->getCampaign()); ?>"><?php echo esc_attr($campaign->post_title); ?></a>
                            </td>
                            <td>
                                <?php
                                $member = $order->getMember();
                                $member_data = $member->getField_value();
                                if (!empty($member_data)) {
                                    foreach ($member_data as $value) {
                                        if ($value['name'] == 'full_name') {
                                            echo esc_attr($value['value']);
                                            break;
                                        }
                                    }
                                }
                                ?>
                            </td>
                            <td><?php echo ($order->getSum_price() . ' (' . $order->getCurrentcy() . ')'); ?></td>
                            <td><?php echo esc_textarea($order->getNote()); ?></td>
                            <td><?php
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
                                ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td colspan="6">
                            <?php
                            $paging->pageList($_GET['pagenum'], $pages);
                            ?>
                        </td>
                    </tr> 
                <?php } else { ?>
                    <tr>
                        <td colspan="6"><?php echo __('No result', 'inwavethemes'); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </form>
</div>