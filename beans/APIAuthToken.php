<?php
/**
 * Created by PhpStorm.
 * User: peter_000
 * Date: 03/09/2016
 * Time: 14:05
 */

namespace Bean;


class APIAuthToken implements \Resourcable, \JsonSerializable {
    private $id;
    private $token;
    private $redirect_url;
    private $expiration;
    private $client_id;
    private $session_id;

    /**
     * APIAuthToken constructor.
     * @param $id
     * @param $token
     * @param $redirect_url
     * @param $expiration
     * @param $client_id
     * @param $session_id
     */
    public function __construct(int $id = 0, string $token = '', $redirect_url = '', int $expiration = 0, int $client_id = 0, $session_id = null) {
        $this->id = $id;
        $this->token = $token;
        $this->redirect_url = $redirect_url;
        $this->expiration = $expiration;
        $this->client_id = $client_id;
        $this->session_id = $session_id;
    }

    public static function getTableName(): string {
        return 'api_auth_token';
    }

    public function jsonSerialize() {
        return ['token' => $this->token, 'redirect_url' => $this->redirect_url];
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
    public function getToken() {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token) {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getRedirectUrl() {
        return $this->redirect_url;
    }

    /**
     * @param string $redirect_url
     */
    public function setRedirectUrl($redirect_url) {
        $this->redirect_url = $redirect_url;
    }

    /**
     * @return int
     */
    public function getExpiration() {
        return $this->expiration;
    }

    /**
     * @param int $expiration
     */
    public function setExpiration($expiration) {
        $this->expiration = $expiration;
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
        $this->client = \Persist::read('APIClient', $client_id);
    }

    /**
     * @return int
     */
    public function getSessionId() {
        return $this->session_id;
    }

    /**
     * @param int $session_id
     */
    public function setSessionId($session_id) {
        $this->session_id = $session_id;
        if ($session_id != null) {
            $this->session = \Persist::readBy('Session', 'session_id', $session_id);
        }
    }
}