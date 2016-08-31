<?php
define('NAME', 'MVC');
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']), true);
define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']), true);
define('BEANS', ROOT.'beans/');
define('SYSTEM', ROOT.'system/');
define('CONFIG', ROOT.'config/');
define('APP', ROOT.'app/');
define('MODELS', APP.'models/');
define('VIEWS', APP.'views/');
define('CONTROLLERS', APP.'controllers/');
define('ASSETS', WEBROOT.'assets/');
define('CSS', ASSETS.'css/');
define('JS', ASSETS.'js/');
define('FONTS', ASSETS.'fonts/');
define('IMG', ASSETS.'img/');


// System requires
require_once SYSTEM.'ModelInterface.php';
require_once SYSTEM.'ResourceInterface.php';
require_once SYSTEM.'ControllerInterface.php';
require_once SYSTEM.'Utils.php';
require_once SYSTEM.'Database.php';
require_once SYSTEM.'Persist.php';
require_once SYSTEM.'Request.php';
require_once SYSTEM.'Config.php';
require_once SYSTEM.'PasswordManager.php';
require_once SYSTEM.'Response.php';
require_once SYSTEM.'Validator.php';
require_once SYSTEM.'JsonException.php';
require_once SYSTEM.'Router.php';

// Beans
require_once BEANS.'APIClient.php';
require_once BEANS.'APIPermission.php';
require_once BEANS.'User.php';
require_once BEANS.'Rank.php';
require_once BEANS.'Session.php';

// Models
require_once MODELS.'APIClient.php';
require_once MODELS.'Session.php';

// Controller
require_once Router::get()->getPathToRequire();

$classname = ucfirst(Request::get()->getArg(0)).'Ctrl';
if (Request::get()->getArg(1) != '') {
	$_METHODS = [
		'GET' => 'read',
		'HEAD' => 'exists',
		'PATCH' => 'update',
		'DELETE' => 'delete'
	];
	if (method_exists($classname, Request::get()->getArg(1))) {
		$_METHODS['GET'] = Request::get()->getArg(1);
	}
}
else {
	$_METHODS = [
		'GET' => 'fetch',
		'POST' => 'create'
	];
}

if (isset($_METHODS[Request::get()->getMethod()])) {
	$methodname = $_METHODS[Request::get()->getMethod()];
	if (Request::get()->getMethod() != 'GET') {
		try{
			Request::get()->decodeBody();
		}catch (JsonException $e){
			$response = new Response(Response::HTTP_404_NOT_FOUND);
			$response->addError("json", $e->getMessage());
			$response->render();
		}
	}
}
else {
	(new Response(Response::HTTP_405_METHOD_NOT_ALLOWED))->render();
}

$client = \Model\APIClient::authenticate();
$session = \Model\Session::authenticate();
if ($client != null) {
	if (\Model\APIClient::hasPermission($client, $_METHODS)) {
		$rep = $classname::$methodname();
		$rep->render();
	}
}
(new Response(Response::HTTP_403_FORBIDDEN))->render();