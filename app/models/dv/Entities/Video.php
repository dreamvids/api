<?php
class Video extends Entry {

    use FlagTrait;

    static $table_name = "dv_video";

    static $associations = [
        'poster_channel' => [
            'type' => self::BELONGS_TO,
            'class_name' => 'Channel'
        ],
        'visibility' => [
            'type' => self::BELONGS_TO,
            'class_name' => 'Visibility'
        ],
        'comments' => [
            'type' => self::HAS_MANY,
            'class_name' => 'Comment'
        ]
    ];
}
