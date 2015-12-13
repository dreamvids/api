<?php
class Client extends Entry implements ModelInterface
{
    static $table_name = 'client';

    public function __construct(int $id)
    {
        parent::__construct($id);
    }
}