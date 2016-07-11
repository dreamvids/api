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

    /**
     * @var $connection DB
     */
    protected $connection = null;
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

    public function __construct(){
        $this->connection = DB::get();
    }

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
     * @return string
     */
    public static function getPrefix()
    {
        return self::$prefix;
    }

    /**
     * @return string
     */
    public static function getFullName(){
        return static::getPrefix().'_'.static::getTableName();
    }

    /**
     * @return array
     */
    public function getAssociations()
    {
        return $this->associations;
    }

    public function newQuery($options = []){
        return new Query($this, $options);
    }

    public function find($finder = 'all', $options = []){
        $methodName = 'find'.ucfirst($finder);
        if(method_exists($this, $methodName)){
            $query = $this->newQuery($options);
            $this->$methodName($query);
            return $query;
        }else{
            throw new Exception('Method \'find\'.ucfirst($finder) does not exists in ' . get_called_class());
        }
    }

    public function count($where){
        return $this->newQuery()->where($where)->count();
    }

    public function exists($where){
        return $this->count($where) > 0;
    }

    public function findAll(Query $query, $options = []){
        return $query->select("*")->options($options);
    }

    public function execute(Query $query){
        $stmt = $this->connection->prepare($query);
        if(!$stmt->execute($query->getData())){
            throw new PDOException(implode(' ,', $stmt->errorInfo()), $stmt->errorCode());
        }
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::getEntityName());
        return $stmt->fetchAll();
    }

    public static function getEntityName(){
        return substr(get_called_class(), 0, -5);
    }

}