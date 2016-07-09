<?php

abstract class Table
{

    /**
     * @var string Table name without prefix
     */
    protected static $table_name = "";

    /**
     * @var string Table prefix
     */
    protected static $prefix = "dv";

    protected $associations = [];

    /**
     * A user belongs to a rank
     * @var array
     */
    const BELONGS_TO = "belongs_to";

    /**
     * A user has one channel (Main channel)
     * @var array
     */
    const HAS_ONE = "has_one";

    /**
     * A channel has many videos
     * @var array
     */
    const HAS_MANY = "has_many";

    /**
     * A channel belongs multiple users (admin) and a user has multiple channels
     * @var array
     */
    const MANY_TO_MANY = "many_to_many";

    public abstract function initialize();

    public static function get(){
        return TableRegistry::get(substr(get_called_class(), 0, -5));
    }

    protected function hasOne(string $foreignClassName, string $varName = "", string $foreignFieldName = ""){
        if($varName == ""){
            $varName = strtolower($foreignClassName);
        }

        if($foreignFieldName == ""){
            $tableClass = TableRegistry::entityToTable($foreignClassName);
            $foreignFieldName = $tableClass::getTableName() . '_id';
        }

        $type = self::HAS_ONE;
        $this->associations[$varName] = [
            'associatedClassName' => $foreignClassName,
            'associatedFieldName' => $foreignFieldName,
            'type' => $type
        ];

        return $this;
    }

    protected function hasMany(string $foreignClassName, string $varName = "", string $foreignFieldName = ""){
        if($varName == ""){
            $varName = strtolower($foreignClassName);
        }

        if($foreignFieldName == ""){
            $tableClass = TableRegistry::entityToTable($foreignClassName);
            $foreignFieldName = $tableClass::getTableName() . '_id';
        }

        $type = self::HAS_MANY;
        $this->associations[$varName] = [
            'associatedClassName' => $foreignClassName,
            'associatedFieldName' => $foreignFieldName,
            'type' => $type
        ];
        return $this;
    }

    protected function belongsTo(string $foreignClassName, string $varName = "", $fieldName = ""){
        if($varName == ""){
            $varName = strtolower($foreignClassName);
        }

        if($fieldName == ""){
            $tableClass = TableRegistry::entityToTable($foreignClassName);
            $fieldName = $tableClass::getTableName() . '_id';
        }


        $type = self::BELONGS_TO;
        $this->associations[$varName] = [
            'associatedClassName' => $foreignClassName,
            'associatedFieldName' => $fieldName,
            'type' => $type
        ];

        return $this;
    }

    protected function hasManyToMany(string $foreignClassName,  string $varName = "", string $joinClass = "", string $localFieldName = "", string $foreignFieldName = ""){
        if($varName == ""){
            $varName = strtolower($foreignClassName);
        }

        if($joinClass == ""){
            $joinClass = static::class.$foreignClassName;
        }

        if($localFieldName == ""){
            $localFieldName = static::getTableName() . '_id';
        }

        if($foreignFieldName == ""){
            $tableClass = TableRegistry::entityToTable($foreignClassName);
            $foreignFieldName = $tableClass::getTableName() . '_id';
        }


        $type = self::MANY_TO_MANY;
        $this->associations[$varName] = [
            'associatedClassName' => $foreignClassName,
            'foreignFieldName' => $foreignFieldName,
            'localFieldName' => $localFieldName,
            'throughClass' => $joinClass,
            'type' => $type
        ];

        return $this;
    }

    /**
     * TODO
     */
    protected function fetchAssociation($varName){
    }

    /**
     * @return string
     */
    public static function getTableName()
    {
        return static::$table_name;
    }

    /**
     * @return array
     */
    public function getAssociations()
    {
        return $this->associations;
    }


}