<?php
abstract class DB {
	private static
	$instance = null,
	$db_host = 'db',
	$db_name = 'dreamvids',
	$db_user = 'root',
	$db_pass = 'root';
	
	public static function get(): PDO {
		if (self::$instance == null) {
			try	{
				$options = [
						PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
				];
				self::$instance = new PDO('mysql:host='.self::$db_host.';dbname='.self::$db_name, self::$db_user, self::$db_pass, $options);
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
