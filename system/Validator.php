<?php

class Validator{

    protected $rules = [];
    protected $errors = [];
    protected $data = [];

    const PARAM_REQUIRED = "required";
    const PARAM_REGEX = "regex";
    const PARAM_CUSTOM = "custom";
    const PARAM_TYPE = "type";
    const PARAM_SAME = "same";
    const PARAM_MIN_LENGTH = "min_length";
    const PARAM_MAX_LENGTH = "max_length";
    const PARAM_MESSAGES = "_messages";
    const PARAM_FILE_EXTENSION = "file_extension";
    const PARAM_FILE_SIZE = "file_size";

    const RULE_ALL = "_all";

    const TYPE_EMAIL = "email";
    const TYPE_FILE = "file";

    static $default_rule = [
        self::PARAM_REQUIRED => null,
        self::PARAM_REGEX => null,
        self::PARAM_CUSTOM => null,
        self::PARAM_TYPE => null,
        self::PARAM_SAME => null,
        self::PARAM_MIN_LENGTH => 0,
        self::PARAM_MAX_LENGTH => -1, //-1 means no limit
        self::PARAM_FILE_EXTENSION => [],
        self::PARAM_FILE_SIZE => -1, //No limit (except limit set in php.ini) (in bytes)
        self::PARAM_MESSAGES => []
    ];


    public function __construct(array $rules, array $data = null){
        $this->rules = $rules;
        $this->data = $data ?? $_POST+$_FILES;

        $this->fillEmptyParams();
    }

    /**
     * Validate all param
     * @return bool
     */
    public function validate(): bool{
        foreach($this->rules as $name => $rule){
            if($name == self::RULE_ALL){
                continue;
            }
            foreach(self::$default_rule as $paramName => $defaultValue){
                if(substr($paramName, 0, 1) === '_'){ // skip all params starting with "_"
                    continue;
                }
                if(!$this->checkRuleParam($name, $paramName)){
                    $this->errors[$name] = $this->getMessage($name, $paramName);
                    break;
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * @return array
     */
    public function getErrors(): array{
        return $this->errors;
    }

    /**
     * Validate one param of a rule
     * @param $name
     * @param $paramName
     * @return bool
     */
    protected function checkRuleParam(string $name, string $paramName): bool{
        $rule = $this->rules[$name];
        if($rule[$paramName] === self::$default_rule[$paramName]){ //If this rule's param is not changed, then it's automatically valid
            if(isset($this->rules[self::RULE_ALL][$paramName])){
                $rule = $this->rules[self::RULE_ALL];
            }else{
                return true;
            }
        }

        if(!isset($this->data[$name])){
            return !$rule[self::PARAM_REQUIRED];
        }

        switch($paramName){
            case self::PARAM_REQUIRED:
                return !$rule[$paramName] || (isset($this->data[$name]) && $this->data[$name] != '');
                break;
            case self::PARAM_REGEX:
                return preg_match($rule[$paramName], $this->data[$name]);
                break;
            case self::PARAM_CUSTOM: //Callback
                return $rule[$paramName]($this->data[$name], $this->data);
                break;
            case self::PARAM_TYPE: //Predefined verifications such as email
                return $this->handlePredefinedRule($rule[$paramName], $this->data[$name]);
                break;
            case self::PARAM_SAME:
                return isset($this->data[$rule[$paramName]]) && ($this->data[$rule[$paramName]] == $this->data[$name]);
                //Return true if this field has the same value as the given one
                break;
            case self::PARAM_MIN_LENGTH:
                return strlen($this->data[$name])>=$rule[$paramName];
                break;
            case self::PARAM_MAX_LENGTH:
                return $rule[$paramName] == -1 || strlen($this->data[$name])<=$rule[$paramName];
                break;
            case self::PARAM_FILE_SIZE:
                return $rule[$paramName] == -1 || $this->data[$name]['size'] <= $rule[$paramName];
                break;
            case self::PARAM_FILE_EXTENSION:
                $allowed = is_array($rule[$paramName]) ? $rule[$paramName] : [$rule[$paramName]]; //Array of extensions
                $extension = strtolower(pathinfo($this->data[$name]['name'], PATHINFO_EXTENSION));
                $this->data[$name]['extension'] = $extension;
                return in_array($extension, $allowed);
                break;
        }

        return false;
    }

    /**
     * Fill given rule with default values
     * @param $name
     */
    protected function fillRuleEmptyParams(string $name){
        $rule = $this->rules[$name];
        foreach(self::$default_rule as $rule_param_name => $rule_param){
            $rule[$rule_param_name] = $rule[$rule_param_name] ?? $rule_param;
        }
        $this->rules[$name] = $rule;
    }

    /**
     * Fill rules with default values
     */
    protected function fillEmptyParams(){
        foreach ($this->rules as $name => $rule) {
            $this->fillRuleEmptyParams($name);
        }
    }

    protected function getMessage(string $name, string $param): string{
        return $this->rules[$name][self::PARAM_MESSAGES][$param] ?? $this->rules[self::RULE_ALL][self::PARAM_MESSAGES][$param] ?? $param;
    }


    protected function handlePredefinedRule(string $type, $value): bool{
        switch($type){
            case self::TYPE_EMAIL:
                return filter_var($value, FILTER_VALIDATE_EMAIL);
                break;
            case self::TYPE_FILE:
                return isset($value['name'], $value['type'], $value['size'], $value['tmp_name'], $value['error']) && $value['error'] == UPLOAD_ERR_OK && is_uploaded_file($value['tmp_name']);
                break;
        }

        return false;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function data(string $key){
        $temp = $this->data;
        $keys = explode('.',$key);

        foreach ($keys as $key) {
            $temp = $temp[$key] ?? null;
        }

        return $temp;
    }

}
