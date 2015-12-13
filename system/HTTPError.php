<?php
class HTTPError
{
    public static function AuthorizationRequired() {
        header('HTTP/1.1 401 Authorization Required');
    }

    public static function Forbidden() {
        header('HTTP/1.1 403 Forbidden');
    }

    public static function NotFound() {
        header('HTTP/1.1 404 Not Found');
    }

    public static function MethodNotAllowed() {
        header('HTTP/1.1 405 Method Not Allowed');
    }

    public static function InternalServerError() {
        header('HTTP/1.1 500 Internal Server Error');
    }
}