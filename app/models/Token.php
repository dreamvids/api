<?php
class Token extends Entry implements ModelInterface
{
    static $table_name = 'token';

    public function __construct(int $id)
    {
        parent::__construct($id);
    }

    public static function flush() {
        $req = DB::get()->prepare("DELETE FROM token WHERE timestamp + ttl < ?");
        $req->execute([Utils::time()]);
    }

    public static function generate(): string {
        return sha1(uniqid());
    }
}