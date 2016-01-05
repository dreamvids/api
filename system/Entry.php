<?php
abstract class Entry extends Model {

	protected $fields = [];

	public function __construct(int $id, $data = [], array $options = []) {
   		$data = empty($data) ? $this->requestDb($id) : $data;
   		foreach ($data as $key => $value) {
   			if (!is_numeric($key) ) {
   				$this->$key = $value;
				$this->fields[] = $key;
   			}
   		}

		$this->autoLoadAssociations($options);
	}

	public function save() {
	    if (isset($this->id)) {
	    	$attr = $this->fields;
			$args = [];
			foreach($attr as $k => $field){
				if($field == 'id'){
    				unset($attr[$k]);
					continue;
				}
				$args[$field] = $this->$field;
			}

			$args = array_values($args);
    		$this->saveToDb($this->id, $attr, $args);
	    }
	}

	public function delete() {
	    if (isset($this->id)) {
    		$this->removeFromDb($this->id);
	    }
	}
}