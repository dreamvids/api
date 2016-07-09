<?php

class TableRegistry
{

    /**
     * @var Table
     */
    protected static $instances = [];

    /**
     * @return Table
     */
    public static function get($className)
    {
        if(!isset(self::$instances[$className])){
            $tableClass = self::entityToTable($className);
            self::$instances[$className] = new $tableClass;
            self::$instances[$className]->initialize();
        }
        return static::$instances[$className];
    }
    
    public static function entityToTable($entityClassName){
        return $entityClassName.'Table';
    }
}