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

		if (count(self::$routes))
		{
			$r0 = self::$routes[0];
		} else {
			$r0 = false;
		}

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

		$folders = array('controllers/','views/');

		$includes = array();
		foreach ($folders as $folder)
		{
			$includes[] = $folder . 'all.php';
			foreach (self::routes() as $include)
			{
				$includes[] = $folder . $include . '.php';
				$folder .= $include .'/';
			}
			$includes[] = $folder . 'home.php';
		}

		self::$includes = $includes;
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
				echo "INC $file\n";
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

		if (end($pieces) == "all.php")
		{
			self::includeNextFile();
			return;
		}

		array_pop($pieces);
		array_pop($pieces);

		if (count($pieces) <= 1)
		{
			self::includeNextFile();
			return;
		}

		array_push($pieces, 'home.php');

		$newFile= implode('/', $pieces);

		array_unshift(self::$includes,$newFile);
		self::includeNextFile();
	}

	static private function updateGlobalVariable()
	{
		global $_ROUTES;
		$_ROUTES = self::routes();
	}
}
?>
