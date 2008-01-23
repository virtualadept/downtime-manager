<?

/* Brings up the downtime ID and relevant conversations
* Allows one to add/change/delete conversation
* Only allows participating + ST to view downtimes
* Allows closure of DT's
* Upon finishing a DT, makes a wiki compatable output
* Emails out everyone when a downtime is updated/changed
*/

include "include.php";

if ($_COOKIE['pcid'] && $_COOKIE['gameid']) {
	$gameid = $_COOKIE['gameid'];
	$pcid = $_COOKIE['pcid'];
	// TODO: Check cookies to make sure everything is okay.
} else {
	print "You are not signed in (or cookies are disabled)<br>\n";
	exit;
}

if ($getopendt = $mysqli->query("SELECT dtid,createdate,name FROM dtmeta,games WHERE pcid=\"$pcid\" AND dtmeta.gameid=\"$gameid\" AND dtmeta.status=\"O\" AND games.gameid = dtmeta.gameid SORT BY createdate ASC LIMIT 10");
	print "These are the latest 10 open downtimes that you own<br>\n"
	while($opendt = $getopendt->fetch_array()) {
		print "id: $opendt['dtid'] - $opendt['name'] opened $opendt['createdate']<br>\n";
	}
}

// Create a new downtime
if (!$dtid && $mode == 'create') {

}
// Do things with an existing dt
if ($dtid) {

}
?>
