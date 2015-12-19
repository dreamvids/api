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
			try	{
				self::$instance = new PDO('mysql:host='.self::$db_host.';dbname='.self::$db_name, self::$db_user, self::$db_pass);
			}
			catch (Exception $e) {
				die('Erreur : ' . $e->getMessage());
			}
		}
		
		return self::$instance;
	}
}