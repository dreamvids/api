<?php

class Response
{

    static $codes = [
        200 => "OK",
        400 => "Bad Request",
        401 => "Unauthorized",
        403 => "Forbidden",
        404=> "Not Found",
        405 => "Method not allowed",
        500 => "Internal Server Error"
    ];

    public $data = [];
    public $code = 200;

    public function __construct($data = [], $code = 200){
        $this->data = $data;
        $this->code = $code;

    }

    protected function renderHeaders(){
        header('Content-type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('HTTP/1.1 ' . $this->code . " " . self::$codes[$this->code]);
    }

    protected function renderBody(){
        echo json_encode($this, JSON_PRETTY_PRINT);
    }

    public function render(){
        $this->renderHeaders();
        $this->renderBody();
        exit();
    }
}