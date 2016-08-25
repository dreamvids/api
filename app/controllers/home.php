<?php
class HomeCtrl implements ControllerInterface {
    public static function fetch(): Response {
        $rep = new Response();
        $rep->addData('Title', 'Accueil');
        return $rep;
    }

    public static function create(): Response {
        // TODO: Implement create() method.
    }

    public static function exists(): Response {
        // TODO: Implement exists() method.
    }

    public static function read(): Response {
        // TODO: Implement read() method.
    }

    public static function update(): Response {
        // TODO: Implement update() method.
    }

    public static function delete(): Response {
        // TODO: Implement delete() method.
    }
}