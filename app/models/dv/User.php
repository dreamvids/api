<?php
class User extends Entry implements ModelInterface {
    static $table_name = 'dv_user';

    /**
     * @var Rank
     */
    public $rank;

    public function __construct(int $id) {
        parent::__construct($id);
        $this->rank = $this->getAssociated('Rank', self::BELONGS_TO);
    }

    public static function usernameExists(string $username): bool {
        return self::exists("username", $username);
    }

    public static function emailExists(string $email): bool {
        return self::exists("email", $email);
    }
}