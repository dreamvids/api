<?php
class Visibility extends Entry implements ModelInterface {
    static $table_name = 'dv_visibility';

    public function __construct(int $id, array $data = [], array $options = []) {
        parent::__construct($id, $data);
    }
}