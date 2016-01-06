<?php
abstract class Model {
	static $table_name = '';

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

	static $associations = [];

	public static function fetchAll(array $options = []): array {
		$model = get_called_class();
		$req = DB::get()->query("SELECT * FROM ".static::$table_name);
		$tab = [];
		while ($rep = $req->fetch()) {
			$tab[] = new $model($rep['id'], $rep, $options);
		}
		$req->closeCursor();
		
		return $tab;
	}

	public static function insertIntoDb(array $args): int {
		$q_args = [];
		foreach ($args as $a) {
			$q_args[] = '?';
		}
		$str = implode(', ', $q_args);
		$req = DB::get()->prepare("INSERT INTO ".static::$table_name." VALUES('', $str)");
		$req->execute($args);
		return DB::get()->lastInsertId();
	}

	public static function exists(string $col, $value): bool {
		$req = DB::get()->prepare("SELECT COUNT(*) AS nb FROM ".static::$table_name." WHERE ".$col." = ?");
		$req->execute([$value]);
		$data = $req->fetch();
		$req->closeCursor();
		return ($data['nb'] > 0);
	}

	/**
	 * @param string $col
	 * @param $value
	 * @param array $options
	 * @return Model|null
	 */
	public static function getBy(string $col, $value, array $options = []) {

        if(is_array($value)){
            $value_str = 'IN ' . self::generateValueListString(count($value));
        }else{
            $value_str = '= ?';
            $value = [$value];
        }

		$req = DB::get()->prepare("SELECT * FROM ".static::$table_name." WHERE ".$col." $value_str LIMIT 1");
		$req->execute($value);
		$data = $req->fetch();
		$req->closeCursor();
		$classname = get_called_class();

		if($data === false){
			return null;
		}else{
			return new $classname($data['id'], $data, $options);
		}
	}

	public static function getAllBy(string $col, $value, array $options = []){
        if(is_array($value)){
            $value_str = 'IN ' . self::generateValueListString(count($value));
        }else{
            $value_str = '= ?';
            $value = [$value];
        }

        $req = DB::get()->prepare("SELECT * FROM ".static::$table_name." WHERE ".$col." $value_str");
		$req->execute($value);
		$return = [];
		$classname = get_called_class();
		foreach($req->fetchAll() as $row){
			$return[] = new $classname($row['id'], $row, $options);
		}

		return $return;
	}

    protected static function generateValueListString($size): string{
        $str = str_repeat('?,', $size);
        $str = trim($str, ',');
        return '(' . $str . ')';
    }

	protected function requestDb(int $id): array {
		$req = DB::get()->prepare("SELECT * FROM ".static::$table_name." WHERE id = ?");
		$req->execute([$id]);
		$data = $req->fetch();
		$req->closeCursor();
		return $data;
	}

	protected function saveToDb(int $id, array $cols, array $values) {
		$q_args = [];
		foreach ($cols as $c) {
			$q_args[] = $c.'=?';
		}
		$str = implode(', ', $q_args);
		array_push($values, $id);

		$req = DB::get()->prepare("UPDATE ".static::$table_name." SET $str WHERE id=?");
		$req->execute($values);
	}

	protected function removeFromDb(int $id) {
		$req = DB::get()->prepare("DELETE FROM ".static::$table_name." WHERE id = ?");
		$req->execute([$id]);
	}

	/**
	 * @param string $targetClassName The name of the associated class
	 * @param string $associationType The type of the link
	 * @param array $options Options
	 * @return Model|null
	 */
	protected function getAssociated(string $targetClassName, string $associationType, array $options = []){
        $association_options = $options['association'] ?? [];
        unset($options['association']);
        $return = null;
        if(isset($this->{$association_options['name']})){
            $object = $this->{$association_options['name']};
            if(is_array($object)){
                foreach($object as $element){
                    $element->autoLoadAssociations($options);
                }
            }else{
                $object->autoLoadAssociations($options);
            }
            return $this->{$association_options['name']};
        }
		switch($associationType){
			case self::BELONGS_TO:
				$field_name = $association_options['field_name'] ?? str_replace(["dv_", 'api_'], '', $targetClassName::$table_name).'_id';
				$return = $targetClassName::getBy('id', $this->$field_name, $options);
			break;
            case self::MANY_TO_MANY:
                $throughClassName = $association_options['through'];
                $self_field_name = str_replace(["dv_", 'api_'], '', static::$table_name).'_id';
                $foreign_field_name = str_replace(["dv_", 'api_'], '', $targetClassName::$table_name).'_id';
                $ids = $throughClassName::getAllBy($self_field_name,$this->id, $options);
                foreach($ids as $k => $id){
                    $ids[$k] = $id->$foreign_field_name;
                }
                $return = $targetClassName::getAllBy('id', $ids, $options);
                break;
            case self::HAS_ONE:
                $field_name = $association_options['field_name'] ?? str_replace(["dv_", 'api_'], '', self::$table_name).'_id';
                $return = $targetClassName::getBy($field_name, $this->id, $options);
            break;
			case self::HAS_MANY:
				$field_name = $association_options['field_name'] ?? str_replace(["dv_", 'api_'], '', static::$table_name).'_id';
				$return = $targetClassName::getAllBy($field_name, $this->id, $options);
			break;
		}
		return $return;
	}

	protected function autoLoadAssociations(array $options = []){
		if(!empty($options)){
			$options_array = $this->getNextAssociationToLoad($options);
			$options = $options_array['options'];
			$next = $options_array['next'];
			$this->loadAssociation($next, $options);
		}else{
			foreach(static::$associations as $name => $association){
				$autoload = $association['autoload'] ?? false;
				if($autoload){
					$this->loadAssociation($name);
				}
			}
		}
	}

	protected function getNextAssociationToLoad(array $list): array{
		$next = '';
		foreach($list as $k => $value){
			if(empty($value)){
                unset($list[$k]);
				continue;
			}else{
				$to_load = explode('.', $value);
				$next = array_shift($to_load);
				$list[$k] = implode('.', $to_load);
				break;
			}
		}
		return [
			'next' => $next,
			'options' => $list
		];
	}


	public function loadAssociation($name, array $options = []){
		if(isset(static::$associations[$name])){
			$association = static::$associations[$name];
            $options['association'] = $association;
            $options['association']['name'] = $name;
			$this->$name = $this->getAssociated($association['class_name'], $association['type'], $options);
		}
		return $this;
	}

	public function loadAssociations(array $options = []){
		foreach($options as $option){
			$options_array = $this->getNextAssociationToLoad([$option]);
			$this->loadAssociation($options_array['next'], $options_array['options']);
		}
		return $this;
	}
}