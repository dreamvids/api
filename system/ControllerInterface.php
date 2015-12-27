<?php
interface ControllerInterface {
    public static function create();
    public static function fetch();
    public static function exists();
    public static function read();
    public static function update();
    public static function delete();
}