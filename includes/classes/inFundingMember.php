<?php

/*
 * @package Inwave Funding
 * @version 1.0.0
 * @created May 19, 2016
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of inFundingMember
 *
 * @developer duongca
 */
class inFundingMember {

    private $id;
    private $user_id;
    private $field_value;
    private $social_links;
    private $member_type;

    function getMember_type() {
        return $this->member_type;
    }

    function setMember_type($member_type) {
        $this->member_type = $member_type;
    }

    function getSocial_links() {
        return $this->social_links;
    }

    function setSocial_links($social_links) {
        $this->social_links = $social_links;
    }

    function getId() {
        return $this->id;
    }

    function getUser_id() {
        return $this->user_id;
    }

    function getField_value() {
        return $this->field_value;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setUser_id($user_id) {
        $this->user_id = $user_id;
    }

    function setField_value($field_value) {
        $this->field_value = $field_value;
    }

    public function addMember($member) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $data = get_object_vars($member);
        $ins = $wpdb->insert($wpdb->prefix . "inf_donors", $data);
        if ($ins) {
            $return['success'] = TRUE;
            $return['msg'] = 'Insert success';
            $return['data'] = $wpdb->insert_id;
        } else {
            $return['msg'] = $wpdb->last_error;
        }
        return serialize($return);
    }

    public function editMember($member) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $data = get_object_vars($member);
        unset($data['id']);
        foreach ($data as $k => $v) {
            if ($v === NULL) {
                unset($data[$k]);
            }
        }
        $update = $wpdb->update($wpdb->prefix . "inf_donors", $data, array('id' => $member->getId()));
        if ($update || $update == 0) {
            $return['success'] = TRUE;
            $return['data'] = $member->getId();
            $return['msg'] = 'Update success';
        } else {
            $return['msg'] = $wpdb->last_error;
        }
        return serialize($return);
    }

    public function updateMember($member) {
        $curent_user_id = get_current_user_id();
        if ($curent_user_id) {
            $member->setUser_id($curent_user_id);
            $check_customer = $member->getMemberByUser($curent_user_id, $member->getMember_type());
            if ($check_customer->getId()) {
                $customer_id = $check_customer->getId();
                $member->setId($customer_id);
                $member_process = unserialize($member->editMember($member));
            } else {
                $member_process = unserialize($member->addMember($member));
            }
        } else {
            $member_process = unserialize($member->addMember($member));
        }
        return $member_process;
    }

    public function getMembers($start, $limit = 20, $member_type='donor') {
        global $wpdb;
        $rs = array();
        $rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'inf_donors WHERE member_type=%s LIMIT %d,%d',$member_type, $start, $limit));
        if (!empty($rows)) {
            foreach ($rows as $value) {
                $member = new inFundingMember();
                $member->setId($value->id);
                $member->setUser_id($value->user_id);
                $member->setField_value(unserialize($value->field_value));
                $member->setSocial_links(unserialize($value->social_links));
                $member->setMember_type($value->member_type);
                $rs[] = $member;
            }
        }
        return $rs;
    }

    public function getMember($id) {
        global $wpdb;
        $member = new inFundingMember();
        $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'inf_donors WHERE id=%d', $id));
        if ($row) {
            $member->setId($row->id);
            $member->setUser_id($row->user_id);
            $member->setField_value(unserialize($row->field_value));
            $member->setSocial_links(unserialize($row->social_links));
            $member->setMember_type($row->member_type);
        }
        return $member;
    }

    /**
     * Function to delete single member
     * @global type $wpdb
     * @param type $id member id
     * @return string serialize data of result
     */
    public function deleteMember($id) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $check = $wpdb->get_results(
                $wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'inf_orders WHERE member_id = %d', $id)
        );
        $msg = '';
        if (!empty($check)) {
            $msg = sprintf(__('Can\'t remove Donor with id: <strong>%s</strong>. Because:', 'inwavethemes'), $id);
            if (!empty($check)) {
                $msg .= '<br/> - ' . __('It used in some orders', 'inwavethemes');
            }
        }
        if ($msg) {
            $return['msg'] = $msg;
        } else {
            $del = $wpdb->delete($wpdb->prefix . 'inf_donors', array('id' => $id));
            if ($del) {
                $return['success'] = true;
                $return['msg'] = __('Donor has been delete', 'inwavethemes');
            } else {
                $return['msg'] = $wpdb->last_error;
            }
        }
        return serialize($return);
    }

    /**
     * Function to delete multiple sponsor
     * @global type $wpdb
     * @param type $ids list ids to delete
     * @return string delete message result
     */
    public function deleteMembers($ids) {
        global $wpdb;
        $result = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'inf_orders WHERE member_id IN(' . implode(',', wp_parse_id_list($ids)) . ') GROUP BY member_id');
        $ar = array();
        foreach ($result as $value) {
            $ar[] = $value->member_id;
        }
        foreach ($ids as $key => $id) {
            if (in_array($id, $ar)) {
                unset($ids[$key]);
            }
        }
        $msg = array();
        if (!empty($ar)) {
            $msg['error'] = sprintf(__('Can\'t delete donor with id: <strong>%s</strong> because it used by some orders.', 'inwavethemes'), implode(', ', $ar)) . '<br/>';
        }
        if (!empty($ids)) {
            $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'inf_donors WHERE id IN(' . implode(',', wp_parse_id_list($ids)) . ')');
            $msg['success'] = sprintf(__('Success delete donors with id: <strong>%s</strong>', 'inwavethemes'), implode(', ', $ids));
        }
        return $msg;
    }

    public function getCountMember($member_type = 'donor') {
        global $wpdb;
        return $wpdb->get_var($wpdb->prepare('SELECT COUNT(id) FROM ' . $wpdb->prefix . 'inf_donors WHERE member_type=%s',$member_type));
    }

    public function getMemberByUser($curent_user, $member_type = 'donor') {
        global $wpdb;
        $member = new inFundingMember();
        $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'inf_donors WHERE user_id=%d AND member_type=%s', $curent_user,$member_type));
        if ($row) {
            $member->setId($row->id);
            $member->setUser_id($row->user_id);
            $member->setField_value(unserialize($row->field_value));
            $member->setSocial_links(unserialize($row->social_links));
            $member->setMember_type($row->member_type);
        }
        return $member;
    }

    public function getMemberRowData($members, $single = false) {
        global $inf_settings;
        $form_fields = $inf_settings['register_form_fields'];
		
        $field_to_show = array();
        if (is_numeric($members)) {
            $field_to_show = $form_fields;
            $members = array($this->getMember($members));
        } else {
            foreach ($form_fields as $field_show) {
                if ($field_show['show_on_list'] == 1) {
                    $field_to_show[] = $field_show;
                }
            }
        }
        $member_list = array();
        foreach ($members as $member) {
            $member_data = array();
            $member_data['id'] = $member->getId();
            $member_field_name = array();
            $member_field_value = array();
            $member_field = $member->getField_value();
            if ($member_field) {
                foreach ($member_field as $info) {
                    $member_field_name[] = $info['name'];
                    $member_field_value[$info['name']] = $info['value'];
                }
            }
            $member_fields = array('names' => $member_field_name, 'values' => $member_field_value);
            foreach ($field_to_show as $field_f) {
                if (in_array($field_f['name'], $member_fields['names'])) {
                    $val = $member_fields['values'][$field_f['name']];
                    if ($field_f['type'] == 'select') {
                        if ($single) {
                            $val = $member_fields['values'][$field_f['name']]['value'];
                        } else {
                            $val = $member_fields['values'][$field_f['name']]['text'];
                        }
                    }
                    if ($field_f['type'] == 'checkbox') {
                        if ($single) {
                            $val = $member_fields['values'][$field_f['name']];
                        } else {
                            $val = (isset($member_fields['values'][$field_f['name']]) && $member_fields['values'][$field_f['name']]) ? __('Yes', 'inwavethenes') : __('No', 'inwavethemes');
                        }
                    }
                } else {
                    $val = '';
                    if (($field_f['type'] == 'select' || $field_f['type'] == 'checkbox') && !$single) {
                        $val = __('Not set', 'inwavethemes');
                    }
                }
                $member_data[$field_f['name']] = $val;
            }
            $member_list[] = $member_data;
        }
        return array('fields' => $field_to_show, 'data' => $member_list);
    }

}
