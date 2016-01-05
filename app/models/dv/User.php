<?php
class User extends Entry {

    static $table_name = 'dv_user';

    /**
     * @var Rank
     */
    public $rank;

    static $associations = [
        'rank' => [
            'type' => self::BELONGS_TO,
            'class_name' => 'Rank',
            'autoload' => true
        ],
        'channels' => [
            'type' => self::HAS_MANY,
            'class_name' => 'Channel'
        ]
    ];

    public static function usernameExists(string $username): bool {
        return self::exists("username", $username);
    }

    public static function emailExists(string $email): bool {
        return self::exists("email", $email);
    }
}