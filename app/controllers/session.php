<?php
class SessionCtrl implements ControllerInterface {
    public static function create() {
        $loggedUser = null;

        $validation = new Validator([
            Validator::RULE_ALL => [
                Validator::PARAM_REQUIRED => true,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_REQUIRED => 'All fields must be filled'
                ]
            ],
            'username' => [
                Validator::PARAM_CUSTOM => function($value){ return User::usernameExists($value);},
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_CUSTOM => "This user does not exists"
                ]
            ],
            'pass' => [
                Validator::PARAM_CUSTOM => function($value, $data) use (&$loggedUser){
                    if(User::usernameExists($data['username'])){
                        $user = User::getBy('username', $data['username']);
                        $result = PasswordManager::checkPass($value, $user->password);
                        if($result){
                            $loggedUser = $user;
                        }
                        return $result;
                    }
                    return false;
                },
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_CUSTOM => "Password doesn't match"
                ]
            ],
        ], $_POST);

        if($validation->validate()){
            $sessid = md5(uniqid());
            $expire = Utils::time() + 365 * 86400;
            Session::insertIntoDb([$sessid, $expire, $loggedUser->id]);
            Response::get()->setSuccess(true);
            Response::get()->addData('sessid', $sessid);
            Response::get()->addData('user', $loggedUser);
        }else{
            Response::get()->addError('validation', $validation->getErrors());
            Response::get()->setSuccess(false);
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
        if (Session::exists('session_id', Request::get()->getArg(1))) {
            $sess = Session::getBy('session_id', Request::get()->getArg(1))->delete();
            Response::get()->setSuccess(true);
        }
        else {
            Response::get()->setSuccess(false);
            Response::get()->addError('notfound', 'Session ID doesn\'t exists');
        }
    }
}