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
$action_link = '';
if (is_admin()) {
    $action_link = admin_url() . 'admin-post.php';
}
?>
<div class="iwe-wrap wrap">
    <form action="<?php echo esc_url($action_link); ?>" method="post">
        <h2 class="in-title"><?php echo __('Edit member', 'inwavethemes'); ?></h2>
        <table class="list-table">
            <tbody class="the-list">
                <?php
                $member_info = $member_data['data'][0];
                foreach ($member_data['fields'] as $field):
                    ?>
                    <tr class="alternate">
                        <td>
                            <label><?php echo __($field['label'], 'inwavethemes'); ?></label>
                        </td>
                        <td>
                            <?php
                            switch ($field['type']):
                                case 'select':
                                    echo '<select name="member[' . $field['name'] . ']">';
                                    foreach ($field['values'] as $option) {
                                        echo '<option value="' . $option['value'] . '" ' . ($option['value'] == stripslashes($member_info[$field['name']]) ? 'selected="selected"' : '') . '>' . $option['text'] . '</option>';
                                    }
                                    echo '</select>';
                                    break;
                                case 'textarea':
                                    echo '<textarea name="member[' . $field['name'] . ']">' . stripslashes($member_info[$field['name']]) . '</textarea>';
                                    break;
                                case 'checkbox':
                                    $field_values = array(
                                        array('text' => __('Checked', 'inwavethemes'), 'value' => '1'),
                                        array('text' => __('Unchecked', 'inwavethemes'), 'value' => '0')
                                    );
                                    $utility->selectFieldRender('', 'member[' . $field['name'] . ']', $member_info[$field['name']], $field_values, '', '', FALSE);
                                    break;

                                default:
                                    echo '<input type="text" value="' . stripslashes($member_info[$field['name']]) . '" name="member[' . $field['name'] . ']"/>';
                                    break;
                            endswitch;
                            ?>
                        </td>
                    </tr>
                    <?php
                endforeach;
                ?>
                <tr class="alternate">
                    <td class="label" style="width: 20%; max-width: 200px;">
                        <label><?php echo __('Social profile', 'inwavethemes'); ?></label>
                    </td>
                    <td>
                        <?php
                        $social_data = $member->getSocial_links();
                        ?>
                        <table class="list-table">
                            <tbody class="the-list">
                                <tr class="alternate">
                                    <td class="label" style="width: 20%; max-width: 200px;">
                                        <label><?php echo __('Facebook', 'inwavethemes'); ?></label>
                                    </td>
                                    <td>
                                        <input name="social[facebook]"  type="url" value="<?php echo isset($social_data['facebook'])?$social_data['facebook']:''; ?>" />
                                    </td>
                                </tr>
                                <tr class="alternate">
                                    <td class="label">
                                        <label><?php echo __('Youtube', 'inwavethemes'); ?></label>
                                    </td>
                                    <td>
                                        <input name="social[youtube]"  type="url" value="<?php echo isset($social_data['youtube'])?$social_data['youtube']:''; ?>" />
                                    </td>
                                </tr>
                                <tr class="alternate">
                                    <td class="label">
                                        <label><?php echo __('Vimeo', 'inwavethemes'); ?></label>
                                    </td>
                                    <td>
                                        <input name="social[vimeo]"  type="url" value="<?php echo isset($social_data['vimeo'])?$social_data['vimeo']:''; ?>" />
                                    </td>
                                </tr>
                                <tr class="alternate">
                                    <td class="label">
                                        <label><?php echo __('Flickr', 'inwavethemes'); ?></label>
                                    </td>
                                    <td>
                                        <input name="social[flickr]"  type="url" value="<?php echo isset($social_data['flickr'])?$social_data['flickr']:''; ?>" />
                                    </td>
                                </tr>
                                <tr class="alternate">
                                    <td class="label">
                                        <label><?php echo __('Google Plus', 'inwavethemes'); ?></label>
                                    </td>
                                    <td>
                                        <input name="social[google-plus]"  type="url" value="<?php echo isset($social_data['google-plus'])?$social_data['google-plus']:''; ?>" />
                                    </td>
                                </tr>
                                <tr class="alternate">
                                    <td class="label">
                                        <label><?php echo __('Linked In', 'inwavethemes'); ?></label>
                                    </td>
                                    <td>
                                        <input name="social[linkedin]"  type="url" value="<?php echo isset($social_data['linkedin'])?$social_data['linkedin']:''; ?>" />
                                    </td>
                                </tr>
                                <tr class="alternate">
                                    <td class="label">
                                        <label><?php echo __('Tumblr', 'inwavethemes'); ?></label>
                                    </td>
                                    <td>
                                        <input name="social[tumblr]"  type="url" value="<?php echo isset($social_data['tumblr'])?$social_data['tumblr']:''; ?>" />
                                    </td>
                                </tr>
                                <tr class="alternate">
                                    <td class="label">
                                        <label><?php echo __('Twitter', 'inwavethemes'); ?></label>
                                    </td>
                                    <td>
                                        <input name="social[twitter]"  type="url" value="<?php echo isset($social_data['twitter'])?$social_data['twitter']:''; ?>" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr class="alternate">
                    <td></td>
                    <td>
                        <input type="hidden" name="id" value="<?php echo esc_attr($member_info['id']); ?>"/>
                        <input type="hidden" name="action" value="inFundingSaveVolunteer"/>
                        <input type="submit" value="<?php _e('Save Update', 'inwavethemes'); ?>" class="button"/>
                    </td>
                </tr>
                <tr class="alternate">
                    <td colspan="2"></td>
                </tr>
                <tr class="alternate">
                    <th colspan="2"><?php _e('Volunteers Register', 'inwavethemes'); ?></th>
                </tr>
                <tr class="alternate">
                    <td colspan="2">
                        <?php
                        $volunteer = new inFundingVolunteer();
                        $vls = $volunteer->getVolunteerByUser($member->getId());
                        ?>
                        <table class="list-table">
                            <tbody class="the-list">
                                <tr class="alternate">
                                    <th><?php _e('Campaign', 'inwavethemes'); ?></th>
                                    <th><?php _e('Date start', 'inwavethemes'); ?></th>
                                    <th><?php _e('Date end', 'inwavethemes'); ?></th>
                                    <th><?php _e('Date register', 'inwavethemes'); ?></th>
                                    <th><?php _e('Message', 'inwavethemes'); ?></th>
                                    <th><?php _e('Status', 'inwavethemes'); ?></th>
                                    <th><?php _e('Accept', 'inwavethemes'); ?></th>
                                </tr>
                                <?php
                                if (!empty($vls)) {
                                    foreach ($vls as $value) {
                                        $campInfo = $utility->getCampaignInfo($value->getCampaign_id());
                                        echo '<tr class="alternate">';
                                        echo '<td>' . $campInfo->title . '</td>';
                                        echo '<td>' . $utility->getLocalDate(get_option('date_format') . ' ' . get_option('time_format'), $value->getDate_start()) . '</td>';
                                        echo '<td>' . $utility->getLocalDate(get_option('date_format') . ' ' . get_option('time_format'), $value->getDate_end()) . '</td>';
                                        echo '<td>' . $utility->getLocalDate(get_option('date_format') . ' ' . get_option('time_format'), $value->getDate_register()) . '</td>';
                                        echo '<td>' . $value->getMessage() . '</td>';
                                        echo '<td class="status">' . ($value->getStatus() == 1 ? __('Accepted', 'inwavethemes') : __('No Accept', 'inwavethemes')) . '</td>';
                                        echo '<td>' . ($value->getStatus() == 0 ? '<span class="button accept-volunteer" data-id="'.$value->getId().'"><span><i class="fa fa-check"></i></span></button>' : '') . '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr class="alternate">';
                                    echo '<td colspan="6">' . __('No volunteer found for this memeber', 'inwavethemes') . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>