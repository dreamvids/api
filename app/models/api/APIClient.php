<?php
class APIClient extends Entry implements ModelInterface
{
    static $table_name = 'api_client';

    public function __construct(int $id)
    {
        parent::__construct($id);
    }
}