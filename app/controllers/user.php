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
        // TODO: Implement create() method.
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