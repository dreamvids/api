<?php
// System constants
require_once 'System.php';
// All requires
System::get()->loadDependencies(['system', 'traits', 'api_models', 'dv_models']);

APIToken::flush();
Dispatcher::get()->handleRequest();
Response::get()->render();
