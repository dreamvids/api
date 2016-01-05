<?php
class APIToken extends Entry{

    use FlushTrait;

    static $table_name = 'api_token';

    public static function generate(): string {
        return sha1(uniqid());
    }
}