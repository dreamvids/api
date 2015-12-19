<?php
// System constants
require_once 'System.php';

// System requires
require_once System::get()->getSystem().'Database.php';
require_once System::get()->getSystem().'ModelInterface.php';
require_once System::get()->getSystem().'Model.php';
require_once System::get()->getSystem().'Entry.php';
require_once System::get()->getSystem().'Utils.php';
require_once System::get()->getSystem().'Request.php';
require_once System::get()->getSystem().'Data.php';
require_once System::get()->getSystem().'HTTPError.php';


// API Models
require_once System::get()->getApiModels().'APIClient.php';
require_once System::get()->getApiModels().'APIController.php';
require_once System::get()->getApiModels().'APIPermission.php';
require_once System::get()->getApiModels().'APIRank.php';
require_once System::get()->getApiModels().'APIToken.php';

// DreamVids Models

APIToken::flush();
// TODO: Remplacer 'root' par '' en prod absolument !!
$tokenString = $_SERVER['HTTP_X_TOKEN'] ?? 'root';
if (APIToken::exists('token', $tokenString)) {
	$token = APIToken::getBy('token', $tokenString);
	Request::get()->setClient(new APIClient($token->client_id));
}

$controller = APIController::getBy('uri', Request::get()->getArg(0));
$filename = System::get()->getControllers().$controller->uri.'.php';
$classname = ucfirst($controller->uri)."Ctrl";
if (!file_exists($filename) ) {
	HTTPError::NotFound();
	exit();
}

if (!in_array(Request::get()->getMethod(), array('GET', 'POST'))) {
    parse_str(file_get_contents('php://input'), $_POST);
}

require_once System::get()->getSystem().'ControllerInterface.php';
require_once $filename;

$method = Request::get()->getMethodToCall();
if (APIPermission::isPermit($controller, $method)) {
	$classname::$method();
}
else {
	HTTPError::Forbidden();
}

Request::get()->sendJSON();