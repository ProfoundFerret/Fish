<?
$title = kSITE_NAME;

$db = new MySQLi('localhost', 'root', '', 'ltd');

$JS = array();
$JS[] = 'js/jquery-1.6.min';
$JS[] = 'js/fish';
$JS[] = 'js/std';
$JS[] = 'js/jquery.color.js';
$JS[] = 'http://static.addtoany.com/menu/page.js';

$CSS = array();
$CSS[] = 'css/reset';
$CSS[] = 'css/http://fonts.googleapis.com/css?family=Ubuntu';
$CSS[] = 'css/http://fonts.googleapis.com/css?family=Droid+Sans';
$CSS[] = 'css/fish';
$CSS[] = 'css/std';

$nav[0]['home'] = 'Home';
$nav[0]['http://italianadventuresofdiannandsusie.blogspot.com/2010_07_01_archive.html'] = 'Blog';
$nav[0]['gallery'] = 'Gallery';
$nav[0]['attractions'] = 'Nearby Attractions';
$nav[0]['travel'] = 'Travel';
$nav[0]['contact'] = 'Contact Us';
$nav[0]['rates'] = 'Rates';

Util::loadLibrary('Text');

$adminDirection = '/' . $_ROUTES[0];

?>
