<?php
class UserCtrl implements ControllerInterface {

    use ExistsTrait;

    public static function create() {
        $validation = new Validator([
            Validator::RULE_ALL => [
                Validator::PARAM_REQUIRED => true,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_REQUIRED  => 'All fields must be filled'
                ]
            ],
            'username' => [
                Validator::PARAM_CUSTOM => function($value){ return !User::usernameExists($value); },
                Validator::PARAM_MAX_LENGTH => 40,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_CUSTOM => 'Username already taken',
                    Validator::PARAM_CUSTOM => 'Username too long (max 40 chars)'
                ]
            ],
            'email' => [
                Validator::PARAM_CUSTOM => function($value){ return !User::emailExists($value); },
                Validator::PARAM_TYPE => Validator::TYPE_EMAIL,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_CUSTOM => 'E-mail address already registered',
                    Validator::PARAM_TYPE => 'Invalid E-Mail address'
                ]
            ],
            'pass' => [
                Validator::PARAM_MIN_LENGTH => 8,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_MIN_LENGTH => 'Password too short (min 8 chars)'
                ]
            ],
            'pass_confirm' => [
                Validator::PARAM_SAME => 'pass',
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_SAME => 'Passwords must match'
                ]
            ]
        ]);

        if($validation->validate()){
            $id = User::insertIntoDb([$_POST['username'], PasswordManager::generateHash($_POST['pass']), $_POST['email'], Utils::time(), $_SERVER['REMOTE_ADDR'], $_SERVER['REMOTE_ADDR'], Rank::getBy('name', 'Member')->id]);
            Channel::insertIntoDb([$_POST['username'], 'Chaine de '.$_POST['username'], '', '', false, $id]);
        }else{
            Response::get()->addError('validation', $validation->getErrors());
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
        if(User::exists('id', Request::get()->getArg(1))){
            $user = User::getBy('id', Request::get()->getArg(1));
            $user->loadAssociations(['administred_channels', 'owned_channels.admins', 'owned_channels.owner']);
            Response::get()->addData('user', $user);
            //Response::get()->addData('videos', Video::getAllBy('id', [1,2])); //Test
        }else{
            HTTPError::NotFound();
        }
    }

    public static function update() {
        // TODO: Implement update() method.
    }

    public static function delete() {
        // TODO: Implement delete() method.
    }
}