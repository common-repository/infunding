<?php

/*
 * @package Inwave Funding
 * @version 1.0.0
 * @created May 26, 2016
 * @author Inwavethemes
 * @email inwavethemes@gmail.com
 * @website http://inwavethemes.com
 * @support Ticket https://inwave.ticksy.com/
 * @copyright Copyright (c) 2015 Inwavethemes. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

/**
 * Description of inFundingLog
 *
 * @developer duongca
 */
class inFundingLog {

    private $id;
    private $log_type;
    private $timestamp;
    private $message;
    private $scope;
    private $link;

    function __construct($id = null, $log_type = null, $timestamp = null, $message = null, $link = null, $scope = null) {
        $this->id = $id;
        $this->log_type = $log_type;
        $this->timestamp = $timestamp;
        $this->message = $message;
        $this->link = $link;
        $this->scope = $scope;
    }

    function getId() {
        return $this->id;
    }

    function getTimestamp() {
        return $this->timestamp;
    }

    function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }

    function getLog_type() {
        return $this->log_type;
    }

    function getMessage() {
        return $this->message;
    }

    function getLink() {
        return $this->link;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setLog_type($log_type) {
        $this->log_type = $log_type;
    }

    function setMessage($message) {
        $this->message = $message;
    }

    function setLink($link) {
        $this->link = $link;
    }
    
    function getScope() {
        return $this->scope;
    }

    function setScope($scope) {
        $this->scope = $scope;
    }

    
    function addLog($log) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $data = get_object_vars($log);
        $ins = $wpdb->insert($wpdb->prefix . "inf_logs", $data);
        if ($ins) {
            $return['success'] = TRUE;
            $return['msg'] = 'Insert success';
            $return['data'] = $wpdb->insert_id;
        } else {
            $return['msg'] = $wpdb->last_error;
        }
        return serialize($return);
    }

    public function getAllLogs() {
        global $wpdb;
        $rs = array();
        $rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'inf_logs');
        if (!empty($rows)) {
            foreach ($rows as $value) {
                $rs[] = new inFundingLog($value->id, $value->log_type, $value->timestamp, $value->message, $value->link, $value->scope);
            }
        }
        return $rs;
    }

    public function getLogsPerPage($start, $limit) {
        global $wpdb;
        $rs = array();
        $rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'inf_logs ORDER BY timestamp DESC LIMIT %d,%d', $start, $limit));
        if (!empty($rows)) {
            foreach ($rows as $value) {
                $rs[] = new inFundingLog($value->id, $value->log_type, $value->timestamp, $value->message, $value->link, $value->scope);
            }
        }
        return $rs;
    }

    public function deleteLog($id) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $del = $wpdb->delete($wpdb->prefix . 'inf_logs', array('id' => $id));
        if ($del) {
            $return['success'] = true;
            $return['msg'] = __('Log has been delete', 'inwavethemes');
        } else {
            $return['msg'] = $wpdb->last_error;
        }
        return serialize($return);
    }

    public function deleteLogs($ids) {
        global $wpdb;
        $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'inf_logs WHERE id IN(' . implode(',', wp_parse_id_list($ids)) . ')');
        $msg['success'] = sprintf(__('Success delete logs with id: <strong>%s</strong>', 'inwavethemes'), implode(', ', $ids));
        return $msg;
    }

    public function emptyLog() {
        global $wpdb;
        $wpdb->query('TRUNCATE ' . $wpdb->prefix . 'inf_logs');
        $msg['success'] = __('All log has been delete','inwavethemes');
        return $msg;
    }

    public function getLog($id) {
        global $wpdb;
        $log = new inFundingLog();
        $rows = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'inf_logs WHERE id=%d', $id));
        if (!empty($rows)) {
            foreach ($rows as $value) {
                $log->setId($value->id);
                $log->setLog_type($value->log_type);
                $log->setTimestamp($value->timestamp);
                $log->setMessage($value->message);
                $log->setLink($value->link);
                $log->setScope($value->scope);
            }
        }
        return $log;
    }

}
