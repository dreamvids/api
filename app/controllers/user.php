<?php
class UserCtrl implements ControllerInterface {
    public static function create() {
        if (isset($_POST['username'], $_POST['email'], $_POST['pass'], $_POST['pass_confirm'])) {
            if (!User::usernameExists($_POST['username'])) {
                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    if (!User::emailExists($_POST['email'])) {
                        if ($_POST['pass'] == $_POST['pass_confirm']) {
                            User::insertIntoDb([$_POST['username'], $_POST['pass'], $_POST['email'], Utils::time(), $_SERVER['REMOTE_ADDR'], $_SERVER['REMOTE_ADDR'], Rank::getBy('name', 'Member')->id]);
                            Data::get()->add('success', true);
                        }
                        else {
                            $err = 'Passwords must match';
                        }
                    }
                    else {
                        $err = 'E-Mail address already registered';
                    }
                }
                else {
                    $err = 'Invalid E-Mail address';
                }
            }
            else {
                $err = 'Username already taken';
            }
        }
        else {
            $err = 'All fields must be filled';
        }

        if (isset($err)) {
            Data::get()->add('success', false);
            Data::get()->add('error', $err);
        }
    }

    public static function fetch() {
        // TODO: Implement fetch() method.
    }

    public static function exists() {
        // TODO: Implement exists() method.
    }

    public static function read() {
        // TODO: Implement read() method.
    }

    public static function update() {
        // TODO: Implement update() method.
    }

    public static function delete() {
        // TODO: Implement delete() method.
    }
}