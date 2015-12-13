<?php
class TokenCtrl implements ControllerInterface
{
    public static function create()
    {
        // TODO: Remplacer 'root' par '' en prod absolument !!
        $public_key = $_SERVER['HTTP_X_PUBLIC_KEY'] ?? 'root';
        // TODO: Remplacer 'hash_hmac(...)' par '' en prod absolument !!
        $received_hash = $_SERVER['HTTP_X_HASH'] ?? hash_hmac('sha256', 'root', 'root');

        if (Client::exists('public_key', $public_key)) {
            $client = Client::getBy('public_key', $public_key);
            $expected_hash = hash_hmac('sha256', $client->name, $client->private_key);

            if ($received_hash == $expected_hash) {
                $token = Token::generate();
                $ttl = 600;
                Token::insertIntoDb([$token, Utils::time(), $ttl, $client->id]);
                Data::get()->add('token', $token);
                return null;
            }
        }

        Data::get()->add('error', 'Wrong keypair');
    }

    public static function fetch()
    {
        HTTPError::MethodNotAllowed();
    }

    public static function exists()
    {
        HTTPError::MethodNotAllowed();
    }

    public static function read()
    {
        HTTPError::MethodNotAllowed();
    }

    public static function update()
    {
        HTTPError::MethodNotAllowed();
    }

    public static function delete()
    {
        $token = Token::getBy('token', $_SERVER['HTTP_X_TOKEN']);
        $token->delete();
        Data::get()->add('message', 'Good bye !');
    }
}