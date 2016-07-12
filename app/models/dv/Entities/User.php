<?php
class User {

    public static function usernameExists(string $username): bool {
        return self::exists("username", $username);
    }

    public static function emailExists(string $email): bool {
        return self::exists("email", $email);
    }
}