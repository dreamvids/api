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
require_once SYSTEM.'HTTPError.php';
require_once SYSTEM.'Request.php';
require_once SYSTEM.'Config.php';
require_once SYSTEM.'PasswordManager.php';
require_once SYSTEM.'Response.php';
require_once SYSTEM.'Validator.php';

// Beans
require_once BEANS.'Example.php';

// Models
require_once MODELS.'Example.php';

if (!file_exists(CONTROLLERS.Request::get()->getArg(0).'.php') ) {
	HTTPError::error404()->render();
}

require_once CONTROLLERS.Request::get()->getArg(0).'.php';

$methods = [
	'GET' => 'fetch',
	'POST' => 'create',
	'HEAD' => 'exists',
	'PATCH' => 'update',
	'DELETE' => 'delete'
];
$classname = ucfirst(Request::get()->getArg(0)).'Ctrl';
if (Request::get()->getArg(1) != '') {
	if (method_exists($classname, Request::get()->getArg(1))) {
		$methods['GET'] = Request::get()->getArg(1);
	}
	else {
		$methods['GET'] = 'read';
	}
}

if (isset($methods[$_SERVER['REQUEST_METHOD']])) {
	$methodname = $methods[$_SERVER['REQUEST_METHOD']];
}
else {
	HTTPError::error405()->render();
}

$rep = $classname::$methodname();
$rep->render();