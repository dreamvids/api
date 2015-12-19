<?php
class APIToken extends Entry implements ModelInterface
{
    static $table_name = 'api_token';

    public function __construct(int $id)
    {
        parent::__construct($id);
    }

    public static function flush() {
        $req = DB::get()->prepare("DELETE FROM ".static::$table_name." WHERE timestamp + ttl < ?");
        $req->execute([Utils::time()]);
    }

    public static function generate(): string {
        return sha1(uniqid());
    }
}