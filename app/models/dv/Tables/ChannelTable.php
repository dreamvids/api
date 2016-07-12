<?php
class ChannelTable extends Table {

    protected static $table_name = "channel";

    public function initialize()
    {
        $this
            ->belongsTo(User::class, 'owner')
            ->hasMany(Video::class, 'videos')
            ->hasManyToMany(User::class, 'admins', ChannelAdmin::class);
    }
}