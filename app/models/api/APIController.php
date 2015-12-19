<?php
class APIController extends Entry implements ModelInterface
{
    static $table_name = 'api_controller';

    public function __construct(int $id)
    {
        parent::__construct($id);
    }
}