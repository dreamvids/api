<?php
class ChannelCtrl implements ControllerInterface {
    public static function create() {}

    public static function fetch() {
        // TODO: Implement fetch() method.
    }

    public static function exists() {

    }

    public static function read() {
        if(Channel::exists('id', Request::get()->getArg(1))){
            $channel = Channel::getBy('id', Request::get()->getArg(1));
            Response::get()->addData('channel', $channel);
        }else{
            HTTPError::NotFound();
            Response::get()->addError('status', Response::$codes[404]);
        }
    }

    public static function update() {
        // TODO: Implement update() method.
    }

    public static function delete() {
        // TODO: Implement delete() method.
    }
}