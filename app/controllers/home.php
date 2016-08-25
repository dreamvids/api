<?php
class HomeCtrl implements ControllerInterface {
    public static function fetch(): Response {
        $rep = new Response();
        $rep->addData('Title', 'Accueil');
        return $rep;
    }

    public static function create(): Response {
        return new Response(201);
    }

    public static function exists(): Response {
        return new Response(409);
    }

    public static function read(): Response {
        $rep = new Response();
        $rep->addData('id', Request::get()->getArg(1));
        return $rep;
    }

    public static function update(): Response {
        return new Response();
    }

    public static function delete(): Response {
        return new Response();
    }
}