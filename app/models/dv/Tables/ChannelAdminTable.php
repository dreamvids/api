<?php
class ChannelAdminTable extends Table {
    protected static $table_name = 'channel_admin';

    public function initialize()
    {
        $this
            ->belongsTo(Channel::class)
            ->belongsTo(User::class)
        ;
    }
}