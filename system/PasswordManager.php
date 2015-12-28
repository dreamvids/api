<?php

class PasswordManager{

    const DEFAULT_HASH = PASSWORD_BCRYPT;

    public static function generateHash($plainTextPass, $algo = self::DEFAULT_HASH): string{
        return password_hash($plainTextPass, $algo);
    }

    public static function checkPass($plainTextPass, $hash): bool{
        return password_verify($plainTextPass, $hash);
    }

}