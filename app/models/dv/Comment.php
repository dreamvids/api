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
        ],
        'replies' => [
            'type' => self::HAS_MANY,
            'class_name' => 'Comment',
            'field_name' => 'parent_id',
            'autoload' => true
        ]
    ];

    /**
     * @var User
     */
    public $poster;
}