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
 * Description of member
 *
 * @developer duongca
 */
 
$utility->getNoticeMessage();
?>
<div class="iwe-wrap wrap">
    <h2 class="in-title"><?php echo __('Members', 'inwavethemes'); ?>
        <!--<a class="bt-button add-new-h2" href ="<?php echo admin_url('edit.php?post_type=iwevent&page=donor/addnew'); ?>"><?php echo __("Add New"); ?></a>-->
        <a class="bt-button add-new-h2" href ="javascript:void(0);" onclick="javascript:document.getElementById('payment-form').submit();
                return false;"><?php echo __("Delete"); ?></a>
    </h2>
    <form id="payment-form" action="<?php echo admin_url(); ?>admin-post.php" method="post">
        <input type="hidden" name="action" value="inFundingDeleteMembers"/>
        <table class="wp-list-table widefat fixed posts">
            <thead>
                <tr>
                    <th class="manage-column column-cb check-column">
                        <label class="screen-reader-text" for="cb-select-all-1"><?php echo __('Select All', 'inwavethemes'); ?></label>
                        <input id="cb-select-all-1" type="checkbox">
                    </th>
                    <?php
                    foreach ($member_list['fields'] as $field_header) {
						if($field_header['show_on_list']){
							echo '<th>' . stripslashes($field_header['label']) . '</th>';
						}
                    }
                    echo '<th>' . __('Action', 'inwavethemes') . '</th>';
                    ?>
                </tr>
            </thead>

            <tbody id="the-list">
                <?php
                if (!empty($member_list['data']) && $member_list['data'][0]['id']) {
                    foreach ($member_list['data'] as $member_info) {
                        $member_id = $member_info['id'];
                        unset($member_info['id']);
                        ?>
                        <tr>
                            <th scope="row" class="check-column">
                                <input id="cb-select-1" type="checkbox" name="fields[]" value="<?php echo esc_attr($member_id); ?>"/>
                    <div class="locked-indicator"></div>
                    </th>
                    <?php
                    foreach ($member_info as $value) {
                        echo '<td>' . stripslashes($value) . '</td>';
                    }
                    ?>
                    <td>
                        <a href="<?php echo admin_url('edit.php?post_type=infunding&page=donor/edit&id=' . $member_id); ?>" title="<?php echo __('Edit this member', 'inwavethemes'); ?>"><?php echo __('Edit', 'inwavethemes'); ?></a> |
                        <a class="submitdelete" title="<?php echo __('Delete this member', 'inwavethemes'); ?>" href="<?php echo admin_url("admin-post.php?action=inFundingDeleteMember&id=" . $member_id); ?>"><?php echo __('Delete', 'inwavethemes'); ?></a>
                    </td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td colspan="<?php echo (count($member_list['fields']) + 2); ?>">
                        <?php
                        $paging->pageList($_GET['pagenum'], $pages);
                        ?>
                    </td>
                </tr> 
            <?php } else {
                ?>
                <tr>
                    <td colspan="<?php echo (count($member_list['fields']) + 2); ?>"><?php echo __('No result', 'inwavethemes'); ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </form>
</div>