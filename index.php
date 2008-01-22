<?
include "include.php";

$mode = $mysqli->real_escape_string($_GET['mode']);
$pcid = $mysqli->real_escape_string($_GET['pcid']);
$cookie = $mysqli->real_escape_string($_GET['cookie']);
//$username = $mysqli->real_escape_string($_GET['username']);

if ($cookie == 'set' && $pcid) {
	scookie('pcid',$pcid);
}
print "Hello $username to the Downtime Manager!<br><br><br>\n";

// If they dont have a cookie set, ask them which character they want.
if (!$_COOKIE['pcid']) {
	print "Ah, I see that you have not picked your character.  Lets fix that.<br>\n";
	print "<form action=\"index.php\" method=\"get\">\n";
	print "<select name=\"pcid\">\n";
	$userid = getuseridfromusername($authmysqli,$username);	
	if ($userid) {
		// Use that userid to find out what characters they are playing
		$getpcid = $mysqli->prepare("SELECT pcid,name FROM players WHERE userid=?");
		$getpcid->bind_param('i',$userid);
		$getpcid->execute();
		if ($getpcid) {
			$getpcid->bind_result($pcid,$pcname);
			while ($getpcid->fetch()) {
				print "<option value=\"$pcid\">$pcname</option>\n";
			}
		}
	}			
	print "<option value=\"createnew\">Create New PC</option>\n";
	print "</select>\n";
		print "<input type=\"hidden\" name=\"cookie\" value=\"set\">\n";
		print "<input type=\"submit\" value=\"Select PC\">\n";
	}

// If we have a cookie with the user's pcid, lets fetch all we can for later scripts

if ($_COOKIE['pcid'] ) {
	$cookiepcid = $_COOKIE['pcid'];
	$userid = getuseridfromusername($authmysqli,$username);
	$pinfo = getplayerinfofrompcid($mysqli,$cookiepcid,$userid);
	print "Ah, You are here for ". $pinfo["name"] . ", excellent!<br>\n";
}
	
if ($_COOKIE['pcid'] && !$_COOKIE['gameid']) {
	print "You have not chosen a game yet! Lets fix that:\n";
}
	
/* TODO
* Find games players are in and set cookie
* Show summary of downtime logs open and closed in nice chart
*/
?>
