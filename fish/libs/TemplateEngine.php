<?
class TemplateEngine
{
	private static $callback;

	static function loadFile($file)
	{
		if (! CacheControl::cacheUpToDate($file)) self::updateCacheFile($file);

		include_once CacheControl::fileLocation($file);
	}

	private static function updateCacheFile($file)
	{
		$contents = self::parseFile($file);

		CacheControl::writeFile($file, $contents);
	}

	private static function parseFile($file)
	{
		$ext = Util::fileExtension($file);
		$contents = file_get_contents($file);

		if ($ext == 'php') $contents = self::parsePHP($contents);

		return $contents;
	}

	private static function parsePHP($contents)
	{

		$kVARIABLE = '(\$[a-zA-Z_][a-zA-Z0-9_]*(\[[\'"]?[a-zA-Z0-9]+[\'"]?\])*)';
		$kDOT_NOTATION_VARIABLE = '\$[a-zA-Z_][a-zA-Z0-9-_]+(\.[a-zA-Z0-9]+)+';
		$kSOMETHING = '([^}]+)';
		$kNUMBER = '(-?[0-9]*(\.[0-9]+)?)';
		$kNAME = '([a-zA-Z_][a-zA-Z0-9_]*)';
		$kSTRING = '([\'"].*[\'"])';
		
		$contents = preg_replace_callback('/' . $kDOT_NOTATION_VARIABLE . '/', __CLASS__ . '::convertVariable', $contents);
		$contents = self::getGlobals($contents,true) . $contents;

		$search = array(
			"{ *$kSOMETHING *}" => "$1",
			"for $kVARIABLE in $kVARIABLE" => " foreach ($3 as $1) {",
			"for $kVARIABLE *(,|=>) *$kVARIABLE in $kVARIABLE" => " foreach($6 as $1 => $4) {",
			"for $kVARIABLE *= *$kSOMETHING ?, ?$kSOMETHING" => "foreach (range($3,$4) as $1) {",
			"for $kVARIABLE *= *$kSOMETHING ?, ?$kSOMETHING ?, ?$kNUMBER" => "foreach (range($3,$4,$5) as $1) {",
			"if $kSOMETHING" => " if ($1) {",
			"while $kSOMETHING" => " while ($1) {",
			"else ?if $kSOMETHING" => " } else if ($1) {",
			"else" => " } else {",
			"end" => " }",
			"set $kVARIABLE =? $kSOMETHING" => '$1 = $3',
		);
		$search['([^ ]*)'] = 'print_r($1)';
		$replace = array_values($search);
		$search = array_keys($search);
		foreach ($search as $i => $s)
		{
			$search[$i] = '/{{ *' . $s . ' *}}/';
		}
		foreach ($replace as $i => $s)
		{
			$replace[$i] = '<? ' . $s . ' ?>';
		}

		$contents = preg_replace($search, $replace, $contents);

		$contents = self::createFunction('createArray',__CLASS__ . '::createArray',$contents);
		$contents = self::createFunction('swap',__CLASS__ . '::swap',$contents);

		return $contents;
	}

	private static function createFunction($name,$callback,$contents)
	{
		$kVARIABLE = '(\$[a-zA-Z_][a-zA-Z0-9_]*(\[[\'"]?[a-zA-Z0-9]+[\'"]?\])*)';
		$search = '/' . "$name *\((( *,? *$kVARIABLE)*)\)" . '/';

		self::$callback = $callback;

		$callback = __CLASS__ . '::returnFunction';
		$contents = preg_replace_callback($search, $callback, $contents);

		return $contents;
	}

	private static function returnFunction($array)
	{
		$callback = self::$callback;
		$vars = $array[1];
		$vars = preg_split('/ *, */',$vars);

		return call_user_func($callback,$vars);
	}

	private static function getGlobals($contents, $usePHPTags = false)
	{
		$kVARIABLE = '((\$[a-zA-Z_][a-zA-Z0-9_]*)(\[[\'"]?[a-zA-Z0-9]+[\'"]?\])*)';
		preg_match_all('/' . $kVARIABLE . '/', $contents, $matches);

		$globals = implode(',',array_unique($matches[2]));

		$contents = "";
		if (strlen($globals))
		{
			if ($usePHPTags) $contents .= '<? ';
			$contents .= "global $globals";
			if ($usePHPTags) $contents .= ' ?>';
		}

		return $contents;
	}

	static function createArray($vars)
	{
		$return = array();
		foreach ($vars as $var)
		{
			$varText = substr($var,1);
			$return[] = "'" . $varText ."' => " . $var;
		}

		$return = 'array(' . implode(', ',$return) . ')';
		return $return;
	}

	static function swap($vars)
	{
		$return = 'list(';

		$return .= implode (',', $vars);

		$return .= ') = array(';

		$return .= implode (',', array_reverse($vars));

		$return .= ')';

		return $return;
		 
	}

	static function convertVariable($dotNotation)
	{
		$dotNotation = $dotNotation[0];
		$variables = explode('.',$dotNotation);
		$first = $variables[0];

		unset($variables[0]);

		$variable = $first;

		foreach ($variables as $text)
		{
			if (is_numeric($text))
			{
				$variable .= "[$text]";
			} else {
				$variable .= "['$text']";
			}
		}

		return $variable;
	}
}

function inc($file)
{
	TemplateEngine::loadFile($file);
}

?>
