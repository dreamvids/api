<?php
class User extends Entry {

    static $table_name = 'dv_user';

    /**
     * @var Rank
     */
    public $rank;

    public $owned_channels = [];

    static $associations = [
        'rank' => [
            'type' => self::BELONGS_TO,
            'class_name' => 'Rank'
        ],
        'owned_channels' => [
            'type' => self::HAS_MANY,
            'class_name' => 'Channel'
        ],
        'comments' => [
            'type' => self::HAS_MANY,
            'class_name' => 'Comment'

        ],
        'administred_channels' => [
            'type' => self::MANY_TO_MANY,
            'class_name' => 'Channel',
            'through' => 'ChannelAdmin'
        ]
    ];

    public static function usernameExists(string $username): bool {
        return self::exists("username", $username);
    }

    public static function emailExists(string $email): bool {
        return self::exists("email", $email);
    }
}