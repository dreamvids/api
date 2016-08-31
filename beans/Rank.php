<?php
/**
 * Created by PhpStorm.
 * User: peter_000
 * Date: 27/08/2016
 * Time: 18:15
 */

namespace Bean;


class Rank implements \Resourcable, \JsonSerializable {
    private $id;
    private $name;

    /**
     * Rank constructor.
     * @param $id
     * @param $name
     */
    public function __construct(int $id = 0, string $name = '') {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize() {
        return get_object_vars($this);
    }

    public static function getTableName(): string {
        return 'dv_rank';
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
}