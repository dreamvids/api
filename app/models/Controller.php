<?php
class Controller extends Entry implements ModelInterface
{
    static $table_name = 'controller';

    public function __construct(int $id)
    {
        parent::__construct($id);
    }
}