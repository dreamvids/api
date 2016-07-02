<?php
/**
 * Created by PhpStorm.
 * User: peter_000
 * Date: 02/07/2016
 * Time: 12:52
 */

class VideoCtrl implements ControllerInterface {
    use ExistsTrait;

    public static function create() {
        $validation = new Validator([
            'title' => [
                Validator::PARAM_REQUIRED => true,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_REQUIRED => 'Title required'
                ]
            ],
            'video' => [
                Validator::PARAM_REQUIRED => true,
                Validator::PARAM_MESSAGES => [
                    Validator::PARAM_REQUIRED => 'A video file il (obviously) required...'
                ]
            ]
        ], $_POST + $_FILES);

        if($validation->validate()){

        }else{
            Response::get()->addError('validation', $validation->getErrors());
            Response::get()->setSuccess(false);

            HTTPError::BadRequest();
        }
    }

    public static function fetch() {
        // TODO: Implement fetch() method.
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