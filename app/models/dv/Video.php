<?php
class Video extends Entry {

    use FlagTrait;

    static $table_name = "dv_video";

    static $associations = [
        'poster_channel' => [
            'type' => self::BELONGS_TO,
            'class_name' => 'Channel',
            'autoload' => false
        ],
        'visibility' => [
            'type' => self::BELONGS_TO,
            'class_name' => 'Visibility',
            'autoload' => true,
        ],
        'comments' => [
            'type' => self::HAS_MANY,
            'class_name' => 'Comment'
        ]
    ];
}
