<?
class Fish
{
	static function next()
	{
		Router::includeNextFile();
	}

	static function skip()
	{
		Router::skipNextInclude();
	}

	static function inc($file)
	{
		TemplateEngine::loadFile($file);
	}
}
?>
