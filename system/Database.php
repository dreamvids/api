<?php
abstract class DB {
	private static
		$instance = null;

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
				$rep = new Response(Response::HTTP_500_INTERNAL_SERVER_ERROR);
				$rep->addError('status', "Can't connect to database");
				$rep->render();
			}
		}

		return self::$instance;
	}
}
