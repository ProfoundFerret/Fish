<?
ob_start();

include 'fish/config.php';
include 'fish/libs/util.php';

Util::loadLibrary('Fish');
Util::loadLibrary('CacheControl');
Util::loadLibrary('TemplateEngine');
Util::loadLibrary('Response');
Util::loadLibrary('Router');
Util::loadLibrary('Debugger');
Util::loadLibrary('Html');
Util::loadLibrary('Validator');

$nav = array(0 => array());
$title = kSITE_NAME;

Fish::next();

if (kTIDY_HTML) Util::tidyHTML();
?>
