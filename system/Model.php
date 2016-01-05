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
	const BELONGS_TO_MANY = "belongs_to_many";

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
		$req = DB::get()->prepare("SELECT * FROM ".static::$table_name." WHERE ".$col." = ? LIMIT 1");
		$req->execute([$value]);
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
		$req = DB::get()->prepare("SELECT * FROM ".static::$table_name." WHERE ".$col." = ?");
		$req->execute([$value]);
		$return = [];
		$classname = get_called_class();
		foreach($req->fetchAll() as $row){
			$return[] = new $classname($row['id'], $row, $options);
		}

		return $return;
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
	public function getAssociated(string $targetClassName, string $associationType, array $options = []){

		switch($associationType){
			case self::BELONGS_TO:
				$field_name = $options['field_name'] ?? str_replace(["dv_", 'api_'], '', $targetClassName::$table_name).'_id';
				return $targetClassName::getBy('id', $this->$field_name, $options);
			break;
			case self::HAS_MANY:
				$field_name = str_replace(["dv_", 'api_'], '', static::$table_name).'_id';
				$return = $targetClassName::getAllBy($field_name, $this->id, $options);
				return $return;
			break;
		}
		return null;
	}

	protected function autoLoadAssociations($options = []){
		foreach(static::$associations as $name => $association){
			$autoload = $association['autoload'] ?? false;
			if($autoload){
				$this->loadAssociation($name);
			}
		}
	}

	/**
	 * @return $this
	 */
	public function loadAssociation(string $name){
		if(isset(static::$associations[$name])){
			$association = static::$associations[$name];
			$this->$name = $this->getAssociated($association['class_name'], $association['type'], $association);
		}
		return $this;
	}
}