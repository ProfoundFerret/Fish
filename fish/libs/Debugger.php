<?
class Debugger
{
	static private $starts = array();

	static function trace()
	{
		$backtrace = debug_backtrace();

		$texts = array();
		foreach ($backtrace as $info)
		{
			$text = $info['file'] . ':' . $info['line'];
			$text .= ' ' . $info['static function'];
		}

		return $texts;
	}

	static function log($var)
	{
		$date = date('r');
		$var = print_r($var, true);

		$fish = getFish();
		$routes = $fish->router()->routes();
		$query = implode('/',$routes);

		$text = $date . ' [ ' . $query . ' ] ' . $var . PHP_EOL;

		file_put_contents(kPREFIX . 'etc/log',$text,FILE_APPEND);
	}

	static function logTrace()
	{
		self::log(self::trace());
	}

	static function startTimer()
	{
		$index = array_push(self::$starts, microtime(true));

		return $index - 1;
	}

	static function timerTime($index = false)
	{
		if ($index === false) $index = count(self::$starts) - 1;
		if (! isSet(self::$starts[$index])) return -1;

		$time = microtime(true);

		$oldTime = self::$starts[$index];

		$length = $time - $oldTime;

		return $length;
	}
}
?>
