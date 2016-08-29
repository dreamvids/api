<?php
/**
 * Created by PhpStorm.
 * User: peter_000
 * Date: 26/08/2016
 * Time: 19:28
 */

namespace Bean;


class User implements \Resourcable, \JsonSerializable {
    private $id;
    private $username;
    private $password;
    private $email;
    private $reg_timestamp;
    private $reg_ip;
    private $current_ip;
    private $rank_id;

    /**
     * User constructor.
     * @param $id
     * @param $username
     * @param $password
     * @param $email
     * @param $reg_timestamp
     * @param $reg_ip
     * @param $current_ip
     * @param $rank_id
     */
    public function __construct(int $id = 0, string $username = '', string $password = '', string $email = '', int $reg_timestamp = 0, string $reg_ip = '', string $current_ip = '', int $rank_id = 0) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->reg_timestamp = $reg_timestamp;
        $this->reg_ip = $reg_ip;
        $this->current_ip = $current_ip;
        $this->rank_id = $rank_id;
    }

    public static function getTableName(): string {
        return 'dv_user';
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize(): array {
        return get_object_vars($this);
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function getRegTimestamp() {
        return $this->reg_timestamp;
    }

    /**
     * @param int $reg_timestamp
     */
    public function setRegTimestamp($reg_timestamp) {
        $this->reg_timestamp = $reg_timestamp;
    }

    /**
     * @return string
     */
    public function getRegIp() {
        return $this->reg_ip;
    }

    /**
     * @param string $reg_ip
     */
    public function setRegIp($reg_ip) {
        $this->reg_ip = $reg_ip;
    }

    /**
     * @return string
     */
    public function getCurrentIp() {
        return $this->current_ip;
    }

    /**
     * @param string $current_ip
     */
    public function setCurrentIp($current_ip) {
        $this->current_ip = $current_ip;
    }

    /**
     * @return int
     */
    public function getRankId() {
        return $this->rank_id;
    }

    /**
     * @param int $rank_id
     */
    public function setRankId($rank_id) {
        $this->rank_id = $rank_id;
        $this->rank = \Persist::read('Rank', $rank_id);
    }
}