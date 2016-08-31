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
                Validator::PARAM_CUSTOM => function (string $value): bool {
                    return !Persist::exists('User', 'email', $value);
                },
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_TYPE => 'Invalid E-Mail address',
                    Validator::PARAM_CUSTOM => 'E-Mail address already registered'
                ]
            ],
            'username' => [
                Validator::PARAM_CUSTOM => function (string $value): bool {
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
            ],
            'ip' => []
        ]);
        $rep = new Response();
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
        } else {
            $rep->addError('errors', $validation->getErrors());
        }

        $rep->setCode(400);
        return $rep;
    }

    public static function exists(): Response {
        if (Persist::exists('User', 'id', Request::get()->getArg(1))) {
            return new Response(Response::HTTP_200_OK);
        }

        return new Response(Response::HTTP_404_NOT_FOUND);
    }

    public static function read(): Response {
        if (Persist::exists('User', 'id', Request::get()->getArg(1))) {
            $rep = new Response();
            $rep->addData('user', Persist::read('User', Request::get()->getArg(1)));
            return $rep;
        }

        return new Response(Response::HTTP_404_NOT_FOUND);
    }

    public static function update(): Response {
        if (Persist::exists('User', 'id', Request::get()->getArg(1))) {
            $user = Persist::read('User', Request::get()->getArg(1));
            $validation = new Validator([
                'email' => [
                    Validator::PARAM_REQUIRED => true,
                    Validator::PARAM_TYPE => Validator::TYPE_EMAIL,
                    Validator::PARAM_CUSTOM => function(string $value): bool {
                        $user = Persist::read('User', Request::get()->getArg(1));
                        return ($user->getEmail() == $value || !Persist::exists('User', 'email', $value));
                    },
                    Validator::PARAM_MESSAGES => [
                        Validator::PARAM_REQUIRED => 'E-Mail address can\'t be empty',
                        Validator::PARAM_TYPE => 'Invalid E-Mail address',
                        Validator::PARAM_CUSTOM => 'E-Mail address already registered'
                    ]
                ],
                'username' => [
                    Validator::PARAM_REQUIRED => true,
                    Validator::PARAM_CUSTOM => function(string $value): bool {
                        $user = Persist::read('User', Request::get()->getArg(1));
                        return ($user->getUsername() == $value || !Persist::exists('User', 'username', $value));
                    },
                    Validator::PARAM_MAX_LENGTH => 40,
                    Validator::PARAM_MESSAGES => [
                        Validator::PARAM_REQUIRED => 'Username can\'t be empty',
                        Validator::PARAM_CUSTOM => 'Username already taken',
                        Validator::PARAM_MAX_LENGTH => 'Username too long (max 40 chars)'
                    ]
                ],
                'password' => [
                    Validator::PARAM_REQUIRED => false,
                    Validator::PARAM_MIN_LENGTH => 8,
                    Validator::PARAM_MESSAGES => [
                        Validator::PARAM_MIN_LENGTH => 'Password too short (min 8 chars)'
                    ]
                ]
            ]);
            $rep = new Response();
            if ($validation->validate()) {
                $user->setUsername($_POST['username']);
                $user->setEmail($_POST['email']);
                if (isset($_POST['password']) && $_POST['password'] != '') {
                    $user->setPassword(PasswordManager::generateHash($_POST['password']));
                }
                Persist::update($user);
                $rep->addData('user', $user);
                return $rep;
            }
            else {
                $rep->addError('errors', $validation->getErrors());
            }

            $rep->setCode(400);
            return $rep;
        }

        return new Response(Response::HTTP_404_NOT_FOUND);
    }

    public static function delete(): Response {
        return new Response(Response::HTTP_405_METHOD_NOT_ALLOWED);
    }
}