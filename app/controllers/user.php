<?php
class UserCtrl implements ControllerInterface {
    public static function create() {
        $validation = new Validator([
            '_all' => [
                'required' => true,
                '_messages' => [
                    'required' => 'All fields must be filled'
                ]
            ],
            'username' => [
                'custom' => function($value){ return !User::usernameExists($value); },
                '_messages' => [
                    'custom' => 'Username already taken'
                ]
            ],
            'email' => [
                'custom' => function($value){ return !User::emailExists($value); },
                'type' => Validator::TYPE_EMAIL,
                '_messages' => [
                    'custom' => 'E-mail address already registered',
                    'type' => 'Invalid E-Mail address'
                ]
            ],
            'pass' => [],
            'pass_confirm' => [
                'same' => 'pass',
                '_messages' => [
                    'same' => 'Passwords must match'
                ]
            ]
        ], $_POST);

        if($validation->validate()){
            User::insertIntoDb([$_POST['username'], password_hash($_POST['pass'], PASSWORD_BCRYPT), $_POST['email'], Utils::time(), $_SERVER['REMOTE_ADDR'], $_SERVER['REMOTE_ADDR'], Rank::getBy('name', 'Member')->id]);
        }else{
            Response::get()->setErrors($validation->getErrors());
            Response::get()->setSuccess(false);

            HTTPError::BadRequest();
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