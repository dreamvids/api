<?php
class ChannelCtrl implements ControllerInterface {

    use ExistsTrait;

    public static function create() {
        // TODO: Implement create() method.
    }

    public static function fetch() {
        // TODO: Implement fetch() method.
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