<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<?php
wp_enqueue_style('datetimepicker');
wp_enqueue_script('datetimepicker');
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
$date_configs = array(
    'mask'=>'__/__/____',
    'timepicker' => false,
    'format' => 'm/d/Y'
);
?>
<form action="" method="post">
    <input type="hidden" name="action" value="infVolunteerRegister"/>
    <div class="in-volunteer-contact">
        <?php
        $utility->getNoticeMessage();
        ?>
        <h3 class="title-contact-form"><?php echo esc_attr($member_info_text); ?></h3>
        <?php if (!empty($newfields)): ?>
            <?php foreach ($newfields as $key => $fields) : ?>
                <?php if (count($newfields) > 1): ?>
                    <fieldset>
                        <legend><?php echo esc_attr($key); ?></legend>
                    <?php endif; ?>
                    <?php foreach ($fields as $field) : ?>
                        <div class="in-contact-field">
                            <label class="label_field"><?php echo __($field['label'], 'inwavethemes') . ($field['require_field'] ? '*' : ''); ?></label>
                            <div class="input-field">
                                <span class="wpcf7-form-control-wrap">
                                    <?php
                                    $require = '';
                                    if ($field['require_field']) {
                                        $require = 'required="required"';
                                    }
                                    switch ($field['type']):
                                        case 'select':
                                            echo '<select class="" name=contact_info[' . $field['name'] . ']>';
                                            foreach ($field['values'] as $option) {
                                                echo '<option ' . $require . ' value="' . $option['value'] . '" ' . (isset($customer_data[$field['name']]) ? ($option['value'] == $customer_data[$field['name']]['value'] ? 'selected="selected"' : '') : ($option['value'] == $field['default_value'] ? 'selected="selected"' : '')) . '>' . $option['text'] . '</option>';
                                            }
                                            echo '</select>';
                                            break;
                                        case 'textarea':
                                            echo '<textarea ' . $require . ' name="contact_info[' . $field['name'] . ']">' . (isset($customer_data[$field['name']]) ? $customer_data[$field['name']] : $field['default_value']) . '</textarea>';
                                            break;
                                        case 'email':
                                            echo '<input class="" placeholder="' . $field['label'] . '" ' . $require . ' type="email" value="' . (isset($customer_data[$field['name']]) ? $customer_data[$field['name']] : $field['default_value']) . '" name="contact_info[' . $field['name'] . ']"/>';
                                            break;
                                        case 'date':
                                            echo '<input data-configs="' . htmlspecialchars(json_encode($date_configs)) . '" class="datetimepicker-input" placeholder="' . $field['label'] . '" ' . $require . ' type="text" value="' . (isset($customer_data[$field['name']]) ? date('d/m/Y',$customer_data[$field['name']]) : $field['default_value']) . '" name="contact_info[' . $field['name'] . ']"/>';
                                            break;

                                        default:
                                            echo '<input class=""  placeholder="' . $field['label'] . '" ' . $require . ' type="text" value="' . (isset($customer_data[$field['name']]) ? $customer_data[$field['name']] : $field['default_value']) . '" name="contact_info[' . $field['name'] . ']"/>';
                                            break;
                                    endswitch;
                                    ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (count($newfields) > 1): ?>
                    </fieldset>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php
        endif;
        ?>


        <h3 class="title-contact-form"><?php echo esc_attr($volunteer_info_text); ?></h3>
        <div class="in-contact-field">
            <label class="label_field"><?php _e('Campaign', 'inwavethemes'); ?> *</label>
            <div class="input-field">
                <span class="wpcf7-form-control-wrap">
                    <?php echo inFundingUtility::selectFieldRender('', 'volunteer_info[campaign]', $campaign, $campaignData, '', '', false, 'required') ?>
                </span>
            </div>
        </div>
        <div class="datetimepicker-group">
            <div class="in-contact-field">
                <label class="label_field"><?php _e('Date Start', 'inwavethemes'); ?> *</label>
                <div class="input-field">
                    <span class="wpcf7-form-control-wrap">
                        <input required="required" type="text" name="volunteer_info[date_start]" value="" placeholder="<?php _e('Date Start', 'inwavethemes'); ?>" class="datetimepicker-input start" data-configs="<?php echo htmlspecialchars(json_encode($date_configs)); ?>">
                    </span>
                </div>
            </div>
            <div class="in-contact-field">
                <label class="label_field"><?php _e('Date End', 'inwavethemes'); ?> *</label>
                <div class="input-field">
                    <input required="required" type="text" name="volunteer_info[date_end]" value="" placeholder="<?php _e('Date End', 'inwavethemes'); ?>" class="datetimepicker-input end" data-configs="<?php echo htmlspecialchars(json_encode($date_configs)); ?>">
                </div>
            </div>
        </div>
        <div class="in-contact-field">
            <label class="label_field"><?php _e('Your Message', 'inwavethemes'); ?></label>
            <div class="input-field"><span class="wpcf7-form-control-wrap your-message"><textarea name="volunteer_info[message]" cols="40" rows="10" class="wpcf7-form-control wpcf7-textarea" aria-invalid="false"></textarea></span> </div>
        </div>
        <div class="in-contact-field in-submit-field">
            <div class="in-submit-field-inner"><input type="submit" value="<?php echo esc_attr($button_text); ?>" class="wpcf7-form-control wpcf7-submit" /><i class="fa fa-arrow-right"></i></div>
        </div>
    </div>
</form>