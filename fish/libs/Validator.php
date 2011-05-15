<?
class Validator
{
	static public $patterns = array (
		'alphaNumeric' => '[0-9A-Z]+',
		'number' => '-?[0-9]*(\.[0-9]+)?',
		'int' => '-?[0-9]+',
		'decimal' => '-?[0-9]*\.[0-9]+',
		'email' => '[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}',
		'ipv4' => '([0-9]{1,3}.){3}[0-9]{1,3}',
		'phone' => '(?:\+?1)?[-. ]?\\(?[2-9][0-8][0-9]\\)?[-. ]?[2-9][0-9]{2}[-. ]?[0-9]{4}'

	);

	static function addPattern($name, $regex)
	{
		self::$patterns[$name] = $regex;
	}

	static function validate($text,$expression)
	{
		if (isSet(self::$patterns[$expression])) $expression = self::$patterns[$expression];

		$expression = '/^' . $expression . '$/i';

		if (preg_match($expression, $text)) return $text;

		return false;
	}

	static function alphaNumeric($text)
	{
		return self::validate($text, 'alphaNumeric');
	}

	static function number($text)
	{
		return self::validate($text, 'number');
	}

	static function int($text)
	{
		return self::validate($text, 'int');
	}

	static function decimal($text)
	{
		return self::validate($text, 'decimal');
	}

	static function between($text, $min, $max)
	{
		if ($text >= $min && $text <= $max) return $text;

		return false;
	}

	static function notEmpty($text)
	{
		if (strlen($text)) return $text;

		return false;
	}

	static function bool($text)
	{
		return (bool) $text;
	}

	static function email($text)
	{
		return self::validate($text, 'email');
	}

	static function equal($text, $value)
	{
		return ($text == $value) ? $text : false;
	}

	static function extension($text, $extensions = array ('gif', 'jpg', 'jpeg', 'png'))
	{
		$extension = explode('.',$text);
		$extension = end($extension);
		$extension = strtolower($extension);

		return self::in($extension, $extensions);
	}

	static function ipv4 ($text)
	{
		return self::validate($text, 'ipv4');
	}

	static function maxLength($text, $length)
	{
		if (strlen($text) <= $length) return $text;

		return false;
	}

	static function minLength($text, $length)
	{
		if (strlen($text) >= $length) return $text;

		return false;
	}

	static function in($text, $values)
	{
		if (in_array($text, $values)) return $text;

		return false;
	}

	static function phone($text)
	{
		return self::validate($text, 'phone');
	}

	static function validateFields($fields, & $values)
	{
		$return = array();
		foreach ($fields as $name => $function)
		{
			$function = explode(',',$function);

			$func = $function[0];

			$function[0] = '$values["' . $name . '"]';

			$variables = implode(',',$function);

			$exec = "\$return['$name'] = self::$func($variables);" . PHP_EOL;
			eval($exec);
		}

		return $return;
	}
}
?>
