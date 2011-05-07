<?
class Response
{
	static private $headers = array();

	static function addHeader($name, $value)
	{
		self::$headers[$name] = $value;
	}

	static function removeHeader($name)
	{
		unset(self::$headers[$name]);
		header_remove($name);
	}

	static function enableCaching($file)
	{
		$mtime = filemtime($file);
		$gmdate_mod = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';

		self::addHeader('Last-Modified', $gmdate_mod);
		self::removeHeader('Expires');
		self::removeHeader('Cache-Control');
		self::removeHeader('Pragma');
	}
	
	static function sendHeaders()
	{
		foreach (self::$headers as $name => $value)
		{
			header($name . ': '. $value);
		}
	}

	static function setSpecificHeaders($ext)
	{
		$headers = array();
		if ($ext == 'js')
		{
			$headers['Content-Type'] = 'text/javascript';
		} else if ($ext == 'css')
		{
			$headers['Content-Type'] = 'text/css';
		}
		foreach ($headers as $name => $value) self::addHeader($name,$value);
	}
	
	static function respondWithFile($file)
	{
		if (! file_exists($file)) return false;

		$ext = Util::fileExtension($file);

		self::enableCaching($file);
		self::setSpecificHeaders($ext);
		self::sendHeaders();

		$contents = file_get_contents($file);

		echo $contents;

		exit;
	}
}
?>
