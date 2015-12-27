<?php
class APIToken extends Entry implements ModelInterface {

    use FlushTrait;

    static $table_name = 'api_token';

    public function __construct(int $id) {
        parent::__construct($id);
    }

    public static function generate(): string {
        return sha1(uniqid());
    }
}