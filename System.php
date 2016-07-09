<?php
class System {
    private static $instance = null;

    private $name;
    private $root;
    private $webroot;
    private $system;
    private $app;
    private $config;
    private $api_models;
    private $dv_models;
    private $views;
    private $controllers;
    private $traits;
    private $assets;
    private $css;
    private $js;
    private $fonts;
    private $img;

    private $dependencies;
    private $dependenciesPaths;
//TODO change models loading system
    private function __construct() {
        $this->name = 'API';

        // Back-End
        $this->root = __DIR__ . DIRECTORY_SEPARATOR;
        $this->system = $this->root.'system/';
        $this->app = $this->root.'app/';
        $this->config = $this->app.'config/';
        $this->api_models = $this->app.'models/api/';
        $this->dv_models = $this->app.'models/dv/';
        $this->views = $this->app.'views/';
        $this->controllers = $this->app.'controllers/';
        $this->api_models = $this->app.'models/api/';
        $this->traits = $this->app.'traits/';
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
                'Model',
                'Entry',
                'Utils',
                'Request',
                'HTTPError',
                'Response',
                'Validator',
                'PasswordManager',
                'Dispatcher',
                'ControllerInterface',
                'Config',
                'Functions',
                'Table',
                'TableRegistry'
            ],
            'api_models' => [
                'APIClient',
                'APIController',
                'APIPermission',
                'APIRank',
                'APIToken'
            ],
            'dv_models' => [
                'Rank',
                'Session',
                'User',
                'Channel',
                'Video',
                'Visibility',
                'Comment',
                'ChannelAdmin'
            ],
            'traits' => [
                'FlagTrait',
                'FlushTrait',
                'ExistsTrait'
            ]
        ];

        $this->dependenciesPaths = [
            'system' => $this->getSystem(),
            'traits' => $this->getTraits(),
            'api_models' => $this->getApiModels(),
            'dv_models' => $this->getDvModels()
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

    public function getConfig() {
        return $this->config;
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

    public function getTraits(){
        return $this->traits;
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
            switch ($type){

                case 'dv_models':
                    foreach($this->dependencies[$type] as $className){
                        require_once $this->dependenciesPaths[$type].'Tables/'.$className.'Table.php';
                        require_once $this->dependenciesPaths[$type].'Entities/'.$className.'.php';
                    }
                    break;
                default:
                    if(isset($this->dependencies[$type])){
                        foreach($this->dependencies[$type] as $className){
                            require_once $this->dependenciesPaths[$type].$className.'.php';
                        }
                    }
                    break;
            }
        }
    }
}
