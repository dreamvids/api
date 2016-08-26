<?php
/**
 * Created by PhpStorm.
 * User: peter_000
 * Date: 26/08/2016
 * Time: 12:08
 */

namespace Bean;


class APIPermission implements \Resourcable {
    private $id;
    private $permission;
    private $client_id;

    /**
     * APIPermission constructor.
     * @param $id
     * @param $permission
     * @param $client_id
     */
    public function __construct(int $id = 0, string $permission = '', int $client_id = 0) {
        $this->id = $id;
        $this->permission = $permission;
        $this->client_id = $client_id;
    }

    public static function getTableName(): string {
        return 'api_permission';
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
    public function getPermission() {
        return $this->permission;
    }

    /**
     * @param string $permission
     */
    public function setPermission($permission) {
        $this->permission = $permission;
    }

    /**
     * @return int
     */
    public function getClientId() {
        return $this->client_id;
    }

    /**
     * @param int $client_id
     */
    public function setClientId($client_id) {
        $this->client_id = $client_id;
    }
}