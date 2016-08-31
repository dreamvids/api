<?php
/**
 * Created by PhpStorm.
 * User: peter_000
 * Date: 26/08/2016
 * Time: 12:13
 */

namespace Model;


class APIClient {
    public static function authenticate() {
        if (\Config::get()->read('config.debug')) {
            return new \Bean\APIClient(0, 'root', 'localhost', 'root', 'root');
        }

        if (isset($_SERVER['HTTP_X_PUBLIC'], $_SERVER['HTTP_X_HASH'])) {
            if (\Persist::exists('APIClient', 'public_key', $_SERVER['HTTP_X_PUBLIC'])) {
                $client = \Persist::readBy('APIClient', 'public_key', $_SERVER['HTTP_X_PUBLIC']);
                $data = [
                    $client->getName(),
                    $client->getDomain(),
                    $_GET['arg'],
                    \Request::get()->getMethod(),
                    $_POST
                ];
                $received_hash = $_SERVER['HTTP_X_HASH'];
                $calculated_hash = hash_hmac('sha512', json_encode($data), $client->getPrivateKey());

                return ($received_hash == $calculated_hash) ? $client : null;
            }
        }

        return null;
    }

    public static function hasPermission(\Bean\APIClient $client, array $methods): bool {
        if (\Config::get()->read('config.debug')) {
            return true;
        }

        $permission = \Request::get()->getArg(0).'_'.$methods[\Request::get()->getMethod()];
        $req = \DB::get()->prepare("SELECT COUNT(*) AS nb FROM api_permission WHERE client_id = ? AND (permission = ? OR permission = '*')");
        $req->execute([$client->getId(), $permission]);
        $rep = $req->fetch();
        $req->closeCursor();

        return ($rep['nb'] > 0);
    }
}