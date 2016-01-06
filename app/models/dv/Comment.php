<?php
class Comment extends Entry {
    use FlagTrait;
    static $table_name = 'dv_comment';

    static $associations = [
        'poster' => [
            'type' => self::BELONGS_TO,
            'class_name' => 'User',
            'autoload' => true
        ],
        'video' => [
            'type' => self::BELONGS_TO,
            'class_name' => 'Video'
        ]
    ];

    /**
     * @var User
     */
    public $poster;
}