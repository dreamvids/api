<?php
class UserTable extends Table {

    protected static $table_name = "user";

    public function initialize()
    {
        $this
            ->hasMany(Session::class, 'sessions')
            ->hasOne(Rank::class)
            ->hasMany(Channel::class, 'owned_channels')
            ->hasManyToMany(Channel::class, 'administred_channels', ChannelAdmin::class)
        ;
    }
}