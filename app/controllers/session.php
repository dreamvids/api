<?php

/**
 * Created by PhpStorm.
 * User: peter_000
 * Date: 31/08/2016
 * Time: 11:44
 */
class SessionCtrl implements ControllerInterface {
    public static function fetch(): Response {
        return new Response(Response::HTTP_405_METHOD_NOT_ALLOWED);
    }

    public static function create(): Response {
        $user = null;
        $validation = new Validator([
            Validator::RULE_ALL => [
                Validator::PARAM_REQUIRED => true,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_REQUIRED => 'All fields must be filled'
                ]
            ],
            'username' => [
                Validator::PARAM_CUSTOM => function(string $value) use(&$user): bool {
                    if (Persist::exists('User', 'username', $value)) {
                        $user = Persist::readBy('User', 'username', $value);
                        return true;
                    }
                    return false;
                },
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_CUSTOM => 'This user doesn\'t exists'
                ]
            ],
            'password' => [
                Validator::PARAM_CUSTOM => function(string $value) use(&$user): bool  {
                    if ($user == null) return false;
                    return PasswordManager::checkPass($value, $user->getPassword());
                },
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_CUSTOM => 'Password doesn\'t match'
                ]
            ]
        ]);
        $rep = new Response();
        if ($validation->validate()) {
            $sessid = sha1(uniqid());
            $expiration = Utils::time() + (365 * 86400);
            $session = new \Bean\Session(
                0,
                $sessid,
                $expiration
            );
            $session->setUserId($user->getId());
            $id = Persist::create($session);
            $session->setId($id);
            $rep->setCode(Response::HTTP_201_CREATED);
            $rep->addData('session', $session);
            return $rep;
        }
        else {
            $rep->addError('errors', $validation->getErrors());
        }

        $rep->setCode(Response::HTTP_400_BAD_REQUEST);
        return $rep;
    }

    public static function exists(): Response {
        return new Response(Response::HTTP_405_METHOD_NOT_ALLOWED);
    }

    public static function read(): Response {
        return new Response(Response::HTTP_405_METHOD_NOT_ALLOWED);
    }

    public static function update(): Response {
        return new Response(Response::HTTP_405_METHOD_NOT_ALLOWED);
    }

    public static function delete(): Response {
        $rep = new Response();
        if (preg_match("#^[0-9a-f]{40}$#", Request::get()->getArg(1))) {
            if (Persist::exists('Session', 'session_id', Request::get()->getArg(1))) {
                Persist::deleteBy('Session', 'session_id', Request::get()->getArg(1));
                $rep->addData('success', 'Session successfully terminated');
            }
            else {
                $rep->setCode(Response::HTTP_404_NOT_FOUND);
                $rep->addError('error', 'Unknown Session ID');
            }
        }
        else {
            if (Persist::exists('User', 'id', Request::get()->getArg(1))) {
                Persist::deleteBy('Session', 'user_id', Request::get()->getArg(1));
                $rep->addData('success', 'Sessions successfully terminated');
            }
            else {
                $rep->setCode(Response::HTTP_404_NOT_FOUND);
                $rep->addError('error', 'Unknown User ID');
            }
        }
        return $rep;
    }
}