<?php
trait ExistsTrait {

    public static function exists(){
        $exists = false;
        $mainModelName = self::getModelName();
        $exists = $mainModelName::exists('id', Request::get()->getArg(2));
        Response::get()->addData('exists', $exists);
    }

    private static function getModelName(): string{
        $remove = "Ctrl";
        $className = substr(get_called_class(), 0, -strlen($remove));

        if(class_exists($className) && is_subclass_of($className, 'Model')){
            return $className;
        }else{
            Response::get()->addError("exists", "Model not found");
            HTTPError::NotFound()->render();
            return "";
        }
    }

}