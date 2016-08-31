<?php
/**
 * Created by PhpStorm.
 * User: peter_000
 * Date: 31/08/2016
 * Time: 11:59
 */

namespace Bean;


class Session implements \Resourcable, \JsonSerializable {
    private $id;
    private $session_id;
    private $expiration_timestamp;
    private $user_id;

    /**
     * Session constructor.
     * @param $id
     * @param $session_id
     * @param $expiration_timestamp
     * @param $user_id
     */
    public function __construct(int $id = 0, string $session_id = '', int $expiration_timestamp = 0, int $user_id = 0) {
        $this->id = $id;
        $this->session_id = $session_id;
        $this->expiration_timestamp = $expiration_timestamp;
        $this->user_id = $user_id;
    }

    public static function getTableName(): string {
        return 'dv_session';
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize() {
        $copy = clone $this;
        unset($copy->id, $copy->user_id);
        return get_object_vars($copy);
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
    public function getSessionId() {
        return $this->session_id;
    }

    /**
     * @param string $session_id
     */
    public function setSessionId($session_id) {
        $this->session_id = $session_id;
    }

    /**
     * @return int
     */
    public function getExpirationTimestamp() {
        return $this->expiration_timestamp;
    }

    /**
     * @param int $expiration_timestamp
     */
    public function setExpirationTimestamp($expiration_timestamp) {
        $this->expiration_timestamp = $expiration_timestamp;
    }

    /**
     * @return int
     */
    public function getUserId() {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId($user_id) {
        $this->user_id = $user_id;
        $this->user = \Persist::read('User', $user_id);
    }
}