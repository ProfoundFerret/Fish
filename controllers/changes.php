<?
$file = kPREFIX . '.git/logs/HEAD';
if (file_exists($file))
{
	$changes = file_get_contents($file);
} else {
	?><h1>Can't Find Changes =[</h1><?
	return;
}

$name = '([^<]*)';
$email = '(<([^>]+)>)?';
$timestamp = '([0-9]+) (-?[0-9]{4})';
$change = ".*commit: (.+)";

$search = "[0-9a-z]+ [0-9a-z]+ $name $email $timestamp$change";

preg_match_all('/' . $search . '/i',$changes,$matches);

$matches = array_turn($matches);

foreach ($matches as $i => $values)
{
	$name = $values[1];
	if (strpos($name,'master:') === 0)
	{
		unset($matches[$i]);
		continue;
	}
	$email = $values[3];
	$timestamp = $values[4];
	$change = $values[6];
	$date = date('M NS \a\t h:iA',$timestamp);

	$matches[$i] = createArray($name,$email,$date,$change,$timestamp);
}
$matches = array_reverse($matches);
?>
