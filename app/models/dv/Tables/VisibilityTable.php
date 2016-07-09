<?php
class VisibilityTable extends Table {
    protected static $table_name = 'visibility';

    public function initialize()
    {
        $this
            ->hasMany(Video::class, 'videos')
        ;
    }
}