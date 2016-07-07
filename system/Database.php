<?php
abstract class DB {
	private static
	$instance = null,
	$db_host = 'localhost',
	$db_name = 'dreamvids',
	$db_user = 'root',
	$db_pass = '';
	
	public static function get(): PDO {
		if (self::$instance == null) {

            $credentials = Config::get()->read("database");

			try	{
				$options = [
						PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
				];
				self::$instance = new PDO('mysql:host='.$credentials['host'].';dbname='.$credentials['dbname'], $credentials['username'], $credentials['password'], $options);
			}
			catch (Exception $e) {
				Response::get()->setSuccess(false);
				Response::get()->addError('status', "Can't connect to database");
				HTTPError::InternalServerError()->render();
			}
		}
		
		return self::$instance;
	}
}
