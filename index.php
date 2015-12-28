<?php
// System constants
require_once 'System.php';
// All requires
System::get()->loadDependencies(['system', 'traits', 'api_models', 'dv_models']);

APIToken::flush();
// TODO: Remplacer 'root' par '' en prod absolument !!
$tokenString = $_SERVER['HTTP_X_TOKEN'] ?? 'root';
if (APIToken::exists('token', $tokenString)) {
	$token = APIToken::getBy('token', $tokenString);
	Request::get()->setClient(new APIClient($token->client_id));
}

Response::get();
$controller = APIController::getBy('uri', Request::get()->getArg(0));

if(is_null($controller)){
	HTTPError::NotFound()->render();
}

$filename = System::get()->getControllers().$controller->uri.'.php';
$classname = ucfirst($controller->uri)."Ctrl";
if (!file_exists($filename) ) {
	HTTPError::NotFound()->render();
	exit();
}

if (!in_array(Request::get()->getMethod(), array('GET', 'POST'))) {
    parse_str(file_get_contents('php://input'), $_POST);
}

require_once System::get()->getSystem().'ControllerInterface.php';
require_once $filename;


$method = Request::get()->getMethodToCall();

if($method == ''){
	HTTPError::MethodNotAllowed()->render();
}

if (APIPermission::isPermit($controller, $method)) {
	$classname::$method();
}
else {
	HTTPError::Forbidden()->render();
}

Response::get()->render();