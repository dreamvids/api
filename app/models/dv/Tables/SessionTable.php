<?php

class SessionTable extends Table {
    protected static $table_name = 'session';

    public function initialize()
    {
        $this
            ->belongsTo(User::class)
        ;
    }
}