<?php
class System {
    private static $instance = null;

    private $name;
    private $root;
    private $webroot;
    private $system;
    private $app;
    private $api_models;
    private $dv_models;
    private $views;
    private $controllers;
    private $assets;
    private $css;
    private $js;
    private $fonts;
    private $img;

    private $dependencies;
    private $dependenciesPaths;

    private function __construct() {
        $this->name = 'API';

        // Back-End
        $this->root = str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']);
        $this->system = $this->root.'system/';
        $this->app = $this->root.'app/';
        $this->api_models = $this->app.'models/api/';
        $this->dv_models = $this->app.'models/dv/';
        $this->views = $this->app.'views/';
        $this->controllers = $this->app.'controllers/';

        // Front-End
        $this->webroot = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
        $this->assets = $this->webroot.'assets/';
        $this->css = $this->webroot.'css/';
        $this->js = $this->webroot.'js/';
        $this->fonts = $this->webroot.'fonts/';
        $this->img = $this->webroot.'img/';

        $this->dependencies = [
            'system' => [
                'Database',
                'ModelInterface',
                'Model',
                'Entry',
                'Utils',
                'Request',
                'Data',
                'HTTPError'
            ],
            'api_models' => [
                'APIClient',
                'APIController',
                'APIPermission',
                'APIRank',
                'APIToken'
            ]
        ];

        $this->dependenciesPaths = [
            'system' => $this->getSystem(),
            'api_models' => $this->getApiModels()
        ];
    }

    public static function get(): System {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getName() {
        return $this->name;
    }

    public function getRoot() {
        return $this->root;
    }

    public function getWebroot() {
        return $this->webroot;
    }

    public function getSystem() {
        return $this->system;
    }

    public function getApp() {
        return $this->app;
    }

    public function getApiModels() {
        return $this->api_models;
    }

    public function getDvModels() {
        return $this->dv_models;
    }

    public function getViews() {
        return $this->views;
    }

    public function getControllers() {
        return $this->controllers;
    }

    public function getAssets() {
        return $this->assets;
    }

    public function getCss() {
        return $this->css;
    }

    public function getJs() {
        return $this->js;
    }

    public function getFonts() {
        return $this->fonts;
    }

    public function getImg() {
        return $this->img;
    }

    /**
     * @param $type array|string
     */
    public function loadDependencies($type){
        if(is_array($type)){
            foreach($type as $value){
                $this->loadDependencies($value);
            }
        }else{
            if(isset($this->dependencies[$type])){
                foreach($this->dependencies[$type] as $className){
                    require_once $this->dependenciesPaths[$type].$className.'.php';
                }
            }
        }
    }
}
