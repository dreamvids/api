<?php
class HTTPError {

    public static function BadRequest() {
        return Response::get()->setCode(400);
    }

    public static function AuthorizationRequired() {
        return Response::get()->setCode(401);
    }

    public static function Forbidden() {
        return Response::get()->setCode(403);
    }

    public static function NotFound() {
        return Response::get()->setCode(404);
    }

    public static function MethodNotAllowed() {
        return Response::get()->setCode(405);
    }

    public static function InternalServerError() {
        return Response::get()->setCode(500);
    }
}