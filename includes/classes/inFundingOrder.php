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
 * Description of inFundingOrder
 *
 * @developer duongca
 */
class inFundingOrder {

    private $id;
    private $member;
    private $campaign;
    private $price;
    private $currentcy;
    private $time_created;
    private $time_paymented;
    private $note;
    private $payment_method;
    private $status;

    function getPayment_method() {
        return $this->payment_method;
    }

    function setPayment_method($payment_method) {
        $this->payment_method = $payment_method;
    }

    function getCurrentcy() {
        return $this->currentcy;
    }

    function getTime_created() {
        return $this->time_created;
    }

    function getTime_paymented() {
        return $this->time_paymented;
    }

    function setTime_created($time_created) {
        $this->time_created = $time_created;
    }

    function setTime_paymented($time_paymented) {
        $this->time_paymented = $time_paymented;
    }

    function setCurrentcy($currentcy) {
        $this->currentcy = $currentcy;
    }

    function getId() {
        return $this->id;
    }

    function getMember() {
        return $this->member;
    }

    function getSum_price() {
        return $this->price;
    }

    function getNote() {
        return $this->note;
    }

    function setSum_price($sum_price) {
        $this->price = $sum_price;
    }

    function setNote($note) {
        $this->note = $note;
    }

    function getStatus() {
        return $this->status;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setMember($member) {
        $this->member = $member;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function getCampaign() {
        return $this->campaign;
    }

    function setCampaign($campaign) {
        $this->campaign = $campaign;
    }

    function getOrder($id) {
        global $wpdb;
        $order = new inFundingOrder();
        $rows = $wpdb->get_results($wpdb->prepare('SELECT o.*, m.id AS mid, m.user_id AS muid, m.field_value AS mfield_value FROM ' . $wpdb->prefix . 'inf_orders AS o left JOIN ' . $wpdb->prefix . 'inf_donors as m ON o.member_id = m.id  WHERE o.id=%d', $id));
        if (!empty($rows)) {
            foreach ($rows as $value) {
                $member = new inFundingMember();

                $member->setId($value->mid);
                $member->setUser_id($value->muid);
                $member->setField_value(unserialize($value->mfield_value));

                $order->setId($value->id);
                $order->setCampaign($value->campaign_id);
                $order->setStatus($value->status);
                $order->setCurrentcy($value->currentcy);
                $order->setTime_created($value->time_created);
                $order->setTime_paymented($value->time_paymented);
                $order->setMember($member);
                $order->setPayment_method($value->payment_method);
                $order->setNote($value->note);
                $order->setSum_price($value->price);
            }
        }
        return $order;
    }

    function getOrders($start, $limit = 20, $filter = '', $orderby = '') {
        global $wpdb;
        $rs = array();
        $rows = $wpdb->get_results('SELECT o.*, m.id AS mid, m.user_id AS muid, m.field_value AS mfield_value FROM ' . $wpdb->prefix . 'inf_orders AS o LEFT JOIN ' . $wpdb->prefix . 'inf_donors as m ON o.member_id = m.id INNER JOIN ' . $wpdb->prefix . 'posts AS p ON o.campaign_id = p.ID' . ($filter ? ' WHERE ' . $filter : '') . ($orderby ? ' ' . $orderby : '') . ' LIMIT ' . $start . ',' . $limit);

        if (!empty($rows)) {
            foreach ($rows as $value) {
                $order = new inFundingOrder();
                $member = new inFundingMember();

                $member->setId($value->mid);
                $member->setUser_id($value->muid);
                $member->setField_value(unserialize($value->mfield_value));

                $order->setId($value->id);
                $order->setCampaign($value->campaign_id);
                $order->setStatus($value->status);
                $order->setNote($value->note);
                $order->setSum_price($value->price);
                $order->setCurrentcy($value->currentcy);
                $order->setTime_created($value->time_created);
                $order->setTime_paymented($value->time_paymented);
                $order->setPayment_method($value->payment_method);
                $order->setMember($member);
                $rs[] = $order;
            }
        }
        return $rs;
    }

    function getOrderCode($id) {
        $id_length = strlen($id);
        $id_mizz = 10 - $id_length;
        $order_prefix = '#';
        for ($i = 0; $i < $id_mizz; $i++) {
            $order_prefix.='0';
        }
        return $order_prefix . $id;
    }

    public function addOrder($order) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $data = get_object_vars($order);
        $data['member_id'] = $data['member'];
        $data['campaign_id'] = $data['campaign'];
        unset($data['member']);
        unset($data['campaign']);
        $ins = $wpdb->insert($wpdb->prefix . "inf_orders", $data);
        if ($ins) {
            $return['success'] = TRUE;
            $return['msg'] = __('Insert success', 'inwavethemes');
            $return['data'] = $wpdb->insert_id;
        } else {
            $return['msg'] = $wpdb->last_error;
        }
        return serialize($return);
    }

    public function editOrder($order) {
        global $wpdb;
        $return = array('success' => false, 'msg' => null, 'data' => null);
        $data = get_object_vars($order);
        unset($data['id']);
        $data['member_id'] = $data['member'];
        $data['campaign_id'] = $data['campaign'];
        unset($data['member']);
        unset($data['campaign']);
        foreach ($data as $k => $v) {
            if ($v === NULL) {
                unset($data[$k]);
            }
        }
        $update = $wpdb->update($wpdb->prefix . "inf_orders", $data, array('id' => $order->getId()));
        if ($update || $update == 0) {
            $return['success'] = TRUE;
            $return['msg'] = __('Update success', 'inwavethemes');
        } else {
            $return['msg'] = $wpdb->last_error;
        }
        return serialize($return);
    }

    public function getLink($id, $text = '') {
        return admin_url('edit.php?post_type=infunding&page=payment/view&id=' . $id) . ($text ? ('|' . $text) : '');
    }

    public function killOrderExpired($timeToKill) {
        global $wpdb;
        $rows = $wpdb->get_results($wpdb->prepare('SELECT id FROM ' . $wpdb->prefix . 'inf_orders WHERE status=1 AND time_created < %d', $timeToKill));
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $this->killOrder($row->id);
            }
        }
        return count($rows);
    }

    public function killOrder($id) {
        global $wpdb;
        $wpdb->query($wpdb->prepare('UPDATE ' . $wpdb->prefix . 'inf_orders SET status=3 WHERE id = %d', $id));
    }

    /**
     * Function to delete single sponsor
     * @global type $wpdb
     * @param type $id sponsor id
     * @return string serialize data of result
     */
    public function deleteOrder($id) {
        global $wpdb;
        $order = $this->getOrder($id);
        if ($order->getStatus() == '2') {
            $current = htmlspecialchars(get_post_meta($order->getCampaign(), 'inf_current', true));
            update_post_meta($order->getCampaign(), 'inf_current', $current - $order->getSum_price());
        }
        $wpdb->delete($wpdb->prefix . 'inf_orders', array('id' => $id));
    }

    /**
     * Function to delete multiple sponsor
     * @global type $wpdb
     * @param type $ids list ids to delete
     * @return string delete message result
     */
    public function deleteOrders($ids) {
        global $wpdb;
        if (!empty($ids)) {
            foreach ($ids as $id) {
                $order = $this->getOrder($id);
                if ($order->getStatus() == '2') {
                    $current = htmlspecialchars(get_post_meta($order->getCampaign(), 'inf_current', true));
                    update_post_meta($order->getCampaign(), 'inf_current', $current - $order->getSum_price());
                }
            }
            $wpdb->query('DELETE FROM ' . $wpdb->prefix . 'inf_orders WHERE id IN(' . implode(',', wp_parse_id_list($ids)) . ')');
        }
    }

    public function getCountOrder($filter = '') {
        global $wpdb;
        $count = $wpdb->get_var('SELECT count(o.id) FROM ' . $wpdb->prefix . 'inf_orders AS o LEFT JOIN ' . $wpdb->prefix . 'inf_donors as m ON o.member_id = m.id INNER JOIN ' . $wpdb->prefix . 'posts AS p ON o.campaign_id = p.ID' . ($filter ? ' WHERE ' . $filter : ''));
        return $count;
    }

    public function getMemberOrder($member_id) {
        global $wpdb;
        $rs = array();
        $rows = $wpdb->get_results($wpdb->prepare('SELECT o.*, m.id AS mid, m.user_id AS muid, m.field_value AS mfield_value FROM ' . $wpdb->prefix . 'inf_orders AS o INNER JOIN ' . $wpdb->prefix . 'inf_donors as m ON o.member_id = m.id WHERE o.member_id=%d', $member_id));
        if (!empty($rows)) {
            foreach ($rows as $value) {
                $order = new inFundingOrder();
                $member = new inFundingMember();

                $member->setId($value->mid);
                $member->setUser_id($value->muid);
                $member->setField_value(unserialize($value->mfield_value));

                $order->setId($value->id);
                $order->setCampaign($value->campaign_id);
                $order->setStatus($value->status);
                $order->setNote($value->note);
                $order->setSum_price($value->price);
                $order->setCurrentcy($value->currentcy);
                $order->setTime_created($value->time_created);
                $order->setTime_paymented($value->time_paymented);
                $order->setPayment_method($value->payment_method);
                $order->setMember($member);
                $rs[] = $order;
            }
        }
        return $rs;
    }

    public function getOrderByCampaign($campaign_id) {
        global $wpdb;
        $rs = array();
        $rows = $wpdb->get_results($wpdb->prepare('SELECT o.*, m.id AS mid, m.user_id AS muid, m.field_value AS mfield_value FROM ' . $wpdb->prefix . 'inf_orders AS o LEFT JOIN ' . $wpdb->prefix . 'inf_donors as m ON o.member_id = m.id WHERE o.campaign_id=%d and o.status=2 ORDER BY o.time_paymented DESC limit 0,5', $campaign_id));
        if (!empty($rows)) {
            foreach ($rows as $value) {
                $order = new inFundingOrder();
                $member = new inFundingMember();

                $member->setId($value->mid);
                $member->setUser_id($value->muid);
                $member->setField_value(unserialize($value->mfield_value));

                $order->setId($value->id);
                $order->setCampaign($value->campaign_id);
                $order->setStatus($value->status);
                $order->setNote($value->note);
                $order->setSum_price($value->price);
                $order->setCurrentcy($value->currentcy);
                $order->setTime_created($value->time_created);
                $order->setTime_paymented($value->time_paymented);
                $order->setPayment_method($value->payment_method);
                $order->setMember($member);
                $rs[] = $order;
            }
        }
        return $rs;
    }

}
