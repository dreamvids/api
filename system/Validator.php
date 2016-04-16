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

    const RULE_ALL = "_all";

    const TYPE_EMAIL = "email";

    static $default_rule = [
        self::PARAM_REQUIRED => null,
        self::PARAM_REGEX => null,
        self::PARAM_CUSTOM => null,
        self::PARAM_TYPE => null,
        self::PARAM_SAME => null,
        self::PARAM_MIN_LENGTH => 0,
        self::PARAM_MAX_LENGTH => -1, //-1 means no limit
        self::PARAM_MESSAGES => []
    ];


    public function __construct(array $rules, array $data){
        $this->rules = $rules;
        $this->data = $data;

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
        if($rule[$paramName] === null){ //If this rule's param is not changed, then it's automatically valid
            if(isset($this->rules[self::RULE_ALL][$paramName])){
                $rule = $this->rules[self::RULE_ALL];
            }else{
                return true;
            }
        }

        if(!isset($this->data[$name]) && $paramName != self::PARAM_REQUIRED){ // Avoid undefined indexes
            return false;
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


    protected function handlePredefinedRule(string $type, string $value): bool{
        switch($type){
            case self::TYPE_EMAIL:
                return filter_var($value, FILTER_VALIDATE_EMAIL);
                break;
        }

        return false;
    }

}
