<?php
class User extends Entry implements ModelInterface {
    static $table_name = 'dv_user';

    public function __construct(int $id) {
        parent::__construct($id);
    }

    public static function usernameExists(string $username): bool {
        return self::exists("username", $username);
    }

    public static function emailExists(string $email): bool {
        return self::exists("email", $email);
    }
}