<?php
interface ControllerInterface {
    public static function create(): Response;
    public static function fetch(): Response;
    public static function exists(): Response;
    public static function read(): Response;
    public static function update(): Response;
    public static function delete(): Response;
}