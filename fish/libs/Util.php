<?
class Util
{
	static function setFlash($text)
	{
		$_SESSION['fish']['flash'] = true;
	}

	static function flash()
	{
		if (isSet($_SESSION['fish']['flash'])) echo $_SESSION['fish']['flash'];

		unset($_SESSION['fish']['flash']);
	}

	static function loadLibrary($lib)
	{
		$file = $lib . '.php';
		
		if (file_exists($file) include $file;
		
		$file = "libs/$file";

		if (file_exists('fish/' . $file)) include 'fish/' . $file;
		
		if (file_exists($file)) include $file;
	}

	static function get($name, $value = false)
	{
		if (isSet($_GET[$name])) return $_GET[$name];
		return $value;
	}

	static function post($name, $value = false)
	{
		if (isSet($_POST[$name])) return $_POST[$name];
		return $value;
	}

	static function e($text)
	{
		$text = htmlspecialchars($text);

		return $text;
	}

	static function alt()
	{
		static $alternatingArrays;

		if (! $alternatingArrays) $alternatingArrays = array();

		$args = func_get_args();
		$string = implode('/', $args);

		if (! isSet($alternatingArrays[$string]))
		{
			$alternatingArrays[$string] = $args;
		}

		$value = current($alternatingArrays[$string]);

		if (next($alternatingArrays[$string]) === false)
		{
			reset($alternatingArrays[$string]);
		}

		return $value;
	}

	static function tidyHTML()
	{
		$html = ob_get_clean();

		$config = array ('indent' => true, 'output-xhtml' => true, 'wrap' => false);

		$tidy = new tidy;
		$tidy->parseString($html, $config, 'UTF8');
		$tidy->cleanRepair();

		echo $tidy;
	}

	static function fileExtension($file)
	{
		$file = explode('.',$file);
		$ext = end($file);
		$ext = strtolower($ext);
		return $ext;
	}
	
	static function alert($text)
	{
		echo '<script type="text/javascript">alert("' . $text . '");</script>';
	}
}

function exists(&$var)
{
	if (! isSet($var)) return false;
	return $var;
}

function array_uncreate($array, $prefix = '')
{
	foreach ($array as $var => $val)
	{
		$var = $prefix . $var;
		global $$var;
		$$var = $val;
	}
}

function array_last_key(& $array)
{
    return array_pop(array_keys($array));
}

function array_turn(& $array)
{
    $rt = array();
    for ($z = 0;$z < count($array);$z++) 
    {
        for ($x = 0;$x < count($array[$z]);$x++) 
        {
            $rt[$x][$z] = $array[$z][$x]; 
        }
    }

    return $rt; 
}
?>
