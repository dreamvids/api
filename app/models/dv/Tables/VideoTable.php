<?php
class VideoTable extends Table {

    protected static $table_name = "video";

    public function initialize()
    {
        $this
            ->belongsTo(Channel::class, 'poster_channel')
            ->belongsTo(Visibility::class)
            ->hasMany(Comment::class, 'comments')
        ;
    }
}
