<?php
class RankTable extends Table {

    protected static $table_name = "rank";

    public function initialize()
    {
        $this
            ->hasMany(User::class, 'members')
        ;
    }

}