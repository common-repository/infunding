/* 
 * @package Inwave Event
 * @version 1.0.0
 * @created Mar 11, 2015
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of iwevent_admin
 *
 * @developer duongca
 */
(function ($) {
    "use strict";
    $(document).ready(function () {
        var $iwTab = $('.iw-tabs.event-detail'),
                content_list = $iwTab.find('.iw-tab-content .iw-tab-item-content'),
                list = $iwTab.find('.iw-tab-items .iw-tab-item'),
                accordion_day = $('.iw-tab-item-content .iw-tabs');
        $('.iw-tab-items .iw-tab-item', $iwTab).click(function () {
            if ($(this).hasClass('active')) {
                return;
            }
            $(this).addClass('active');
            var itemclick = this;
            list.each(function () {
                if (list.index(this) !== list.index(itemclick) && $(this).hasClass('active')) {
                    $(this).removeClass('active');
                }
            });
            loadTabContent();
        });

        function loadTabContent() {
            var item_active = $iwTab.find('.iw-tab-items .iw-tab-item.active');
            content_list.addClass('iw-hidden');
            $(content_list.get(list.index(item_active))).removeClass('iw-hidden');
        }
        ;
        loadTabContent();

        $('.iw-tab-item-content .iw-accordion-header').live('click', function () {
            var itemClick = $(this),
                    accordion_list = accordion_day.find('.iw-accordion-item'),
                    item_target = itemClick.parent();
            if (itemClick.hasClass('active')) {
                itemClick.removeClass('active');
                item_target.find('.iw-accordion-content').slideUp();
                item_target.find('.iw-accordion-header-icon .expand').hide();
                item_target.find('.iw-accordion-header-icon .no-expand').show();
                return;
            }
            itemClick.addClass('active');
            item_target.find('.iw-accordion-content').slideDown();
            item_target.find('.iw-accordion-header-icon .expand').show();
            item_target.find('.iw-accordion-header-icon .no-expand').hide();
            accordion_list.each(function () {
                if (accordion_list.index(this) !== accordion_list.index(item_target) && $(this).find('.iw-accordion-header').hasClass('active')) {
                    $(this).find('.iw-accordion-header').removeClass('active');
                    $(this).find('.iw-accordion-content').slideUp();
                    $(this).find('.iw-accordion-header-icon .expand').hide();
                    $(this).find('.iw-accordion-header-icon .no-expand').show();
                }
            });
        });


        //Set date event
        $('.input-date.start-date').datepicker({
//            minDate: 0,
//            onSelect: function (dateText) {
//                $('.input-date.end-date').datepicker('option', 'minDate', new Date(dateText));
//            }
        });
        $('.input-date.end-date').datepicker({
//            minDate: $('.input-date.start-date').val(),
//            onSelect: function (dateText) {
//                $('.input-date.start-date').datepicker('option', 'maxDate', new Date(dateText));
//            }
        });


        //Add images gallery
        var frame;
        //Get image from wp library
        $('.button-add-image .add-new-image').click(function () {
            // Set options
            var options = {
                state: 'insert',
                frame: 'post',
                multiple: true,
                library: {
                    type: 'image'
                }
            };

            frame = wp.media(options).open();

            // Tweak views
            frame.menu.get('view').unset('gallery');
            frame.menu.get('view').unset('featured-image');

            frame.toolbar.get('view').set({
                insert: {
                    style: 'primary',
                    text: 'Insert',
                    click: function () {
                        var models = frame.state().get('selection');
                        models.each(function (e) {
                            var attm = e.toJSON();
                            var item_control = '<div class="iw-image-item">';
                            item_control += '<div class="action-overlay">';
                            item_control += '<span class="remove-image">x</span>';
                            item_control += '</div>';
                            item_control += '<img src="' + attm.url + '" width="150"/>';
                            item_control += '<input type="hidden" value="' + attm.id + '" name="inf_information[image_gallery][]"/>';
                            $('.inf-metabox-fields .list-image-gallery').append(item_control);
                        });
                        frame.close();
                    }
                } // end insert
            });
        });

        //Remove image from list
        //Remove image from list gallery
        $('.list-image-gallery .action-overlay .remove-image').live('click', function () {
            $(this).parents('.iw-image-item').hide(200).remove();
        });

        $('.iwe-field-manager .field-label').live('change', function () {
            var val = $(this).val();
            if (val === '') {
                $(this).addClass('form-error');
            } else {
                $(this).removeClass('form-error');
            }
            saveSettingFormValid();
        }).trigger('change');

        $('.iwe-field-manager .field-name').live('input', function () {
            var val = $(this).val();
            if (val === '') {
                $(this).addClass('form-error');
            } else {
                if (!/^[a-zA-Z0-9_]+$/.test(val)) {
                    $(this).addClass('form-error');
                } else {
                    $(this).removeClass('form-error');
                }
            }
            saveSettingFormValid();
        }).trigger('input');

        $('.iwe-field-manager .select-field-type').live('change', function () {
            var val = $(this).val(), row = $(this).parents('tr');
            if (val === 'select') {
                row.find('.default-value').html('<select name="inf_settings[register_form_fields][default_value][]"></select>');
                row.find('.field-values').html('<textarea class="form-error" placeholder="value|Text" name="inf_settings[register_form_fields][values][]"></textarea><span class="description">Multiple value with new line</span>');
            } else if (val === 'custom_regex') {
                row.find('.default-value').html('<input type="text" class="custom-regex-val" pattern="[A-Za-z0-9]{5}" value="" name="inf_settings[register_form_fields][default_value][]"/>');
                row.find('.field-values').html('<input class="custom-regex-patten" type="text" placeholder="[A-Za-z0-9]{5}" value="[A-Za-z0-9]{5}" name="inf_settings[register_form_fields][values][]"/><span class="description">Pattern to apply for field</span>');
            } else if (val === 'email') {
                row.find('.default-value').html('<input type="email" name="inf_settings[register_form_fields][default_value][]"/>');
                row.find('.field-values').html('No default value for this field type.<input type="hidden" name="inf_settings[register_form_fields][values][]" value=""/>');
            } else if (val === 'checkbox') {
                row.find('.default-value').html('<select name="inf_settings[register_form_fields][default_value][]"><option value="1">Checked</option><option value="0">Unchecked</option></select>');
                row.find('.field-values').html('No default value for this field type.<input type="hidden" name="inf_settings[register_form_fields][values][]" value=""/>');
            } else if (val === 'date') {
                row.find('.default-value').html('<input type="date" name="inf_settings[register_form_fields][default_value][]" class="input-box input-date"/>');
                row.find('.field-values').html('No default value for this field type.<input type="hidden" name="inf_settings[register_form_fields][values][]" value=""/>');
            } else if (val === 'text') {
                row.find('.field-values').html('No default value for this field type.<input type="hidden" name="inf_settings[register_form_fields][values][]" value=""/>');
                row.find('.default-value').html('<input type="text" name="inf_settings[register_form_fields][default_value][]"/>');
            } else {
                row.find('.field-values').html('No default value for this field type.<input type="hidden" name="inf_settings[register_form_fields][values][]" value=""/>');
                row.find('.default-value').html('<textarea name="inf_settings[register_form_fields][default_value][]"></textarea>');
            }
            saveSettingFormValid();
        });

        $('.iwe-field-manager .field-values textarea').live('input', function () {
            var item_target = $(this), val = item_target.val().trim('\n'), line = val.split('\n'), options = '';
            for (var i = 0; i < line.length; i++) {
                var option = line[i].split('|');
                if (option.length !== 2) {
                    item_target.addClass('form-error');
                    break;
                } else {
                    options += '<option value="' + option[0] + '">' + option[1] + '</option>';
                    item_target.removeClass('form-error');
                }
            }
            $('.iwe-field-manager .default-value select').html(options);
            saveSettingFormValid();
        });

        $('.iwe-field-manager .field-values .custom-regex-patten').live('input', function () {
            var item_target = $(this),
                    val = item_target.val(),
                    re = new RegExp(val, "i"),
                    value = $('.iwe-field-manager .default-value .custom-regex-val');
            if (re.test(value.val())) {
                value.removeClass('form-error');
            } else {
                value.addClass('form-error');
            }
            value.attr('pattern', val);
            saveSettingFormValid();
        });

        $('.iwe-field-manager .default-value .custom-regex-val').live('input', function () {
            var item_target = $(this),
                    val = item_target.val(),
                    re = new RegExp(item_target.attr('pattern'), "i");
            if (re.test(val)) {
                item_target.removeClass('form-error');
            } else {
                item_target.addClass('form-error');
            }
            saveSettingFormValid();
        });


        $('.button.add-rgister-field').click(function () {
            var html = '<tr class="alternate">';
            html += '<td class="iwe-sortable-cell">';
            html += '<span><i class="fa fa-arrows"></i></span>';
            html += '</td>';
            html += '<td class="iwe_field_label"><input class="field-label form-error" type="text" name="inf_settings[register_form_fields][label][]"></td>';
            html += '<td class="iwe_field_name"><input class="field-name form-error" type="text" name="inf_settings[register_form_fields][name][]"></td>';
            html += '<td><input type="text" value="" class="field-group" name="inf_settings[register_form_fields][group][]"/></td>';
            html += '<td><select class="select-field-type" name="inf_settings[register_form_fields][type][]">';
            html += '<option value="text">String</option>';
            html += '<option value="select">Select</option>';
            html += '<option value="textarea">Text</option>';
            html += '<option value="email">Email</option>';
            html += '<option value="date">Date</option>';
            html += '<option value="checkbox">Checkbox</option>';
            html += '<option value="custom_regex">Custom Regex</option>';
            html += '</select></td>';
            html += '<td class="field-values">';
            html += 'No default value for this field type.<input type="hidden" name="inf_settings[register_form_fields][values][]" value=""/>';
            html += '</td>';
            html += '<td class="default-value"><input type="text" name="inf_settings[register_form_fields][default_value][]"></td>';
            html += '<td>';
            html += '<input type="checkbox" value="1" name="show_on_list" class="show_on_list"/>';
            html += '<input class="iwe_field_val" type="hidden" value="0" name="inf_settings[register_form_fields][show_on_list][]"/>';
            html += '</td>';
            html += '<td>';
            html += '<input type="checkbox" value="1" name="require_field" class="require_field"/>';
            html += '<input class="iwe_field_val" type="hidden" value="0" name="inf_settings[register_form_fields][require_field][]"/>';
            html += '</td>';
            html += '<td><span class="button remove_field">Remove</span></td>';
            html += '</tr>';

            $('.iwe-field-manager .the-list').append(html);
            saveSettingFormValid();
        });

        $('.button.remove_field').live('click', function () {
            $(this).parents('tr').remove();
            saveSettingFormValid();
        });

        $('input.show_on_list, input.require_field').live('change', function () {
            var val = $(this).is(':checked');
            if (val) {
                $(this).parent().find('.iwe_field_val').val(1);
            } else {
                $(this).parent().find('.iwe_field_val').val(0);
            }
        });

        $('.iwe-wrap input.sendmail_to_cutommer').change(function () {
            if ($(this).prop("checked") === true) {
                $('.iwe-wrap .status_reason').show();
            } else {
                $('.iwe-wrap .status_reason').hide();
            }
        }).trigger('change');

        function disableSaveSettingForm() {
            $('.iwe-save-settings input').attr('disabled', 'disabled');
        }
        function enableSaveSettingForm() {
            $('.iwe-save-settings input').removeAttr('disabled');
        }

        function saveSettingFormValid() {
            var error = $('.iwe-field-manager').find('.form-error');
            if (error.length > 0) {
                disableSaveSettingForm();
            } else {
                enableSaveSettingForm();
            }
        }

        $('.button.accept-volunteer').click(function () {
            var target = $(this),
            id = target.data('id'),
            parent = target.parents('tr');
            $.ajax({
                url: inFundingCfg.ajaxUrl,
                data: {action: 'acceptVolunter', id: id},
                type: "post",
                success: function (data) {
                    var rs = $.parseJSON(data);
                    if (rs.success) {
                        parent.find('td.status').text(rs.text);
                        parent.addClass('accepted');
                        target.remove();
                    }
                }
            });
        });
    });
})(jQuery);


