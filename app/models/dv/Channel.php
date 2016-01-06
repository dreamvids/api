<?php
class Channel extends Entry {
    static $table_name = 'dv_channel';

    static $associations = [
        'owner' => [
            'type' => self::BELONGS_TO,
            'class_name' => 'User',
            'autoload' => true
        ],
        'videos' => [
            'type' => self::HAS_MANY,
            'class_name' => 'Video',
            'autoload' => false
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