<?php

trait FlushTrait {
    public static function flush() {
        $req = DB::get()->prepare("DELETE FROM ".static::$table_name." WHERE timestamp + ttl < ?");
        $req->execute([Utils::time()]);
    }
}