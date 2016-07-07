<?php

// Setting up the exception handler

function exceptionsHandler($code, $message = null, $file = null, $line = null){
    if(Config::get()->read("config.debug")){
        if($code instanceof Exception){
            $exception = $code;
            Response::get()->addError('exception', $exception);
        }else{
            Response::get()->addError('exception', [
                'code' => $code,
                'message' => $message,
                'file' => $file,
                'line' => $line,
                'stacktrace' => debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)
            ]);
        }

    }
    HTTPError::InternalServerError();
    Response::get()->render();
}
set_error_handler('exceptionsHandler');
set_exception_handler('exceptionsHandler');

//Starting the app

// System constants
require_once __DIR__ . DIRECTORY_SEPARATOR . 'System.php';
// All requires
System::get()->loadDependencies(['system', 'traits', 'api_models', 'dv_models']);

APIToken::flush();

Dispatcher::get()->handleRequest();
Response::get()->render();