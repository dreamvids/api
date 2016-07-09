<?php
class Channel extends Entry {
    static $table_name = 'dv_channel';

    static $associations = [
        'owner' => [
            'type' => self::BELONGS_TO,
            'class_name' => 'User'
        ],
        'videos' => [
            'type' => self::HAS_MANY,
            'class_name' => 'Video'
        ],
        'admins' => [
            'type' => self::MANY_TO_MANY,
            'class_name' => 'User',
            'through' => 'ChannelAdmin'
        ]
    ];

    /**
     * @var User
     */
    public $owner;
}