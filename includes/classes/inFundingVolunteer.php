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
class inFundingVolunteer {

    private $id;
    private $campaign_id;
    private $member_id;
    private $date_start;
    private $date_end;
    private $date_register;
    private $message;
    private $status;

    function getId() {
        return $this->id;
    }

    function getCampaign_id() {
        return $this->campaign_id;
    }

    function getMember_id() {
        return $this->member_id;
    }

    function getDate_start() {
        return $this->date_start;
    }

    function getDate_end() {
        return $this->date_end;
    }

    function getDate_register() {
        return $this->date_register;
    }

    function getMessage() {
        return $this->message;
    }

    function getStatus() {
        return $this->status;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCampaign_id($campaign_id) {
        $this->campaign_id = $campaign_id;
    }

    function setMember_id($member_id) {
        $this->member_id = $member_id;
    }

    function setDate_start($date_start) {
        $this->date_start = $date_start;
    }

    function setDate_end($date_end) {
        $this->date_end = $date_end;
    }

    function setDate_register($date_register) {
        $this->date_register = $date_register;
    }

    function setMessage($message) {
        $this->message = $message;
    }

    function setStatus($status) {
        $this->status = $status;
    }

        
    public function addVolunteer($volunteer) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $data = get_object_vars($volunteer);
        $ins = $wpdb->insert($wpdb->prefix . "inf_volunteer", $data);
        if ($ins) {
            $return['success'] = TRUE;
            $return['msg'] = 'Insert success';
            $return['data'] = $wpdb->insert_id;
        } else {
            $return['msg'] = $wpdb->last_error;
        }
        return serialize($return);
    }

    public function editVolunteer($volunteer) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $data = get_object_vars($volunteer);
        unset($data['id']);
        foreach ($data as $k => $v) {
            if ($v === NULL) {
                unset($data[$k]);
            }
        }
        $update = $wpdb->update($wpdb->prefix . "inf_volunteer", $data, array('id' => $volunteer->getId()));
        if ($update || $update == 0) {
            $return['success'] = TRUE;
            $return['msg'] = 'Update success';
        } else {
            $return['msg'] = $wpdb->last_error;
        }
        return serialize($return);
    }

    public function getVolunteerByUser($user_id) {
        global $wpdb;
        $rs = array();
        $rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'inf_volunteer WHERE member_id=%d order by id DESC', $user_id));
        if (!empty($rows)) {
            foreach ($rows as $value) {
                $volunteer = new inFundingVolunteer();
                $volunteer->setId($value->id);
                $volunteer->setCampaign_id($value->campaign_id);
                $volunteer->setDate_end($value->date_end);
                $volunteer->setDate_start($value->date_start);
                $volunteer->setDate_register($value->date_register);
                $volunteer->setMessage($value->message);
                $volunteer->setStatus($value->status);
                $volunteer->setMember_id($value->member_id);
                $rs[] = $volunteer;
            }
        }
        return $rs;
    }

    public function acceptVolunter($id) {
        $v = $this->getVolunter($id);
        $v->setStatus('1');
        return $this->editVolunteer($v);
    }

    public function getVolunter($id) {
        global $wpdb;
        $volunter = new inFundingVolunteer();
        $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'inf_volunteer WHERE id=%d', $id));
        if ($row) {
            $volunter->setId($row->id);
            $volunter->setCampaign_id($row->campaign_id);
            $volunter->setDate_end($row->date_end);
            $volunter->setDate_register($row->date_register);
            $volunter->setDate_start($row->date_start);
            $volunter->setMember_id($row->member_id);
            $volunter->setMessage($row->message);
            $volunter->setStatus($row->status);
        }
        return $volunter;
    }

}
