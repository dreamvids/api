<?php

class Dispatcher
{
    private static $instance;

    private final function __construct(){}

    public static function get(): Dispatcher{
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     *
     */
    public function handleRequest(){
        $this->setClient();
        $controller = $this->chooseController();
        $className = $this->loadController($controller);
        $this->loadPostData();
        $method = $this->getMethodToCall();
        $this->tryDispatch($controller, $className, $method);
    }

    protected function setClient(){

        // TODO: Remplacer 'root' par '' en prod absolument !!
        $tokenString = $_SERVER['HTTP_X_TOKEN'] ?? 'root';

        if (APIToken::exists('token', $tokenString)) {
            $token = APIToken::getBy('token', $tokenString);
            Request::get()->setClient(new APIClient($token->client_id));
        }
    }

    protected function chooseController(): APIController{
        $controller = APIController::getBy('uri', Request::get()->getArg(0));

        if(is_null($controller)){
            HTTPError::NotFound()->render();
        }else{
            return $controller;
        }
    }

    protected function loadController(APIController $controller): string{
        $filename = System::get()->getControllers().$controller->uri.'.php';
        $classname = ucfirst($controller->uri)."Ctrl";
        if (!file_exists($filename) ) {
            HTTPError::NotFound()->render();
        }else{
            require_once $filename;
            return $classname;
        }
    }

    protected function loadPostData(){
        if (!in_array(Request::get()->getMethod(), array('GET', 'POST'))) {
            parse_str(file_get_contents('php://input'), $_POST);
        }
    }

    protected function getMethodToCall(): string {
        $method = Request::get()->getMethodToCall();

        if ($method == '') {
            HTTPError::MethodNotAllowed()->render();
        } else {
            return $method;
        }
    }

    protected function tryDispatch(APIController $controller, string $classname, string $method){
        if (APIPermission::isPermit($controller, $method)) {
            $classname::$method();
        }
        else {
            HTTPError::Forbidden()->render();
        }
    }

}