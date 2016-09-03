<?php
/**
 * Created by PhpStorm.
 * User: peter_000
 * Date: 31/08/2016
 * Time: 12:28
 */

namespace Model;


class Session implements \Modelable {
    private static $session = null;

    public static function authenticate() {
        if (isset($_SERVER['HTTP_X_SESSION_ID'])) {
            if (\Persist::exists('Session', 'session_id', $_SERVER['HTTP_X_SESSION_ID'])) {
                return \Persist::readBy('Session', 'session_id', $_SERVER['HTTP_X_SESSION_ID']);
            }
        }

        return null;
    }

    public static function getSession() {
        if (self::$session == null) {
            self::$session = self::authenticate();
        }

        return self::$session;
    }
}