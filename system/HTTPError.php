<?php
class HTTPError {
    public static function AuthorizationRequired() {
        return new Response([], 401);
    }

    public static function Forbidden() {
        return new Response([], 403);
    }

    public static function NotFound() {
        return new Response([], 404);
    }

    public static function MethodNotAllowed() {
        return new Response([], 405);
    }

    public static function InternalServerError() {
        return new Response([], 500);
    }
}