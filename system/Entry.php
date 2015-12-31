<?php
abstract class Entry extends Model {

	protected $fields = [];

	public function __construct(int $id) {
   		$data = $this->requestDb($id);
   		foreach ($data as $key => $value) {
   			if (!is_numeric($key) ) {
   				$this->$key = $value;
				$this->fields[] = $key;
   			}
   		}
	}

	public function save() {
	    if (isset($this->id)) {
	    	$attr = $this->fields;
    		unset($attr['id']);
    		$cols = array_keys($attr);
    		$args = array_values($attr);
    		$this->saveToDb($this->id, $cols, $args);
	    }
	}

	public function delete() {
	    if (isset($this->id)) {
    		$this->removeFromDb($this->id);
	    }
	}
}