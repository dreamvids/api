<?php

class Query
{
    /**
     * @var $table Table
     */
    protected $table = null;
    protected $options = [];
    protected $data = [];

    public function __construct(Table $table, $options = []){
        $this->table = $table;
        $this->options($options);
    }

    public function options($options = [], $overwrite = false){
        if($overwrite){
            $this->options = $options;
        }else{
            $this->options = array_merge($this->options, $options);
        }

        return $this;
    }

    public function select($what = "*"){
        $this->options['select'] = $what;

        return $this;
    }

    public function count(){
        return $this->select('COUNT(*) as count')->first('count');
    }

    public function loadAssociations($associations = []){
        return $this->options(['associations' => $associations]);
    }

    public function where($where){
        $this->options['where'][] = $where;
        return $this;
    }

    public function limit($limit){
        $this->options['limit'] = $limit;
        return $this;
    }

    protected function generateWhereStatement(){
        $query = "";
        $data = [];
        if(isset($this->options['where'])){
            $stmt = "WHERE ";
            $orParts = [];
            foreach ($this->options['where'] as $conditions){
                $andParts = [];
                foreach ($conditions as $field => $value){
                    if(is_array($value)){

                        $inString = str_repeat('?', count($value));
                        $inString = implode(', ', str_split($inString));
                        $andParts[] = $field . "  IN (".$inString.")";
                        $data = array_merge($data, $value);
                    }else{
                        if(strtoupper(substr($field, -5, 5)) == ' LIKE'){
                            $andParts[] = $field . " ?";
                        }else{
                            $andParts[] = $field . " = ?";
                        }
                        $data[] = $value;
                    }
                }
                $orParts[] = "(".implode(" AND ", $andParts).")";
            }
            $stmt .= implode(" OR ", $orParts);
            $query = $stmt;
        }

        return ['query' => $query, 'data' => $data];
    }

    /**
     * @return string
     */
    public function getQuery(){
        $data = [];
        $query = "SELECT ";
        $query .= is_array($this->options['select']) ? implode(", ", $this->options['select']) : $this->options['select'] . ' FROM ';
        $query .= $this->table->getFullName() . ' ';

        $where = $this->generateWhereStatement();
        $query .= $where['query'];
        $data += $where['data'];

        $this->data = $data;
        return $query;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    public function __toString()
    {
        return $this->getQuery();
    }

    public function execute(){
        return $this->table->execute($this);
    }

    public function first($field = null){
        $result = $this->limit(1)->execute();
        return ($field ? $result[0]->$field : $result[0]) ?? null;
    }

    public function toArray(){
        return $this->execute();
    }

}