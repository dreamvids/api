<?php

class Config
{
    private $config = [];
    private $configRoot = "";
    private static $instance;

    public static function get(): Config{
        if(!self::$instance){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->configRoot = System::get()->getConfig();
        $this->loadAll();
    }

    /**
     * Read a key from the config file, for instance a key in the "config.php" config file : Config::get()->read("config.debug")
     * OR in database.php : Config::get()->read("config.username"), Config::get()->read("database.*") etc..
     * @param string $key
     * @return array|mixed|null
     */
    public function read(string $key = "*"){
        $temp = $this->config;
        $keyParts = explode('.', $key);

        foreach ($keyParts as $keyPart) {
            if($keyPart == "*"){
                return $temp;
            }else{
                $temp = $temp[$keyPart] ?? null;
            }

            if(is_null($temp)){
                break;
            }
        }

        return $temp;
        
    }

    protected function loadAll(){
        $filesList = glob($this->configRoot . '*.php');
        foreach ($filesList as $path) {
            $name = substr(pathinfo($path, PATHINFO_BASENAME), 0, -4);
            $this->load($path, $name);
        }
    }

    protected function load($path, $name){
        $this->config[$name] = include $path;
    }

}