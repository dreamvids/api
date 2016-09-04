<?php

/**
 * Created by PhpStorm.
 * User: peter_000
 * Date: 03/09/2016
 * Time: 14:08
 */
class TokenCtrl implements ControllerInterface {
    public static function fetch(): Response {
        return new Response(Response::HTTP_405_METHOD_NOT_ALLOWED);
    }

    public static function create(): Response {
        Persist::deleteByCond('APIAuthToken', "WHERE expiration < ?", [Utils::time()]);
        $rep = new Response(Response::HTTP_201_CREATED);
        $validation = new Validator([
            Validator::RULE_ALL => [
                Validator::PARAM_REQUIRED => true,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_REQUIRED => 'You must provide a redirection URL'
                ]
            ],
            'redirect_url' => []
        ]);
        if ($validation->validate()) {
            $token = new \Bean\APIAuthToken(
                0,
                sha1(uniqid()),
                $_POST['redirect_url'],
                Utils::time() + 600,
                \Model\APIClient::getClient()->getId()
            );
            $id = Persist::create($token);
            $token->setId($id);
            $rep->addData('token', $token);
        }
        else {
            $rep->setCode(Response::HTTP_400_BAD_REQUEST);
            $rep->setErrors($validation->getErrors());
        }
        return $rep;
    }

    public static function read(): Response {
        $rep = new Response();
        if (Persist::exists('APIAuthToken', 'token', Request::get()->getArg(1))) {
            $rep = PermChecker::get()->clientId(\Model\APIClient::getClient()->getId())->or(PermChecker::get()->clientAdmin())->isPermit();
            $rep->addData('token', Persist::readBy('APIAuthToken', 'token', Request::get()->getArg(1)));
            return $rep;
        }
        $rep->setCode(Response::HTTP_404_NOT_FOUND);
        return $rep;
    }

    public static function update(): Response {
        return new Response(Response::HTTP_405_METHOD_NOT_ALLOWED);
    }

    public static function delete(): Response {
        return new Response(Response::HTTP_405_METHOD_NOT_ALLOWED);
    }
}