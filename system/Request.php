<?php
class Request {
	private static $instance = null;
	private $args;
	private $method;
	
	private function __construct() {
		$this->args = (isset($_GET['arg']) ) ? explode('/', $_GET['arg']) : ['home'];
		$this->method = $_SERVER['REQUEST_METHOD'];
	}
	
	public static function get(): Request {
		if (self::$instance == null) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	public function getArg(int $i): string {
		if (count($this->args) > $i) {
			return $this->args[$i];
		}

		return '';
	}

	public function getMethod() {
		return $this->method;
	}

}