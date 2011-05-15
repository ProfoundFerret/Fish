<?
const kDOCTYPE_XHTML_1_1 = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
const kDOCTYPE_XHTML_1_STRICT = '!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
const kDOCTYPE_XHTML_1_TRANSITIONAL = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
const kDOCTYPE_HTML_5 = '<!DOCTYPE html>';
const kDOCTYPE_HTML_4_STRICT = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
const kDOCTYPE_HTML_4_TRANSITIONAL = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';

class HTML
{
	public static $xhtml = false;
	private static $doctype = false;

	static function doctype()
	{
		$doctype = self::$doctype;
		if ($doctype === false) $doctype = kDOCTYPE_HTML_4_TRANSITIONAL;
		return $doctype;
	}

	static function setDoctype($type)
	{
		self::$doctype = $type;
	}

	static function setXhtml($value)
	{
		self::$xhtml = (bool) $value;
	}

	static function open($tag, $attrs = array())
	{
		if (is_string($attrs))
		{
			$attrs = array('title' => $attrs);
		}

		$tag = strtolower($tag);
		$attributes = "";
		foreach ($attrs as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			$attributes .= " $attribute";
			if (strlen($value))
			{
				$attributes .= '="' . $value . '"';
			}
		}
		$text = "<$tag$attributes";

		if (self::$xhtml) 
		{
			$endTags = array ('br','img','link');
			foreach ($endTags as $endTag)
			{
				if ($endTag == $tag)
				{
					$text .- '/';
					break;
				}
			}
		}

		$text .= ">";


		echo $text;
	}

	static function close($tag)
	{
		$tag = strtolower($tag);
		$text = "</$tag>";

		echo $text;
	}

	static function tag($tag, $attrs = array())
	{
		self::open($tag, $attrs);
		self::close($tag);
	}

	static function br($count = 1)
	{
		$count = max($count,0);

		while ($count--) self::open('br');
	}

	static function select($name, $values, $default = false, $attrs = array())
	{
		self::input('select', $name, $default, $values, $attrs);
	}

	static function checkbox($name, $values, $default = false, $attrs = array())
	{
		self::input('checkbox', $name, $default, $values, $attrs);
	}

	static function radio($name, $values, $default = false, $attrs = array())
	{
		self::input('radio', $name, $default, $values, $attrs);
	}

	static function submit($name = false, $value = false, $attrs = array())
	{
		self::input('submit', $name, $value, false, $attrs);
	}

	static function password($name = 'password', $default = false, $attrs = array())
	{
		self::inputForced('password', $name, $default, false, $attrs);
	}

	static function text($name, $default = false, $attrs = array())
	{
		self::input('text', $name, $default, false, $attrs);
	}

	static function form($method = 'get', $uploadingFiles = false, $attrs = array())
	{
		$attrs['method'] = $method;
		if ($uploadingFiles) $attrs['enctype'] = 'multipart/form-data';

		self::open('form',$attrs);
	}

	static function file($name, $attrs = array())
	{
		self::input('file', $name, false, false, $attrs);
	}

	static function input($type, $name, $default = false, $values = false, $attrs = array())
	{
		if ($type == "checkbox" || $type == "radio")
		{
			$curVal = array();

			foreach ($values as $name => $value)
			{
				if (isSet($_REQUEST[$name])) 
				{
					$newValue = $_REQUEST[$name];
				} else if (isSet($default[$name]))
				{
					$newValue = $default[$name];
				} else {
					$newValue = false;
				}

				$curVal[$name] = $newValue;
			}
		} else {
			if (isSet($_REQUEST[$name]))
			{
				$curVal = $_REQUEST[$name];
			} else if ($default) {
				$curVal = $default;
			} else {
				$curVal = "";
			}
		}

		self::inputForced($type,$name,$curVal, $values, $attrs);
	}

	static function textarea($name, $default = false, $attrs = array())
	{
		self::input('textarea', $default, $attrs);
	}

	static function inputForced($type, $name = "", $value = "", $values = array(), $attrs = array())
	{
		if (is_string($attrs))
		{
			$attrs = array ('title' => $attrs);
		}
		$attrs['name'] = $name;
		if (! is_array($values)) $values = array();
		if ($type == "select")
		{
			self::open($type, $attrs);

			foreach ($values as $key => $val)
			{
				$attrs = array();
				$attrs['value'] = $key;

				echo PHP_EOL . "$key / $value";

				if ($val == $value)
				{
					$attrs['selected'] = true;
				}

				self::open('option',$attrs);
				echo $val;
				self::close('option');
			}

			self::close($type);
		} else if ($type == "checkbox" || $type == "radio")
		{
			$attrs['type'] = $type;

			$delims = " \n\t";

			foreach ($values as $key => $val)
			{
				if ($type == "radio") $key = $name;

				$tok = strtok($name . '-' . $val, $delims);
				$id = "";
				while ($tok !== false)
				{
					$id .= $tok;
					$tok = strtok($delims);
				}

				$attributes = $attrs;
				$attributes['id'] = $id;
				$attributes['name'] = $key;
				$attributes['value'] = $val;
				if (isSet($value[$key]) && $val == $value[$key]) $attributes['checked'] = true;

				self::tag('input',$attributes);
				
				$attributes = array();
				$attributes['for'] = $id;

				self::open('label',$attributes);
				echo $val;
				self::close('label');
			}
		} else if ($type == 'textarea')	{
			self::open('textarea',$attrs);
			echo $value;
			self::close('textarea');
		} else {
			// Provide a default for buttons
			if (($type == "Submit" || $type == "Button") && ! strlen($value)) $value = "Submit";
			$attrs['type'] = $type;
			$attrs['value'] = $value;

			self::open('input',$attrs);
		}
	}

	static function link($url, $name = false, $attrs = array(), $confirmMessage = "")
	{
		if ((substr($url,0,7) != "http://") && (substr($url,0,1) != '/'))
		   	$url = kSHORT_PREFIX . $url;

		if (is_string($attrs))
		{
			$attrs = array('title' => $attrs);
		}

		if (strlen($confirmMessage))
		{
			$confirmMessage = htmlspecialchars($confirmMessage,ENT_QUOTES);
			$attrs['onclick'] = "return confirm('" . $confirmMessage . "');";
		}
		$attrs['href'] = $url;

		if ($name === false) $name = $url;

		self::open('a',$attrs);
		echo $name;
		self::close('a');
	}

	static function h($number, $text, $attrs = array())
	{
		$tag = 'h' . $number;

		self::open($tag, $attrs);
		echo $text;
		self::close($tag);
	}

	static function img($file, $attrs = array())
	{
		if (is_string($attrs))
		{
			$title = $attrs;
			$attrs = array();
			$attrs['title'] = $title;
			$attrs['alt'] = $title;
		}

		$attrs['src'] = $file;
		self::open('img',$attrs);
	}

	static function css($file, $media = 'screen')
	{
		if ((substr($file,0,7) != "http://") && (substr($file,0,1) != '/'))
		   	$file = kSHORT_PREFIX . $file;
		$attrs = array();
		$attrs['href'] = $file;
		$attrs['type'] = 'text/css';
		$attrs['media'] = $media;
		$attrs['rel'] = 'stylesheet';

		self::open('link',$attrs);
	}

	static function js($file)
	{
		if ((substr($file,0,7) != "http://") && (substr($file,0,1) != '/'))
		   	$file = kSHORT_PREFIX . $file;

		$attrs = array();
		$attrs['src'] = $file;
		if (self::$xhtml)
		{
			$attrs['type'] = 'text/javascript';
		}

		self::tag('script',$attrs);
	}

	static function selectRange($name, $start, $end, $leadingZeros = false, $default = false, $attrs = array())
	{
		$numbers = array();
		for ($i = $start; $i <= $end; $i++)
		{
			$text = $i;
			if ($leadingZeros && $i < 10) $text = "0$text";
			$numbers[$text] = $i;
		}

		self::select($name, $numbers, $default, $attrs);
	}

	static function day($name, $default = false, $attrs = array())
	{
		if ($default === false) $default = date('d');

		self::selectRange($name, 1, 31, $default, $attrs);
	}

	static function year($name, $default = false, $attrs = array())
	{
		if ($default === false) $default = date('Y');

		self::selectRange($name, date('Y'), date('Y') + 1, $default, $attrs);
	}

	static function month($name, $default = false, $attrs = array())
	{
		if ($default === false) $default = date('m');

		$months = array(1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		foreach ($months as $i => $name)
		{
			if ($i < 10)
			{
				$months["0$i"] = $name;
				unset($months[$i]);
			}
		}
		ksort($months);
		
		self::select($name, $months, $default, $attrs);
	}

	static function comment($text)
	{
		self::open('!-- ');
		print_r($text);
		self::close(' --');
	}
}
?>
