<?php
/**
 * Created by PhpStorm.
 * User: peter_000
 * Date: 05/07/2016
 * Time: 12:23
 */

namespace Bean;


class Example implements \Resourcable{
    private $id;
    private $name;
    private $description;

    /**
     * Example constructor.
     * @param $id
     * @param $name
     * @param $description
     */
    public function __construct($id = 0, $name = '', $description = '') {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public static function getTableName(): string {
        return 'example';
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
}