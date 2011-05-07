<?
class CacheControl
{
	static function fileLocation($file)
	{
		$file = 'fish/cache/' . $file;

		return $file;
	}

	static function cacheUpToDate($file)
	{
		$cache = self::fileLocation($file);

		$fileTime = filemtime($file);
		if (file_exists($cache))
		{
			$cacheTime = filemtime($cache);
		} else {
			$cacheTime = 0;
		}
		
		return ($fileTime <= $cacheTime);
	}

	static function writeFile($file, $contents)
	{
		$file = self::fileLocation($file);

		$dirname = dirname($file);
		if (! file_exists($dirname))
		{
			$old = umask(0);
			mkdir($dirname, 0777, true);
			umask($old);
		}

		if (file_exists($file)) chmod($file, 0777);
		file_put_contents($file,$contents,LOCK_EX);
	}
}
?>
