<?php
class Permission extends Entry implements ModelInterface
{
    static $table_name = 'permission';

    public function __construct(int $id)
    {
        parent::__construct($id);
    }

    public static function isPermit(Controller $ctrl, string $method): bool {
        $req = DB::get()->prepare("SELECT COUNT(*) AS nb FROM permission WHERE controller_id = ? AND `action` = ? AND rank_id = ?");
        $req->execute([$ctrl->id, $method, Request::get()->getClient()->rank_id]);
        $data = $req->fetch();
        $req->closeCursor();
        return ($data['nb'] > 0);
    }
}