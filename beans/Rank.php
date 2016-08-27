<?php
/**
 * Created by PhpStorm.
 * User: peter_000
 * Date: 27/08/2016
 * Time: 18:15
 */

namespace Bean;


class Rank implements \Resourcable {
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