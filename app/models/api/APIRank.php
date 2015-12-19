<?php
class APIRank extends Entry implements ModelInterface
{
    static $table_name = 'api_rank';

    public function __construct(int $id)
    {
        parent::__construct($id);
    }
}