<?php

/**
 * Created by PhpStorm.
 * User: peter_000
 * Date: 26/08/2016
 * Time: 15:34
 */
class UserCtrl implements ControllerInterface {
    public static function fetch(): Response {
        $rep = new Response();
        $rep->addData('users', Persist::fetchAll('User'));
        return $rep;
    }

    public static function create(): Response {
        $validation = new Validator([
            Validator::RULE_ALL => [
                Validator::PARAM_REQUIRED => true,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_REQUIRED => 'All fields must be filled'
                ]
            ],
            'email' => [
                Validator::PARAM_TYPE => Validator::TYPE_EMAIL,
                Validator::PARAM_CUSTOM => function(string $value): bool {
                    return !Persist::exists('User', 'email', $value);
                },
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_TYPE => 'Invalid E-Mail address',
                    Validator::PARAM_CUSTOM => 'E-Mail address already registered'
                ]
            ],
            'username' => [
                Validator::PARAM_CUSTOM => function(string $value): bool {
                    return !Persist::exists('User', 'username', $value);
                },
                Validator::PARAM_MAX_LENGTH => 40,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_CUSTOM => 'Username already taken',
                    Validator::PARAM_MAX_LENGTH => 'Username too long (max 40 chars)'
                ]
            ],
            'password' => [
                Validator::PARAM_MIN_LENGTH => 8,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_MIN_LENGTH => 'Password too short (min 8 chars)'
                ]
            ]
        ]);
        $rep = new Response();
        if (isset($_POST['username'], $_POST['password'], $_POST['email'], $_POST['ip'])) {
            if ($validation->validate()) {
                $user = new \Bean\User(
                    0,
                    $_POST['username'],
                    PasswordManager::generateHash($_POST['password']),
                    $_POST['email'],
                    Utils::time(),
                    $_POST['ip'],
                    $_POST['ip'],
                    Persist::readBy('Rank', 'name', 'Member')->getId()
                );
                $id = Persist::create($user);
                $user->setId($id);
                $rep->setCode(201);
                $rep->addData('user', $user);
                return $rep;
            }
            else {
                $rep->addError('errors', $validation->getErrors());
            }
        }
        else {
            $rep->addError('error', 'Some fields are missing');
        }

        $rep->setCode(400);
        return $rep;
    }

    public static function exists(): Response {
        if (Persist::exists('User', 'id', Request::get()->getArg(1))) {
            return new Response(200);
        }

        return new Response(404);
    }

    public static function read(): Response {
        if (Persist::exists('User', 'id', Request::get()->getArg(1))) {
            $rep = new Response();
            $rep->addData('user', Persist::read('User', Request::get()->getArg(1)));
            return $rep;
        }

        return new Response(404);
    }

    public static function update(): Response {
        // TODO: Implement update() method.
    }

    public static function delete(): Response {
        return new Response(405);
    }
}