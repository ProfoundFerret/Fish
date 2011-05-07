<?
Class Router
{
	private static $routes = false;
	private static $includes = array();
	private static $count = 0;
	private static $extension = 'html';

	static function determineRoute()
	{
		if (isSet($_SERVER['REDIRECT_URL'])) $url = $_SERVER['REDIRECT_URL'];
		else $url = $_SERVER['REQUEST_URI'];

		$pwd = getcwd();

		$pieces = explode('/',$url);

		$routes = array();
		$segment = "";
		
		foreach ($pieces as $piece)
		{
			if (strlen($piece) && $piece != 'all')
			{
				$segment .= '/' . $piece;
				$routes[] = $piece;

				if (strpos($pwd, $segment) !== false)
				{
					$routes = array();
				}
			}
		}

		if (! isSet($routes[0])) $routes[0] = 'home';

		self::$routes = $routes;

		self::updateGlobalVariable();
	}
	
	static function routes()
	{
		return self::$routes;
	}
	
	static function routeUser()
	{
		if (! self::routes()) self::determineRoute();

		$r0 = self::$routes[0];

		if ($r0 == "css" || $r0 == "js" || $r0 == "images")
		{
			$file = self::$routes;
			unset($file[0]);
			$file = $r0 . '/' . implode(',',$file);
			$file = glob($file . '*');

			if (count($file) != 1) exit;
			
			$file = $file[0];

			Response::respondWithFile($file);

			return;
		}

		$folders = array('controllers','views');

		foreach ($folders as $folder)
		{	
			foreach (self::routes() as $include)
			{
				$file = $folder . '/all.php';
				self::$includes[] = $file;
				$folder .=  '/' . $include;
			}
			$file = $folder . '.php';
			self::$includes[] = $file;
		}
	}

	static function skipNextInclude()
	{
		return array_shift(self::$includes);
	}

	static function includeNextFile()
	{
		if (! self::routes()) self::routeUser();

		$file = array_shift(self::$includes);
		if ($file)
		{
			if (file_exists($file))
			{
				self::$count++;
				$count = self::$count;
				TemplateEngine::loadFile($file);
				if ($count == self::$count) self::includeNextFile();
			} else {
				self::loadBackupFile($file);
			}
		}
	}

	private static function loadBackupFile($file)
	{
		$pieces = explode('/', $file);
		if (end($pieces) == 'all.php')
		{
			self::includeNextFile();
			return;
		}
		array_pop($pieces);

		$lastKey = FArray::lastKey($pieces);

		if (! isSet($pieces[$lastKey])) return;

		$pieces[$lastKey] .= '.php';

		$file = implode('/',$pieces);

		self::$includes[] = $file;
		self::includeNextFile();
	}

	static private function updateGlobalVariable()
	{
		global $_ROUTES;
		$_ROUTES = self::routes();
	}
}
?>
