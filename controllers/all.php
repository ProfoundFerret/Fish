<?
$title = kSITE_NAME;

$db = new MySQLi('localhost', 'root', '', 'ltd');

$JS = array();
$JS[] = 'js/jquery';
$JS[] = 'js/std';

$CSS = array();
$CSS[] = 'css/reset';
$CSS[] = 'css/http://fonts.googleapis.com/css?family=Ubuntu';
$CSS[] = 'css/http://fonts.googleapis.com/css?family=Droid+Sans';
$CSS[] = 'css/fish';
$CSS[] = 'css/std';

$nav[0]['home'] = 'Home';
$nav[0]['gallery'] = 'Gallery';
$nav[0]['attractions'] = 'Nearby Attractions';
$nav[0]['travel'] = 'Travel';
$nav[0]['contact'] = 'Contact Us';

Util::loadLibrary('Text');

?>
