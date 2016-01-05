<?php
class HomeCtrl implements ControllerInterface {
    public static function create() {
        // TODO: Implement create() method.
    }

    public static function fetch() {
        Response::get()->addData('TITLE', 'Accueil');
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
        // TODO: Implement delete() method.
    }
}