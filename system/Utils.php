<?php
class Utils {
	public static function time(): int {
		$jet_lag = 0;
		return time() + (3600 * $jet_lag); 
	}

	public static function generateId(int $length = 6): string {
		$str = '0aN1zB2eV3rC4tX5yW6uM7iL8oK9pJ0qH1sG2dF3fD4gS5hQ6jP7kO8lI9mU0wY1xT2cR3vE4bZ5nA6';
		return substr(str_shuffle($str), 0, $length);;
	}
}