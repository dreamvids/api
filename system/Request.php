<?php
class Request {
	private static $instance = null;
	private $args;
	private $client;
	private $controller;
	private $method;
	
	private function __construct() {
		$this->args = (isset($_GET['arg']) ) ? explode('/', trim($_GET['arg'], '/')) : ['home'];
		$this->client = APIClient::getBy('name', 'Guest');
		$this->controller = ucfirst($this->args[0])."Ctrl";
		$this->method = $_SERVER['REQUEST_METHOD'];
	}
	
	public static function get(): Request {
		if (self::$instance == null) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	public function getArg(int $i): string {
		if ($this->countArgs() > $i) {
			return $this->args[$i];
		}

		return '';
	}

	public function countArgs(): int {
		return count($this->args);
	}

	public function sendJSON() {
		$data = Data::get()->getData();
		header('Content-Type: application/json; charset=utf-8');

		//Dev
		//var_dump($data);

		//Prod
		echo json_encode($data);
	}

	public function getMethodToCall(): string {
		if ($this->countArgs() > 1) {
			if (method_exists($this->controller, $this->args[1])) {
				return $this->args[1];
			}
			else {
				$methods = [
					'GET' => 'read',
					'PUT' => 'update',
					'DELETE' => 'delete'
				];

				return $methods[$this->method];
			}
		}
		else {
			$methods = [
				'POST' => 'create',
				'GET' => 'fetch'
			];

			return $methods[$this->method];
		}
	}

	public function getClient(): APIClient {
		return $this->client;
	}

	public function setClient(APIClient $client) {
		$this->client = $client;
	}

	public function getMethod(): string	{
		return $this->method;
	}
}