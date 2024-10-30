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
 * Description of Inwave_Session
 *
 * @author duongca
 */
class Inwave_Session {


    public function __construct() {
        if (!session_id() && !headers_sent()) {
            session_start();
        }
    }


    /**
     * Get a session variable.
     *
     * @param string $key
     * @param  mixed $default used if the session variable isn't set
     * @return array|string value of session variable
     */
    public function get($key, $default = null) {
        $key = sanitize_key($key);
        return isset($_SESSION[$key]) ? maybe_unserialize($_SESSION[$key]) : $default;
    }

    /**
     * Set a session variable.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value) {
        if ($value !== $this->get($key)) {
            $_SESSION[sanitize_key($key)] = maybe_serialize($value);
        }
    }

    public function clearSession($key) {
        $key = sanitize_key($key);
        unset($_SESSION[sanitize_key($key)]);
    }

}
