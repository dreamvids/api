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
	
	public function decodeBody() {
		if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
			if(($body = json_decode(file_get_contents('php://input'), true)) !== null){
				$_POST = $body;
			}else{
				throw new JsonException(json_last_error_msg(), json_last_error());
			}
		}
		else {
			parse_str(file_get_contents('php://input'), $_POST);
		}
	}

}