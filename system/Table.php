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

    protected static $executedQueries = [];

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

    public function getById($id){
        return $this->find()->where(['id' => $id])->first();
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
            $foreignFieldName = static::getTableName() . '_id';
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
            'joinClass' => $joinClass,
            'type' => $type
        ];
        return $this;
    }

    protected function fetchAssociation($varName, $entity, $associationsToLoad = []){
        $associationSettings = $this->associations[$varName];
        $table = TableRegistry::get($associationSettings['associatedClassName']);
        switch ($associationSettings['type']) {
            case self::HAS_ONE:
                $associatedEntity = $table->find()->where([$associationSettings['associatedFieldName'] => $entity->id ])->loadAssociations($associationsToLoad)->first();
                $entity->$varName = $associatedEntity;
                break;
            case self::BELONGS_TO:
                $associatedEntity = $table->find()->where(['id' => $entity->{$associationSettings['associatedFieldName']} ])->loadAssociations($associationsToLoad)->first();
                $entity->$varName = $associatedEntity;
                break;
            case self::HAS_MANY:
                $associatedEntity = $table->find()->where([$associationSettings['associatedFieldName'] => $entity->id])->loadAssociations($associationsToLoad)->toArray();
                $entity->$varName = $associatedEntity;
                break;
            case self::MANY_TO_MANY:
                $joinTable = TableRegistry::get($associationSettings['joinClass']);
                $ids = $joinTable->find()->asList($associationSettings['foreignFieldName'])->where([$associationSettings['localFieldName'] => $entity->id])->toArray();
                $associatedEntities = $table->find()->where(['id' => $ids])->loadAssociations($associationsToLoad)->toArray();
                $entity->$varName = $associatedEntities;
                break;
        }
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return static::$table_name;
    }

    /**
     * @return string
     */
    public static function getPrefix(): string
    {
        return self::$prefix;
    }

    /**
     * @return string
     */
    public static function getFullName(): string{
        return static::getPrefix().'_'.static::getTableName();
    }

    /**
     * @return array
     */
    public function getAssociations(): array
    {
        return $this->associations;
    }

    public function newQuery($options = []): Query{
        return new Query($this, $options);
    }

    public function find($finder = 'all', $options = []): Query{
        $methodName = 'find'.ucfirst($finder);
        if(method_exists($this, $methodName)){
            $query = $this->newQuery($options);
            $this->$methodName($query);
            return $query;
        }else{
            throw new Exception('Method '. $methodName .'(Query $query, $options = []) does not exists in ' . get_called_class());
        }
    }

    public function count($where): int{
        return $this->newQuery()->where($where)->count();
    }

    public function exists($where): bool{
        return $this->count($where) > 0;
    }

    public function findAll(Query $query, $options = []){
        return $query->select("*")->options($options);
    }

    public function findList(Query $query, $options = []){
        return $this->findAll($query, $options)->asList('id');
    }

    /**
     * @return array
     */
    public static function getExecutedQueries()
    {
        return self::$executedQueries;
    }

    public function execute(Query $query){
        self::$executedQueries[] = (string)$query;
        $stmt = $this->connection->prepare($query);
        if(!$stmt->execute($query->getData())){
            throw new PDOException(implode(' ,', $stmt->errorInfo())/*, $stmt->errorCode()*/);
        }
        if($query->isHydrated()){
            $stmt->setFetchMode(PDO::FETCH_CLASS, self::getEntityName());
        }else{
            $stmt->setFetchMode(PDO::FETCH_COLUMN, 0);
        }
        $entities = $stmt->fetchAll();
        foreach ($query->getAssociationsToLoad() as $association){
            foreach($entities as $entity){
                $associationParts = explode('.', $association);
                $association = array_shift($associationParts);
                $this->fetchAssociation($association, $entity, $associationParts);
            }
        }

        return $entities;
    }

    public static function getEntityName(): string{
        return substr(get_called_class(), 0, -5);
    }

}