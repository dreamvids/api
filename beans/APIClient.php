<?php
/**
 * Created by PhpStorm.
 * User: peter_000
 * Date: 26/08/2016
 * Time: 12:06
 */

namespace Bean;


class APIClient implements \Resourcable {
    private $id;
    private $name;
    private $domain;
    private $public_key;
    private $private_key;
    private $admin;

    /**
     * APIClient constructor.
     * @param $id
     * @param $name
     * @param $domain
     * @param $public_key
     * @param $private_key
     * @param $admin
     */
    public function __construct(int $id = 0, string $name = '', string $domain = '', string $public_key = '', string $private_key = '', bool $admin = false) {
        $this->id = $id;
        $this->name = $name;
        $this->domain = $domain;
        $this->public_key = $public_key;
        $this->private_key = $private_key;
        $this->admin = $admin;
    }

    public static function getTableName(): string {
        return 'api_client';
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDomain() {
        return $this->domain;
    }

    /**
     * @param string $domain
     */
    public function setDomain($domain) {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getPublicKey(): string {
        return $this->public_key;
    }

    /**
     * @param string $public_key
     */
    public function setPublicKey(string $public_key) {
        $this->public_key = $public_key;
    }

    /**
     * @return string
     */
    public function getPrivateKey(): string {
        return $this->private_key;
    }

    /**
     * @param string $private_key
     */
    public function setPrivateKey(string $private_key) {
        $this->private_key = $private_key;
    }

    /**
     * @return boolean
     */
    public function isAdmin() {
        return $this->admin;
    }

    /**
     * @return boolean
     */
    public function getAdmin() {
        return $this->admin;
    }

    /**
     * @param boolean $admin
     */
    public function setAdmin($admin) {
        $this->admin = $admin;
    }
}