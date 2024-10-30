<?php
/*
 * @package Inwave Charity
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
 * Description of utility
 *
 * @developer duongca
 */
require_once 'classes/Inwave_Session.php';
require_once 'classes/inFundingMetaBox.php';
require_once 'classes/inFundingOrder.php';
require_once 'classes/inFundingMember.php';
require_once 'classes/inFundingLog.php';
require_once 'classes/inFundingPaging.php';
require_once 'classes/inFundingVolunteer.php';
if (!class_exists('inFundingUtility')) {

    class inFundingUtility {

        function categoryField($name, $value, $multiple = true) {
            $categories = get_terms('iwevent_category', 'hide_empty=0');
            $html = array();
            $multiselect = '';
            if ($multiple) {
                $multiselect = 'multiple="multiple"';
                $html[] = '<select id="category_field" name="' . $name . '[]" ' . $multiselect . '>';
                $html[] = '<option ' . (empty($value) ? 'selected="selected"' : '' ) . ' value="0">' . __('Select all') . '</option>';
            } else {
                $html[] = '<select id="category_field" name="' . $name . '">';
                $html[] = '<option value="0">' . __('Select category') . '</option>';
            }
            foreach ($categories as $category) {
                if (is_array($value)) {
                    if (in_array($category->term_id, $value)) {
                        $html[] = '<option value="' . $category->term_id . '" selected="selected">' . $category->name . '</option>';
                    } else {
                        $html[] = '<option value="' . $category->term_id . '">' . $category->name . '</option>';
                    }
                } else {
                    $html[] = '<option value="' . $category->term_id . '" ' . (($category->term_id == $value) ? 'selected="selected"' : '') . '>' . $category->name . '</option>';
                }
            }
            $html[] = '</select>';
            $html[] = '<script type="text/javascript">';
            $html[] = '(function ($) {';
            $html[] = '$(document).ready(function () {';
            $html[] = '$("#category_field").select2({';
            $html[] = 'placeholder: "' . __('Select category', 'inwavethemes') . '",';
            $html[] = 'allowClear: true';
            $html[] = '});';
            $html[] = '});';
            $html[] = '})(jQuery);';
            $html[] = '</script>';
            return implode($html);
        }

        /**
         * Function create select option field
         * 
         * @param type $id
         * @param String $name Name of field
         * @param String $value The value field
         * @param Array $data list data option of field
         * @param String $text Default value of field
         * @param String $class Class of field
         * @param Bool $multi Field allow multiple select of not
         * @return String Select option field
         * 
         */
        static function selectFieldRender($id, $name, $value, $data, $text = '', $class = '', $multi = true, $extra = '', $html5_data = array()) {
            $html = array();
            $multiselect = '';
//Kiem tra neu bien class ton tai thi them class vao field
            if ($class) {
                $class = 'class="' . $class . '"';
            }

            $html_data = '';
            if (!empty($html5_data)) {
                foreach ($html5_data as $key => $value) {
                    $html_data.='data-' . $key . '="' . $value . '" ';
                }
            }

//Kiem tra neu field can tao cho phep multiple
            if ($multi) {
                $multiselect = 'multiple="multiple"';
                $html[] = '<select' . ($id ? ' id="' . $id . '"' : ' ') . ($html_data ? $html_data : '') . ' ' . $class . ' name="' . $name . '[]" ' . $multiselect . ' ' . $extra . '>';
                if ($text) {
                    $html[] = '<option value="">' . __($text) . '</option>';
                }
            } else {
                $html[] = '<select ' . $class . ' name="' . $name . '" ' . ($html_data ? $html_data : '') . ($id ? ' id="' . $id . '"' : ' ') . $extra . '>';
                if ($text) {
                    $html[] = '<option value="">' . __($text) . '</option>';
                }
            }

//Duyet qua tung phan tu cua mang du lieu de tao option tuong ung
            foreach ($data as $option) {
                if (is_array($value)) {
                    if (in_array($option['value'], $value)) {
                        $html[] = '<option value="' . $option['value'] . '" selected="selected">' . $option['text'] . '</option>';
                    } else {
                        $html[] = '<option value="' . $option['value'] . '">' . __($option['text']) . '</option>';
                    }
                } else {
                    $html[] = '<option value="' . $option['value'] . '" ' . (($option['value'] == $value) ? 'selected="selected"' : '') . '>' . __($option['text']) . '</option>';
                }
            }
            $html[] = '</select>';
            if ($id) {
                $html[] = '<script type="text/javascript">';
                $html[] = '(function ($) {';
                $html[] = '$(document).ready(function () {';
                $html[] = '$("#' . $id . '").select2({';
                $html[] = 'placeholder: "' . $text . '",';
                $html[] = 'allowClear: true';
                $html[] = '});';
                $html[] = '});';
                $html[] = '})(jQuery);';
                $html[] = '</script>';
            }
            echo implode($html);
        }

        function getMessage($message, $type = 'success') {
            $html = array();
            $class = 'success';
            if ($type == 'error') {
                $class = 'error';
            }
            if ($type == 'notice') {
                $class = 'notice';
            }
            $html[] = '<div class="in-message ' . $class . '">';
            $html[] = '<div class="message-text">' . $message . '</div>';
            $html[] = '</div>';
            return implode($html);
        }

        /**
         * Function check and create alias
         * @param type $title
         * @param type $isCopy
         * @return type
         */
        public static function createAlias($title, $table, $isCopy = FALSE) {
            require_once 'classes/unicodetoascii.php';
            if (class_exists('unicodetoascii')) {
                $calias = new unicodetoascii();
                $alias = $calias->asciiAliasCreate($title);
            } else {
                $alias = str_replace(' ', '-', strtolower($title));
            }
//xu ly truong hop alias duoc tao ra do copy tu 1 item khac
            if ($isCopy) {
                $newAlias = explode('-', $alias);
                if (count($newAlias) > 1 && is_numeric(end($newAlias))) {
                    unset($newAlias[count($newAlias) - 1]);
                }
                $alias = implode('-', $newAlias);
            }
            $listAlias = self::getAllAlias($alias, $table);
            $alias = self::generateAlias($alias, $listAlias);
            return $alias;
        }

        /**
         * function create alias
         * 
         * @param String $alias
         * @param Array $listAlias
         * @return string
         */
        static function generateAlias($alias, $listAlias) {
            if ($listAlias) {
                $listEndAlias = array();
                foreach ($listAlias as $value) {
                    $parseAlias = explode("-", $value['alias']);
                    if (is_numeric(end($parseAlias))) {
                        $listEndAlias[] = end($parseAlias);
                    }
                }
                if (empty($listEndAlias)) {
                    $alias = $alias . '-2';
                } else {
                    $endmax = max($listEndAlias);
                    $alias = $alias . '-' . ($endmax + 1);
                }
            }
            return $alias;
        }

        /**
         * function takes on all the alias alias similar to the present
         * @global type $wpdb
         * @param String $alias
         * @return Array list alias
         */
        static function getAllAlias($alias, $table) {
            global $wpdb;
            $listAlias = $wpdb->get_results('SELECT id, alias FROM ' . $wpdb->prefix . $table . ' WHERE alias LIKE "' . $alias . '%"');
            foreach ($listAlias as $value) {
                $rs[] = array('id' => $value->id, 'alias' => $value->alias);
            }
            return $rs;
        }

        public function MakeTree($categories, $id = 0) {
            $tree = array();
            $tree = self::TreeTitle($categories, $tree, 0);
            $tree_array = array();
            if ($id > 0) {
                $tree_sub = array();
                $id_sub = '';
                $subcategories = self::SubTree($categories, $tree_sub, 0, $id_sub);
                foreach ($subcategories as $key0 => $value0) {
                    $subcategories_array[$key0] = explode(',', $value0);
                }

                foreach ($tree as $key => $value) {

                    foreach ($categories as $key2 => $value2) {
                        $syntax_check = 1;

                        if ($id == $key) {
                            $syntax_check = 0;
                        }

                        foreach ($subcategories_array as $key3 => $value3) {
                            foreach ($value3 as $key4 => $value4) {
                                if ($value4 == $id && $key == $key3) {
                                    $syntax_check = 0;
                                }
                            }
                        }

                        if ($syntax_check == 1) {
                            if ($key == $value2->value) {
                                $tree_object = new JObject();
                                $tree_object->text = $value;
                                $tree_object->value = $key;
                                $tree_array[] = $tree_object;
                            }
                        }
                    }
                }
            } else {
                foreach ($tree as $key => $value) {
                    foreach ($categories as $key2 => $value2) {
                        if ($key == $value2->value) {
                            $tree_object = new JObject();
                            $tree_object->text = $value;
                            $tree_object->value = $key;
                            $tree_array[] = $tree_object;
                        }
                    }
                }
            }
            return $tree_array;
        }

        static function TreeTitle($data, $tree, $id = 0, $text = '') {

            foreach ($data as $key) {
                $show_text = $text . $key->text;
                if ($key->parent_id == $id) {
                    $tree[$key->value] = $show_text;
                    $tree = self::TreeTitle($data, $tree, $key->value, $text . " -- ");
                }
            }
            return ($tree);
        }

        static function SubTree($data, $tree, $id = 0, $id_sub = '') {
            foreach ($data as $key) {
                $show_id_sub = $id_sub . $key->value;
                if ($key->parent_id == $id) {
                    $tree[$key->value] = $id_sub;
                    $tree = self::SubTree($data, $tree, $key->value, $show_id_sub . ",");
                }
            }
            return ($tree);
        }

        /**
         * 
         * @param type $email
         * @param type $type: order_created, order_change_status, order_info
         * @return type
         */
        function sendEmail($email, $data, $type) {
            global $inf_settings, $inf_order;
            $mail_template = $inf_settings['email_template'];
            $mail_content = '';
            $mail_title = '';
            $result = array();
            $result['success'] = false;
            $admin_email = get_option('admin_email');

            if (isset($data['full_name'])) {
                $inf_order->member = $data['full_name'];
            }
            if (isset($data['reason'])) {
                $inf_order->reason = $data['reason'];
            }

            switch ($type) {
                case 'order_created':
                case 'order_info':
                    $mail_title = strip_tags(apply_filters('the_content', $mail_template['order_info']['title']));
                    $mail_content = apply_filters('the_content', $mail_template['order_info']['content']);
                    break;
                case 'order_change_status':
                    $mail_title = strip_tags(apply_filters('the_content', $mail_template['order_change_state']['title']));
                    $mail_content = apply_filters('the_content', $mail_template['order_change_state']['content']);
                    break;
                case 'user_register':
                    $mail_title = strip_tags(apply_filters('the_content', $mail_template['register_info']['title']));
                    $mail_content = apply_filters('the_content', $mail_template['register_info']['content']);
                    break;
                case 'offline_payment_notice':
                    $mail_title = __('Thanks for donate', 'inwavethemes');

                    $mail_content = apply_filters('the_content', $inf_settings['inf_payment']['custom_payment']['content']);
                    break;

                default:
                    break;
            }

            $html = '
<html>
<head>
  <title>' . $mail_title . '</title>
</head>
<body>' . $mail_content . '</body>
</html>
';

// To send HTML mail, the Content-type header must be set
            $headers = "From: [" . get_option('blogname') . "] <" . $admin_email . "> \r\n";
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

            if (wp_mail($email, $mail_title, $html, $headers)) {
                $result['success'] = true;
                $result['message'] = __('The email has been sent', 'inwavethemes');
            } else {
                $infLog = new inFundingLog();
                $infLog->addLog(new inFundingLog(null, 'error', time(), __('Can\'t send email to donor when donate, please check settings or code.', 'inwavethemes')));
                $result['message'] = __('Can\'t send email present', 'inwavethemes');
            }
            return serialize($result);
        }

        function prepareMemberFieldValue($value) {
            global $inf_settings;
            $memberinfo = array();
            $memberFields = $inf_settings['register_form_fields'];
            foreach ($value as $k => $v) {
                foreach ($memberFields as $field) {
                    if ($k == $field['name']) {
                        $val = $v;
                        if ($field['type'] == 'select') {
                            foreach ($field['values'] as $f_val) {
                                if ($v == $f_val['value']) {
                                    $val = $f_val;
                                    break;
                                }
                            }
                        }
                        $memberinfo[] = array('name' => $k, 'label' => $field['label'], 'type' => $field['type'], 'value' => $val);
                        break;
                    }
                }
            }
            return $memberinfo;
        }

        function getIWEventcurrencies() {
            return array(
                array('value' => 'AED', 'text' => __('United Arab Emirates dirham', 'inwavethemes')),
                array('value' => 'AFN', 'text' => __('Afghan afghani', 'inwavethemes')),
                array('value' => 'ALL', 'text' => __('Albanian lek', 'inwavethemes')),
                array('value' => 'AMD', 'text' => __('Armenian dram', 'inwavethemes')),
                array('value' => 'ANG', 'text' => __('Netherlands Antillean guilder', 'inwavethemes')),
                array('value' => 'AOA', 'text' => __('Angolan kwanza', 'inwavethemes')),
                array('value' => 'ARS', 'text' => __('Argentine peso', 'inwavethemes')),
                array('value' => 'AUD', 'text' => __('Australian dollar', 'inwavethemes')),
                array('value' => 'AWG', 'text' => __('Aruban florin', 'inwavethemes')),
                array('value' => 'AZN', 'text' => __('Azerbaijani manat', 'inwavethemes')),
                array('value' => 'BAM', 'text' => __('Bosnia and Herzegovina convertible mark', 'inwavethemes')),
                array('value' => 'BBD', 'text' => __('Barbadian dollar', 'inwavethemes')),
                array('value' => 'BDT', 'text' => __('Bangladeshi taka', 'inwavethemes')),
                array('value' => 'BGN', 'text' => __('Bulgarian lev', 'inwavethemes')),
                array('value' => 'BHD', 'text' => __('Bahraini dinar', 'inwavethemes')),
                array('value' => 'BIF', 'text' => __('Burundian franc', 'inwavethemes')),
                array('value' => 'BMD', 'text' => __('Bermudian dollar', 'inwavethemes')),
                array('value' => 'BND', 'text' => __('Brunei dollar', 'inwavethemes')),
                array('value' => 'BOB', 'text' => __('Bolivian boliviano', 'inwavethemes')),
                array('value' => 'BRL', 'text' => __('Brazilian real', 'inwavethemes')),
                array('value' => 'BSD', 'text' => __('Bahamian dollar', 'inwavethemes')),
                array('value' => 'BTC', 'text' => __('Bitcoin', 'inwavethemes')),
                array('value' => 'BTN', 'text' => __('Bhutanese ngultrum', 'inwavethemes')),
                array('value' => 'BWP', 'text' => __('Botswana pula', 'inwavethemes')),
                array('value' => 'BYR', 'text' => __('Belarusian ruble', 'inwavethemes')),
                array('value' => 'BZD', 'text' => __('Belize dollar', 'inwavethemes')),
                array('value' => 'CAD', 'text' => __('Canadian dollar', 'inwavethemes')),
                array('value' => 'CDF', 'text' => __('Congolese franc', 'inwavethemes')),
                array('value' => 'CHF', 'text' => __('Swiss franc', 'inwavethemes')),
                array('value' => 'CLP', 'text' => __('Chilean peso', 'inwavethemes')),
                array('value' => 'CNY', 'text' => __('Chinese yuan', 'inwavethemes')),
                array('value' => 'COP', 'text' => __('Colombian peso', 'inwavethemes')),
                array('value' => 'CRC', 'text' => __('Costa Rican col&oacute;n', 'inwavethemes')),
                array('value' => 'CUC', 'text' => __('Cuban convertible peso', 'inwavethemes')),
                array('value' => 'CUP', 'text' => __('Cuban peso', 'inwavethemes')),
                array('value' => 'CVE', 'text' => __('Cape Verdean escudo', 'inwavethemes')),
                array('value' => 'CZK', 'text' => __('Czech koruna', 'inwavethemes')),
                array('value' => 'DJF', 'text' => __('Djiboutian franc', 'inwavethemes')),
                array('value' => 'DKK', 'text' => __('Danish krone', 'inwavethemes')),
                array('value' => 'DOP', 'text' => __('Dominican peso', 'inwavethemes')),
                array('value' => 'DZD', 'text' => __('Algerian dinar', 'inwavethemes')),
                array('value' => 'EGP', 'text' => __('Egyptian pound', 'inwavethemes')),
                array('value' => 'ERN', 'text' => __('Eritrean nakfa', 'inwavethemes')),
                array('value' => 'ETB', 'text' => __('Ethiopian birr', 'inwavethemes')),
                array('value' => 'EUR', 'text' => __('Euro', 'inwavethemes')),
                array('value' => 'FJD', 'text' => __('Fijian dollar', 'inwavethemes')),
                array('value' => 'FKP', 'text' => __('Falkland Islands pound', 'inwavethemes')),
                array('value' => 'GBP', 'text' => __('Pound sterling', 'inwavethemes')),
                array('value' => 'GEL', 'text' => __('Georgian lari', 'inwavethemes')),
                array('value' => 'GGP', 'text' => __('Guernsey pound', 'inwavethemes')),
                array('value' => 'GHS', 'text' => __('Ghana cedi', 'inwavethemes')),
                array('value' => 'GIP', 'text' => __('Gibraltar pound', 'inwavethemes')),
                array('value' => 'GMD', 'text' => __('Gambian dalasi', 'inwavethemes')),
                array('value' => 'GNF', 'text' => __('Guinean franc', 'inwavethemes')),
                array('value' => 'GTQ', 'text' => __('Guatemalan quetzal', 'inwavethemes')),
                array('value' => 'GYD', 'text' => __('Guyanese dollar', 'inwavethemes')),
                array('value' => 'HKD', 'text' => __('Hong Kong dollar', 'inwavethemes')),
                array('value' => 'HNL', 'text' => __('Honduran lempira', 'inwavethemes')),
                array('value' => 'HRK', 'text' => __('Croatian kuna', 'inwavethemes')),
                array('value' => 'HTG', 'text' => __('Haitian gourde', 'inwavethemes')),
                array('value' => 'HUF', 'text' => __('Hungarian forint', 'inwavethemes')),
                array('value' => 'IDR', 'text' => __('Indonesian rupiah', 'inwavethemes')),
                array('value' => 'ILS', 'text' => __('Israeli new shekel', 'inwavethemes')),
                array('value' => 'IMP', 'text' => __('Manx pound', 'inwavethemes')),
                array('value' => 'INR', 'text' => __('Indian rupee', 'inwavethemes')),
                array('value' => 'IQD', 'text' => __('Iraqi dinar', 'inwavethemes')),
                array('value' => 'IRR', 'text' => __('Iranian rial', 'inwavethemes')),
                array('value' => 'ISK', 'text' => __('Icelandic kr&oacute;na', 'inwavethemes')),
                array('value' => 'JEP', 'text' => __('Jersey pound', 'inwavethemes')),
                array('value' => 'JMD', 'text' => __('Jamaican dollar', 'inwavethemes')),
                array('value' => 'JOD', 'text' => __('Jordanian dinar', 'inwavethemes')),
                array('value' => 'JPY', 'text' => __('Japanese yen', 'inwavethemes')),
                array('value' => 'KES', 'text' => __('Kenyan shilling', 'inwavethemes')),
                array('value' => 'KGS', 'text' => __('Kyrgyzstani som', 'inwavethemes')),
                array('value' => 'KHR', 'text' => __('Cambodian riel', 'inwavethemes')),
                array('value' => 'KMF', 'text' => __('Comorian franc', 'inwavethemes')),
                array('value' => 'KPW', 'text' => __('North Korean won', 'inwavethemes')),
                array('value' => 'KRW', 'text' => __('South Korean won', 'inwavethemes')),
                array('value' => 'KWD', 'text' => __('Kuwaiti dinar', 'inwavethemes')),
                array('value' => 'KYD', 'text' => __('Cayman Islands dollar', 'inwavethemes')),
                array('value' => 'KZT', 'text' => __('Kazakhstani tenge', 'inwavethemes')),
                array('value' => 'LAK', 'text' => __('Lao kip', 'inwavethemes')),
                array('value' => 'LBP', 'text' => __('Lebanese pound', 'inwavethemes')),
                array('value' => 'LKR', 'text' => __('Sri Lankan rupee', 'inwavethemes')),
                array('value' => 'LRD', 'text' => __('Liberian dollar', 'inwavethemes')),
                array('value' => 'LSL', 'text' => __('Lesotho loti', 'inwavethemes')),
                array('value' => 'LYD', 'text' => __('Libyan dinar', 'inwavethemes')),
                array('value' => 'MAD', 'text' => __('Moroccan dirham', 'inwavethemes')),
                array('value' => 'MDL', 'text' => __('Moldovan leu', 'inwavethemes')),
                array('value' => 'MGA', 'text' => __('Malagasy ariary', 'inwavethemes')),
                array('value' => 'MKD', 'text' => __('Macedonian denar', 'inwavethemes')),
                array('value' => 'MMK', 'text' => __('Burmese kyat', 'inwavethemes')),
                array('value' => 'MNT', 'text' => __('Mongolian t&ouml;gr&ouml;g', 'inwavethemes')),
                array('value' => 'MOP', 'text' => __('Macanese pataca', 'inwavethemes')),
                array('value' => 'MRO', 'text' => __('Mauritanian ouguiya', 'inwavethemes')),
                array('value' => 'MUR', 'text' => __('Mauritian rupee', 'inwavethemes')),
                array('value' => 'MVR', 'text' => __('Maldivian rufiyaa', 'inwavethemes')),
                array('value' => 'MWK', 'text' => __('Malawian kwacha', 'inwavethemes')),
                array('value' => 'MXN', 'text' => __('Mexican peso', 'inwavethemes')),
                array('value' => 'MYR', 'text' => __('Malaysian ringgit', 'inwavethemes')),
                array('value' => 'MZN', 'text' => __('Mozambican metical', 'inwavethemes')),
                array('value' => 'NAD', 'text' => __('Namibian dollar', 'inwavethemes')),
                array('value' => 'NGN', 'text' => __('Nigerian naira', 'inwavethemes')),
                array('value' => 'NIO', 'text' => __('Nicaraguan c&oacute;rdoba', 'inwavethemes')),
                array('value' => 'NOK', 'text' => __('Norwegian krone', 'inwavethemes')),
                array('value' => 'NPR', 'text' => __('Nepalese rupee', 'inwavethemes')),
                array('value' => 'NZD', 'text' => __('New Zealand dollar', 'inwavethemes')),
                array('value' => 'OMR', 'text' => __('Omani rial', 'inwavethemes')),
                array('value' => 'PAB', 'text' => __('Panamanian balboa', 'inwavethemes')),
                array('value' => 'PEN', 'text' => __('Peruvian nuevo sol', 'inwavethemes')),
                array('value' => 'PGK', 'text' => __('Papua New Guinean kina', 'inwavethemes')),
                array('value' => 'PHP', 'text' => __('Philippine peso', 'inwavethemes')),
                array('value' => 'PKR', 'text' => __('Pakistani rupee', 'inwavethemes')),
                array('value' => 'PLN', 'text' => __('Polish z&#x142;oty', 'inwavethemes')),
                array('value' => 'PRB', 'text' => __('Transnistrian ruble', 'inwavethemes')),
                array('value' => 'PYG', 'text' => __('Paraguayan guaran&iacute;', 'inwavethemes')),
                array('value' => 'QAR', 'text' => __('Qatari riyal', 'inwavethemes')),
                array('value' => 'RON', 'text' => __('Romanian leu', 'inwavethemes')),
                array('value' => 'RSD', 'text' => __('Serbian dinar', 'inwavethemes')),
                array('value' => 'RUB', 'text' => __('Russian ruble', 'inwavethemes')),
                array('value' => 'RWF', 'text' => __('Rwandan franc', 'inwavethemes')),
                array('value' => 'SAR', 'text' => __('Saudi riyal', 'inwavethemes')),
                array('value' => 'SBD', 'text' => __('Solomon Islands dollar', 'inwavethemes')),
                array('value' => 'SCR', 'text' => __('Seychellois rupee', 'inwavethemes')),
                array('value' => 'SDG', 'text' => __('Sudanese pound', 'inwavethemes')),
                array('value' => 'SEK', 'text' => __('Swedish krona', 'inwavethemes')),
                array('value' => 'SGD', 'text' => __('Singapore dollar', 'inwavethemes')),
                array('value' => 'SHP', 'text' => __('Saint Helena pound', 'inwavethemes')),
                array('value' => 'SLL', 'text' => __('Sierra Leonean leone', 'inwavethemes')),
                array('value' => 'SOS', 'text' => __('Somali shilling', 'inwavethemes')),
                array('value' => 'SRD', 'text' => __('Surinamese dollar', 'inwavethemes')),
                array('value' => 'SSP', 'text' => __('South Sudanese pound', 'inwavethemes')),
                array('value' => 'STD', 'text' => __('S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'inwavethemes')),
                array('value' => 'SYP', 'text' => __('Syrian pound', 'inwavethemes')),
                array('value' => 'SZL', 'text' => __('Swazi lilangeni', 'inwavethemes')),
                array('value' => 'THB', 'text' => __('Thai baht', 'inwavethemes')),
                array('value' => 'TJS', 'text' => __('Tajikistani somoni', 'inwavethemes')),
                array('value' => 'TMT', 'text' => __('Turkmenistan manat', 'inwavethemes')),
                array('value' => 'TND', 'text' => __('Tunisian dinar', 'inwavethemes')),
                array('value' => 'TOP', 'text' => __('Tongan pa&#x2bb;anga', 'inwavethemes')),
                array('value' => 'TRY', 'text' => __('Turkish lira', 'inwavethemes')),
                array('value' => 'TTD', 'text' => __('Trinidad and Tobago dollar', 'inwavethemes')),
                array('value' => 'TWD', 'text' => __('New Taiwan dollar', 'inwavethemes')),
                array('value' => 'TZS', 'text' => __('Tanzanian shilling', 'inwavethemes')),
                array('value' => 'UAH', 'text' => __('Ukrainian hryvnia', 'inwavethemes')),
                array('value' => 'UGX', 'text' => __('Ugandan shilling', 'inwavethemes')),
                array('value' => 'USD', 'text' => __('United States dollar', 'inwavethemes')),
                array('value' => 'UYU', 'text' => __('Uruguayan peso', 'inwavethemes')),
                array('value' => 'UZS', 'text' => __('Uzbekistani som', 'inwavethemes')),
                array('value' => 'VEF', 'text' => __('Venezuelan bol&iacute;var', 'inwavethemes')),
                array('value' => 'VND', 'text' => __('Vietnamese &#x111;&#x1ed3;ng', 'inwavethemes')),
                array('value' => 'VUV', 'text' => __('Vanuatu vatu', 'inwavethemes')),
                array('value' => 'WST', 'text' => __('Samoan t&#x101;l&#x101;', 'inwavethemes')),
                array('value' => 'XAF', 'text' => __('Central African CFA franc', 'inwavethemes')),
                array('value' => 'XCD', 'text' => __('East Caribbean dollar', 'inwavethemes')),
                array('value' => 'XOF', 'text' => __('West African CFA franc', 'inwavethemes')),
                array('value' => 'XPF', 'text' => __('CFP franc', 'inwavethemes')),
                array('value' => 'YER', 'text' => __('Yemeni rial', 'inwavethemes')),
                array('value' => 'ZAR', 'text' => __('South African rand', 'inwavethemes')),
                array('value' => 'ZMW', 'text' => __('Zambian kwacha', 'inwavethemes'))
            );
        }

        public static function getIWCurrencySymbol($currency) {
            $symbols = array(
                'AED' => '&#x62f;.&#x625;',
                'AFN' => '&#x60b;',
                'ALL' => 'L',
                'AMD' => 'AMD',
                'ANG' => '&fnof;',
                'AOA' => 'Kz',
                'ARS' => '&#36;',
                'AUD' => '&#36;',
                'AWG' => '&fnof;',
                'AZN' => 'AZN',
                'BAM' => 'KM',
                'BBD' => '&#36;',
                'BDT' => '&#2547;&nbsp;',
                'BGN' => '&#1083;&#1074;.',
                'BHD' => '.&#x62f;.&#x628;',
                'BIF' => 'Fr',
                'BMD' => '&#36;',
                'BND' => '&#36;',
                'BOB' => 'Bs.',
                'BRL' => '&#82;&#36;',
                'BSD' => '&#36;',
                'BTC' => '&#3647;',
                'BTN' => 'Nu.',
                'BWP' => 'P',
                'BYR' => 'Br',
                'BZD' => '&#36;',
                'CAD' => '&#36;',
                'CDF' => 'Fr',
                'CHF' => '&#67;&#72;&#70;',
                'CLP' => '&#36;',
                'CNY' => '&yen;',
                'COP' => '&#36;',
                'CRC' => '&#x20a1;',
                'CUC' => '&#36;',
                'CUP' => '&#36;',
                'CVE' => '&#36;',
                'CZK' => '&#75;&#269;',
                'DJF' => 'Fr',
                'DKK' => 'DKK',
                'DOP' => 'RD&#36;',
                'DZD' => '&#x62f;.&#x62c;',
                'EGP' => 'EGP',
                'ERN' => 'Nfk',
                'ETB' => 'Br',
                'EUR' => '&euro;',
                'FJD' => '&#36;',
                'FKP' => '&pound;',
                'GBP' => '&pound;',
                'GEL' => '&#x10da;',
                'GGP' => '&pound;',
                'GHS' => '&#x20b5;',
                'GIP' => '&pound;',
                'GMD' => 'D',
                'GNF' => 'Fr',
                'GTQ' => 'Q',
                'GYD' => '&#36;',
                'HKD' => '&#36;',
                'HNL' => 'L',
                'HRK' => 'Kn',
                'HTG' => 'G',
                'HUF' => '&#70;&#116;',
                'IDR' => 'Rp',
                'ILS' => '&#8362;',
                'IMP' => '&pound;',
                'INR' => '&#8377;',
                'IQD' => '&#x639;.&#x62f;',
                'IRR' => '&#xfdfc;',
                'ISK' => 'Kr.',
                'JEP' => '&pound;',
                'JMD' => '&#36;',
                'JOD' => '&#x62f;.&#x627;',
                'JPY' => '&yen;',
                'KES' => 'KSh',
                'KGS' => '&#x43b;&#x432;',
                'KHR' => '&#x17db;',
                'KMF' => 'Fr',
                'KPW' => '&#x20a9;',
                'KRW' => '&#8361;',
                'KWD' => '&#x62f;.&#x643;',
                'KYD' => '&#36;',
                'KZT' => 'KZT',
                'LAK' => '&#8365;',
                'LBP' => '&#x644;.&#x644;',
                'LKR' => '&#xdbb;&#xdd4;',
                'LRD' => '&#36;',
                'LSL' => 'L',
                'LYD' => '&#x644;.&#x62f;',
                'MAD' => '&#x62f;. &#x645;.',
                'MAD' => '&#x62f;.&#x645;.',
                'MDL' => 'L',
                'MGA' => 'Ar',
                'MKD' => '&#x434;&#x435;&#x43d;',
                'MMK' => 'Ks',
                'MNT' => '&#x20ae;',
                'MOP' => 'P',
                'MRO' => 'UM',
                'MUR' => '&#x20a8;',
                'MVR' => '.&#x783;',
                'MWK' => 'MK',
                'MXN' => '&#36;',
                'MYR' => '&#82;&#77;',
                'MZN' => 'MT',
                'NAD' => '&#36;',
                'NGN' => '&#8358;',
                'NIO' => 'C&#36;',
                'NOK' => '&#107;&#114;',
                'NPR' => '&#8360;',
                'NZD' => '&#36;',
                'OMR' => '&#x631;.&#x639;.',
                'PAB' => 'B/.',
                'PEN' => 'S/.',
                'PGK' => 'K',
                'PHP' => '&#8369;',
                'PKR' => '&#8360;',
                'PLN' => '&#122;&#322;',
                'PRB' => '&#x440;.',
                'PYG' => '&#8370;',
                'QAR' => '&#x631;.&#x642;',
                'RMB' => '&yen;',
                'RON' => 'lei',
                'RSD' => '&#x434;&#x438;&#x43d;.',
                'RUB' => '&#8381;',
                'RWF' => 'Fr',
                'SAR' => '&#x631;.&#x633;',
                'SBD' => '&#36;',
                'SCR' => '&#x20a8;',
                'SDG' => '&#x62c;.&#x633;.',
                'SEK' => '&#107;&#114;',
                'SGD' => '&#36;',
                'SHP' => '&pound;',
                'SLL' => 'Le',
                'SOS' => 'Sh',
                'SRD' => '&#36;',
                'SSP' => '&pound;',
                'STD' => 'Db',
                'SYP' => '&#x644;.&#x633;',
                'SZL' => 'L',
                'THB' => '&#3647;',
                'TJS' => '&#x405;&#x41c;',
                'TMT' => 'm',
                'TND' => '&#x62f;.&#x62a;',
                'TOP' => 'T&#36;',
                'TRY' => '&#8378;',
                'TTD' => '&#36;',
                'TWD' => '&#78;&#84;&#36;',
                'TZS' => 'Sh',
                'UAH' => '&#8372;',
                'UGX' => 'UGX',
                'USD' => '&#36;',
                'UYU' => '&#36;',
                'UZS' => 'UZS',
                'VEF' => 'Bs F',
                'VND' => '&#8363;',
                'VUV' => 'Vt',
                'WST' => 'T',
                'XAF' => 'Fr',
                'XCD' => '&#36;',
                'XOF' => 'Fr',
                'XPF' => 'Fr',
                'YER' => '&#xfdfc;',
                'ZAR' => '&#82;',
                'ZMW' => 'ZK'
            );
            return $symbols[$currency];
        }

        /**
         * Function truncate string by number of word
         * @param string $string
         * @param type $length
         * @param type $etc
         * @return string
         */
        public function truncateString($string, $length, $etc = '...') {
            $string = strip_tags($string);
            if (str_word_count($string) > $length) {
                $words = str_word_count($string, 2);
                $pos = array_keys($words);
                $string = substr($string, 0, $pos[$length]) . $etc;
            }
            return $string;
        }

        public function inFundingAddImageSize() {
            add_image_size('infunding-large', 800, 420, 'center');
            add_image_size('infunding-thumb', 400, 250, 'center');
        }

        public function iweDisplayPagination($query = '') {
            if (!$query) {
                global $wp_query;
                $query = $wp_query;
            }

            $big = 999999999; // need an unlikely integer

            $paginate_links = paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => $query->max_num_pages,
                'next_text' => '&raquo;',
                'prev_text' => '&laquo'
            ));
            // Display the pagination if more than one page is found
            if ($paginate_links) :
                ?>

                <div class="iwevent-pagination clearfix">
                    <?php echo esc_html($paginate_links); ?>
                </div>

                <?php
            endif;
        }

        public function initPluginThemes() {
            $files = array('single-infunding.php', 'taxonomy-infunding_category.php');
            $template_path = get_template_directory();
            foreach ($files as $file) {
                if (!file_exists($template_path . '/' . $file)) {
                    $theme_plugin_path = WP_PLUGIN_DIR . '/infunding/includes/themes/';
                    copy($theme_plugin_path . $file, $template_path . '/' . $file);
                }
            }
        }

        public function getTicketPriceLabel($price) {
            $iwe_settings = unserialize(get_option('iwe_settings'));
            $currency = $iwe_settings['general']['currency'];
            $currency_pos = $iwe_settings['general']['currency_pos'];
            $cSymbol = $this->getIWCurrencySymbol($currency);
            $priceLabel = __('Free', 'inwavethemes');
            if ($price) {
                switch ($currency_pos) {
                    case 'left':
                        $priceLabel = $cSymbol . $price;
                        break;
                    case 'left_space':
                        $priceLabel = $cSymbol . ' ' . $price;
                        break;
                    case 'right':
                        $priceLabel = $price . $cSymbol;
                        break;
                    case 'right_space':
                        $priceLabel = $price . ' ' . $cSymbol;
                        break;

                    default:
                        break;
                }
            }
            return $priceLabel;
        }

        public function inFundingRenderMap($options = null) {
            global $inf_settings;
            $map = json_decode($options['map']);
            $query = $this->getCampaignsList($options['cat'], $options['ids'], 'date', 'desc', '-1', true);
            $places = array();
            if ($query->have_posts()) {
                while ($query->have_posts()) :
                    $query->the_post();
                    $address = htmlspecialchars(get_post_meta(get_the_ID(), 'inf_address', true));
                    $map_pos = unserialize(get_post_meta(get_the_ID(), 'inf_map_pos', true));
                    $img = wp_get_attachment_image_src(get_post_thumbnail_id());
                    $p = new stdClass();
                    $p->id = 'pid-' . get_the_ID();
                    $p->link = get_permalink();
                    $p->readmore = __('Reard More', 'inwavethemes');
                    $p->title = get_the_title();
                    $p->image = $img[0];
                    $p->address = $address;
                    $p->latitude = $map_pos['latitude'];
                    $p->longitude = $map_pos['longitude'];
                    $p->description = $this->truncateString(get_the_excerpt(), $options['desc_text_limit']);
                    $places[] = $p;
                endwhile;
            }
            wp_reset_postdata();

            $styleObj = '[{"featureType": "landscape", "elementType": "labels", "stylers": [{"visibility": "off"}]}, {"featureType": "transit", "elementType": "labels", "stylers": [{"visibility": "off"}]}, {"featureType": "poi", "elementType": "labels", "stylers": [{"visibility": "off"}]}, {"featureType": "water", "elementType": "labels", "stylers": [{"visibility": "off"}]}, {"featureType": "road", "elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {"stylers": [{"hue": "#00aaff"}, {"saturation": -100}, {"gamma": 2.15}, {"lightness": 12}]}, {"featureType": "road", "elementType": "labels.text.fill", "stylers": [{"visibility": "on"}, {"lightness": 24}]}, {"featureType": "road", "elementType": "geometry", "stylers": [{"lightness": 57}]}]';

            $script = array();
            $script[] = 'var options = {';
            $script[] = 'mapPlaces : ' . json_encode($places) . ',';
            $script[] = 'mapProperties:{';
            $script[] = 'zoom : ' . ($map->zoomlv ? $map->zoomlv : $inf_settings['general']['map_zoom_level']) . ',';
            $script[] = 'center : new google.maps.LatLng(' . (isset($map->lat) ? $map->lat : -33.8665433) . ', ' . (isset($map->lng) ? $map->lng : 151.1956316) . '),';
            $script[] = 'zoomControl : true,';
            $script[] = 'scrollwheel : true,';
            $script[] = 'disableDoubleClickZoom : true,';
            $script[] = 'draggable : true,';
            $script[] = 'panControl : true,';
            $script[] = 'mapTypeControl : true,';
            $script[] = 'scaleControl : true,';
            $script[] = 'overviewMapControl : true,';
            $script[] = 'mapTypeId : google.maps.MapTypeId.ROADMAP,';
            $script[] = '},';
            $script[] = 'detail_page:false,';
            $script[] = 'show_location:' . ($options['show_location'] ? 'true' : 'false') . ',';
            $script[] = 'show_des:' . ($options['show_des'] ? 'true' : 'false') . ',';
            $script[] = 'spinurl:"' . plugins_url('infunding/assets/images/') . '",';
            $script[] = 'styleObj: {"name":"","override_default":"1","styles":""}';
            $script[] = '};';
            $script[] = 'jQuery(".infunding-map").infMap(options);';

            echo '(function(){' . implode($script) . '})();';
        }

        public function getCampaignsList($cats, $ids, $order_by, $order_dir, $item_per_page, $filter = false, $page = 'page') {
            if ($page == 'page') {
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            } else {
                $paged = (get_query_var('page')) ? get_query_var('page') : 1;
            }
            $terms = isset($_REQUEST['category']) ? $_REQUEST['category'] : '';
            $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
            $order_byn = isset($_REQUEST['order_by']) ? $_REQUEST['order_by'] : $order_by;
            $order_dirn = isset($_REQUEST['order_dir']) ? $_REQUEST['order_dir'] : $order_dir;

            $args = array();
            if ($ids) {
                $args['post__in'] = explode(',', $ids);
                if ($terms) {
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'infunding_category',
                            'terms' => array($terms),
                            'include_children' => false
                        ),
                    );
                }
            } else {
                $cat_array = explode(',', $cats);
                $new_cats = array();
                if ($terms) {
                    $new_cats[] = $terms;
                } else {
                    if (in_array('0', $cat_array) || empty($cat_array)) {
                        $res = get_terms('infunding_category');
                        foreach ($res as $value) {
                            $new_cats[] = $value->term_id;
                        }
                    } else {
                        $new_cats = $cat_array;
                    }
                }
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'infunding_category',
                        'terms' => $new_cats,
                        'include_children' => false
                    ),
                );
            }
            $args['post_type'] = 'infunding';
            $args['s'] = $keyword;
            $args['order'] = ($order_dirn) ? $order_dirn : 'desc';
            if ($order_byn == 'time_remaning') {
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = 'inf_end_date';
            } else if ($order_byn == 'goal') {
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = 'inf_goal';
            } else if ($order_byn == 'current') {
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = 'inf_current';
            } else {
                $args['orderby'] = ($order_byn) ? $order_byn : 'ID';
            }
            if ($filter) {
                $args['meta_query'] = array(
                    array(
                        'key' => 'inf_end_date',
                        'value' => time(),
                        'compare' => '<',
                    )
                );
            }
            $args['post_status'] = 'publish';
            $args['posts_per_page'] = $item_per_page;
            $args['paged'] = $paged;
            $query = new WP_Query($args);
            return $query;
        }

        public function infunding_display_pagination_none($query = '') {
            $rs = array('success' => false, 'data' => '');
            if (!$query) {
                global $wp_query;
                $query = $wp_query;
            }

            $paginate_links = paginate_links(array(
                'format' => '?page=%#%',
                'current' => max(1, get_query_var('page')),
                'total' => $query->max_num_pages
            ));
            // Display the pagination if more than one page is found
            if ($paginate_links) :
                $html = array();
                $html[] = '<div class="post-pagination clearfix" style="display: none;">';
                $html[] = $paginate_links;
                $html[] = '</div>';
                $rs['success'] = true;
                $rs['data'] = implode($html);
            endif;
            return $rs;
        }

        public function infunding_display_pagination($query = '', $page = 'page') {
            $rs = array('success' => false, 'data' => '');
            if (!$query) {
                global $wp_query;
                $query = $wp_query;
            }

            $link_args = array(
                'total' => $query->max_num_pages,
                'prev_text' => __('<'),
                'next_text' => __('>'));
            if ($page == 'page') {
                $link_args['format'] = '?paged=%#%';
                $link_args['current'] = max(1, get_query_var('paged'));
            } else {
                $link_args['format'] = '?page=%#%';
                $link_args['current'] = max(1, get_query_var('page'));
            }
            $paginate_links = paginate_links($link_args);
            // Display the pagination if more than one page is found
            if ($paginate_links) :
                $html = array();
                $html[] = '<div class="post-pagination clearfix">';
                $html[] = $paginate_links;
                $html[] = '</div>';
                $rs['success'] = true;
                $rs['data'] = implode($html);
            endif;
            return $rs;
        }

        public function getInfundingFilterForm($cat) {
            $cats = explode(',', $cat);
            $html = array();
            $keyword = filter_input(INPUT_GET, 'keyword');
            $order_by = filter_input(INPUT_GET, 'order_by') ? filter_input(INPUT_GET, 'order_by') : 'time_remaning';
            $order_dir = filter_input(INPUT_GET, 'order_dir') ? filter_input(INPUT_GET, 'order_dir') : 'desc';
            $cat_select = filter_input(INPUT_GET, 'category');
            $html[] = '<form id="filterForm" name="filterForm" action="" method="get">';
            $html[] = '<input type="text" class="filter-field" placeholder="' . __('Enter your keywords', 'inwavethemes') . '" name="keyword" value="' . $keyword . '"/>';
            if (in_array('0', $cats)) {
                $cats_data = array();
                $cat_obs = get_terms('infunding_category');
                $cats_data[] = array('text' => __('All Categories', 'inwavethemes'), 'value' => '');
                foreach ($cat_obs as $co) {
                    $cats_data[] = array('text' => $co->name, 'value' => $co->term_id);
                }
                $html[] = $this->selectFieldRender('', 'category', $cat_select, $cats_data, '', 'filter-field', FALSE);
            }

            $order_data = array(
                array('value' => 'ID', 'text' => 'ID'),
                array('value' => 'post_title', 'text' => __('Title', 'inwavethemes')),
                array('value' => 'time_remaning', 'text' => __('Remaing Day', 'inwavethemes')),
                array('value' => 'goal', 'text' => __('Goal', 'inwavethemes')),
                array('value' => 'current', 'text' => __('Funded Amount', 'inwavethemes')),
                array('value' => 'date', 'text' => __('Created', 'inwavethemes')),
                array('value' => 'modified', 'text' => __('Last Modified', 'inwavethemes')),
            );
            $html[] = $this->selectFieldRender('', 'order_by', $order_by, $order_data, '', 'filter-field', FALSE);
            $html[] = '<span class="order-dir filter-field"><i class="fa fa-sort-amount-' . $order_dir . '"></i><input type="hidden" value="' . $order_dir . '" name="order_dir"/></span>';
            $html[] = '</form>';
            echo implode('', $html);
        }

        function getCampaignInfo($post) {
            if (is_numeric($post)) {
                $post = get_post($post);
            }
            $inf_cache = wp_cache_get('campaign_' . $post->ID);
            if ($inf_cache) {
                return $inf_cache;
            }
            $order = new inFundingOrder();
            $campaign = new stdClass();
            $campaign->id = $post->ID;
            $campaign->title = $post->post_title;
            $campaign->content = $post->post_content;
            $campaign->categories = wp_get_post_terms($post->ID, 'infunding_category');
            if (is_wp_error($campaign->categories)) {
                $campaign->categories = array();
            }
            $campaign->tags = wp_get_post_terms($post->ID, 'infunding_tag');
            if (is_wp_error($campaign->tags)) {
                $campaign->tags = array();
            }
            $campaign->images = unserialize(get_post_meta($post->ID, 'inf_image_gallery', true));
            $campaign->time_start = htmlspecialchars(get_post_meta($post->ID, 'inf_start_date', true));
            $campaign->time_end = htmlspecialchars(get_post_meta($post->ID, 'inf_end_date', true));
            $campaign->days_to_start = floor(($campaign->time_start - time()) / 86400);
            $campaign->days_to_end = floor(($campaign->time_end - time()) / 86400);
            $campaign->status = ((!$campaign->time_end && $campaign->days_to_start < 0) || ($campaign->days_to_start <= 0 && 0 <= $campaign->days_to_end));
            $campaign->address = get_post_meta($post->ID, 'inf_address', true);
            $campaign->map_pos = unserialize(get_post_meta($post->ID, 'inf_map_pos', true));
            $campaign->current = htmlspecialchars(get_post_meta($post->ID, 'inf_current', true));
            $campaign->goal = htmlspecialchars(get_post_meta($post->ID, 'inf_goal', true));
            $campaign->currency = htmlspecialchars(get_post_meta($post->ID, 'inf_currency', true));
            $campaign->external_link = htmlspecialchars(get_post_meta($post->ID, 'inf_donate_link', true));
            if ($campaign->goal) {
                $campaign->percent = number_format($campaign->current / $campaign->goal * 100, 2);
                $campaign->p_delay = $campaign->percent;
                if ($campaign->percent >= 100) {
                    $campaign->p_delay = 100;
                }
            }
            $campaign->orders = $order->getOrderByCampaign($post->ID);

            wp_cache_set('campaign_' . $campaign->id, $campaign);
            return $campaign;
        }

        function getLocalDate($format, $timestamp) {
            $current_offset = get_option('gmt_offset');
            $date = date_i18n($format, $timestamp);
            if ($current_offset) {
                $date = date_i18n($format, $timestamp + $current_offset * 60 * 60);
            }
            echo esc_attr($date);
        }

        public function getMoneyFormated($value, $currency = '') {
            global $inf_settings;
            if (!$currency) {
                $currency = $inf_settings['general']['currency'];
            }
            $currency_sym = $this->getIWCurrencySymbol($currency);
            $currency_pos = $inf_settings['general']['currency_pos'];
            $result = $currency_sym . $value;
            if ($currency_pos == 'left_space') {
                $result = $currency_sym . ' ' . $value;
            }
            if ($currency_pos == 'right') {
                $result = $value . $currency_sym;
            }
            if ($currency_pos == 'right_space') {
                $result = $value . ' ' . $currency_sym;
            }
            echo esc_attr($result);
        }
	
        function getNoticeMessage(){
            $inwave_Session = new Inwave_Session();
            $message = $inwave_Session->get('inwave_message');
            if($message){
                printf($message);
                $inwave_Session->clearSession('inwave_message');
            }
        }

    }

}
