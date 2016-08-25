<?php

class Response
{
    static $codes = [
        200 => "OK",
        400 => "Bad Request",
        401 => "Unauthorized",
        403 => "Forbidden",
        404 => "Not Found",
        405 => "Method not allowed",
        500 => "Internal Server Error"
    ];

    public $code = 200;
    public $data = [];
    public $errors= [];
    public $debug = [];
    public $success = true;

    public function __construct(int $code = 200, array $data = []){
        $this->data = $data;
        $this->code = $code;
    }

    public function isSuccess() {
        return $this->success;
    }

    public function setSuccess(bool $success){
        $this->success = $success;
    }

    public function setCode(int $code): Response{
        $this->code = $code;
        if(400 <= $code && $code <= 599){
            $this->setSuccess(false);
        }
        return $this;
    }

    public function addData(string $name, $value) {
        $this->data[$name] = $value;
    }

    public function getData(): array {
        return $this->data;
    }

    public function setData(array $data) {
        if (is_array($data)) {
            $this->data = $data;
        }
    }

    public function addError(string $name, $value) {
        $this->errors[$name] = $value;
    }

    public function addDebug($var){
        $this->debug[] = $var;
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function setErrors(array $errors) {
        if (is_array($errors)) {
            $this->errors = $errors;
        }
    }

    protected function renderHeaders(){
        header('Content-type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('HTTP/1.1 ' . $this->code . " " . self::$codes[$this->code]);
    }

    protected function renderBody(){
        if(!Config::get()->read('config.debug')){
            unset($this->debug);
        }
        echo json_encode($this, JSON_PRETTY_PRINT);
    }

    public function render($exit = true){
        $this->renderHeaders();
        $this->renderBody();
        if($exit){
            exit();
        }
    }
}