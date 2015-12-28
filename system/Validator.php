<?php

class Validator{

    protected $rules = [];
    protected $errors = [];
    protected $data = [];

    static $default_rule = [
        'required' => null,
        'regex' => null,
        'custom' => null,
        'type' => null,
        'same' => null,
        '_messages' => []
    ];

    const TYPE_EMAIL = "email";

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
            if($name == '_all'){
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
            if(isset($this->rules['_all'][$paramName])){
                $rule = $this->rules['_all'];
            }else{
                return true;
            }
        }

        switch($paramName){
            case 'required':
                return !$rule['required'] || (isset($this->data[$name]) && $this->data[$name] != '');
                break;
            case 'regex':
                return preg_match($rule['required'], $this->data[$name]);
                break;
            case 'custom': //Callback
                return $rule['custom']($this->data[$name]);
                break;
            case 'type': //Predefined verifications such as email
                return $this->handlePredefinedRule($rule['type'], $this->data[$name]);
                break;
            case 'same':
                return isset($this->data[$rule['same']]) && ($this->data[$rule['same']] == $this->data[$name]);
                //Return true if this field has the same value as the given one
                break;
        }
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
        return $this->rules[$name]['_messages'][$param] ?? $this->rules['_all']['_messages'][$param] ?? '';
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