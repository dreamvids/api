<?php
class HomeCtrl implements ControllerInterface {
    public static function fetch(): Response {
        $rep = new Response();
        $rep->addData('Title', 'Accueil');
        return $rep;
    }

    public static function create(): Response {
        return new Response(Response::HTTP_201_CREATED);
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