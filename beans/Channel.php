<?php
/**
 * Created by PhpStorm.
 * User: peter_000
 * Date: 16/12/2016
 * Time: 17:10
 */

namespace Bean;


class Channel implements \Resourcable, \JsonSerializable {
    private $id;
    private $name;
    private $description;
    private $avatar;
    private $background;
    private $verified;
    private $user_id;

    /**
     * Channel constructor.
     * @param $id
     * @param $name
     * @param $description
     * @param $avatar
     * @param $background
     * @param $verified
     * @param $user_id
     */
    public function __construct(int $id = 0, string $name = '', string $description = '', string $avatar = '', string $background = '', bool $verified = false, int $user_id = 0) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->avatar = $avatar;
        $this->background = $background;
        $this->verified = $verified;
        $this->user_id = $user_id;
    }

    public static function getTableName(): string {
        return 'dv_channel';
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
        unset($copy->user_id);
        return $copy;
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
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getAvatar() {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar($avatar) {
        $this->avatar = $avatar;
    }

    /**
     * @return string
     */
    public function getBackground() {
        return $this->background;
    }

    /**
     * @param string $background
     */
    public function setBackground($background) {
        $this->background = $background;
    }

    /**
     * @return boolean
     */
    public function isVerified() {
        return $this->verified;
    }

    /**
     * @return boolean
     */
    public function getVerified() {
        return $this->verified;
    }

    /**
     * @param boolean $verified
     */
    public function setVerified($verified) {
        $this->verified = $verified;
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