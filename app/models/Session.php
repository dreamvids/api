<?php
/**
 * Created by PhpStorm.
 * User: peter_000
 * Date: 31/08/2016
 * Time: 12:28
 */

namespace Model;


class Session implements \Modelable {
    public static function authenticate() {
        if (isset($_SERVER['HTTP_X_SESSION_ID'])) {
            if (\Persist::exists('Session', 'session_id', $_SERVER['HTTP_X_SESSION_ID'])) {
                return \Persist::readBy('Session', 'session_id', $_SERVER['HTTP_X_SESSION_ID']);
            }
        }

        return null;
    }
}