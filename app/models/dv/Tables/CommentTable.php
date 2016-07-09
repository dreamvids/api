<?php
class CommentTable extends Table {
    protected static $table_name = 'comment';

    public function initialize()
    {
        $this
            ->belongsTo(Video::class)
            ->belongsTo(User::class, 'poster')
            ->belongsTo(Comment::class, 'parent')
            ->hasMany(Comment::class, 'answers');
        ;
    }
}