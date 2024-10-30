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
                                    echo '<select name="member[' . esc_attr($field['name']) . ']">';
                                    foreach ($field['values'] as $option) {
                                        echo '<option value="' . esc_attr($option['value']) . '" ' . ($option['value'] == stripslashes($member_info[$field['name']]) ? 'selected="selected"' : '') . '>' . esc_attr($option['text']) . '</option>';
                                    }
                                    echo '</select>';
                                    break;
                                case 'textarea':
                                    echo '<textarea name="member[' . esc_attr($field['name']) . ']">' . stripslashes($member_info[$field['name']]) . '</textarea>';
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
                    <td></td>
                    <td>
                        <input type="hidden" name="id" value="<?php echo esc_attr($member_info['id']); ?>"/>
                        <input type="hidden" name="action" value="inFundingSaveMember"/>
                        <input type="submit" value="<?php _e('Save Update', 'inwavethemes'); ?>" class="button"/>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>