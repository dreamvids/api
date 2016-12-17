<?php

/**
 * User: SundownDEV
 * Date: 26/08/2016
 * Time: 15:34
 */
class ChannelCtrl implements ControllerInterface {
    public static function fetch(): Response {
        $rep = PermChecker::get()->clientAdmin()->isPermit();
        $rep->addData('channel', Persist::fetchAll('Channel'));
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
            'name' => [
                Validator::PARAM_MIN_LENGTH => 4,
                Validator::PARAM_MAX_LENGTH => 25,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_MIN_LENGTH => 'Name too short (min 4 chars)'
                ]
            ],
            'description' => [
                Validator::PARAM_MIN_LENGTH => 4,
                Validator::PARAM_MAX_LENGTH => 1000,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_MIN_LENGTH => 'Description too short (min 4 chars)',
                    Validator::PARAM_MAX_LENGTH => 'Description too long (max 1000 chars)'
                ]
            ],
            'avatar' => [
                Validator::PARAM_TYPE => Validator::TYPE_FILE,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_TYPE => 'Avatar must be a valid image'
                ]
            ],
            'background' => [
                Validator::PARAM_TYPE => Validator::TYPE_FILE,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_TYPE => 'Background must be a valid image'
                ]
            ]
        ]);
        $rep = new Response();
        if ($validation->validate()) {
            $channel = new \Bean\Channel(
                0,
                $_POST['name'],
                $_POST['description'],
                $_POST['avatar'],
                $_POST['background'],
                0,
                Persist::readBy('User', 'id', 'Member')->getId()
            );
            $id = Persist::create($channel);
            $channel->setId($id);
            $rep->setCode(Response::HTTP_201_CREATED);
            $rep->addData('channel', $channel);
            return $rep;
        } else {
            $rep->addError('errors', $validation->getErrors());
        }

        $rep->setCode(Response::HTTP_400_BAD_REQUEST);
        return $rep;
    }

    public static function read(): Response {
        if (Persist::exists('Channel', 'id', Request::get()->getArg(1))) {
            $rep = PermChecker::get()->userId(Request::get()->getArg(1))->or(PermChecker::get()->clientAdmin())->isPermit();
            $rep->addData('channel', Persist::read('Channel', Request::get()->getArg(1)));
            return $rep;
        }

        return new Response(Response::HTTP_404_NOT_FOUND);
    }

    public static function update(): Response {
        if (Persist::exists('User', 'id', Request::get()->getArg(1))) {
            $rep = new Response();
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

            $rep->setCode(Response::HTTP_400_BAD_REQUEST);
            return $rep;
        }

        return new Response(Response::HTTP_404_NOT_FOUND);
    }

    public static function delete(): Response {
        return new Response(Response::HTTP_405_METHOD_NOT_ALLOWED);
    }
}