<?
include "db.php";

$mode = $mysqli->real_escape_string($_POST['mode']);
$pcid = $mysqli->real_escape_string($_POST['pcid']);
$cookie = $mysqli->real_escape_string($_POST['cookie']);
//$username = $mysqli->real_escape_string($_POST['username']);

if ($cookie == 'set') {
	setpcidcookie($pcid);
}
print "Hello $username to the Downtime Manager!<br><br><br>\n";

// If they dont have a cookie set, ask them which character they want.
if (!$_COOKIE['pcid']) {
	print "Ah, I see that you have not picked your character.  Lets fix that.<br>\n";
	print "<form action=\"index.php\" method=\"post\">\n";
	print "<select name=\"pcid\">\n";
	// Pull the userid of the logged in user from the apacheauth db.
	$getuserid = $authmysqli->prepare("SELECT userid FROM users WHERE username=?");
	$getuserid->bind_param('s',$username);
	$getuserid->execute();
	if ($getuserid) {
		$getuserid->bind_result($userid);
		$getuserid->fetch();
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

if ($_COOKIE['pcid'] ) {
	$cookiepcid = $_COOKIE['pcid'];
	$getplayername = $mysqli->prepare("SELECT name FROM players WHERE pcid=?");
	$getplayername->bind_param('i',$cookiepcid);
	$getplayername->execute();
	$getplayername->bind_result($playername);
	$getplayername->fetch();
	print "Ah, You are here for $playername, excellent!<br>\n";
}
	
if ($_COOKIE['pcid'] && !$_COOKIE['gameid']) {
	print "You have not chosen a game yet! Lets fix that:\n";
}
	

function getuseridfromusername($username) {
	$getuserid = $authmysqli->prepare("SELECT userid FROM users WHERE username=?");
	$getuserid->bind_param('s',$username);
	$getuserid->execute();
	if ($getuserid) {
		$getuserid->bind_result($userid);
		$getuserid->fetch();
		return $userid;
	}
}

function getplayerinfofrompcid($pcid,$userid) {
	$getplayerinfo = $mysqli->prepare("SELECT * FROM players WHERE pcid=? AND userid=?");
	$getplayerinfo->bind_param('ii',$pcid,$userid);
	$getplayerinfo->execute();
	if ($getplayerinfo) {
		// fetch it into assoc array
		return $something;
	}
}


function createpcidcookie($pcid) {
	setcookie("pcid","$pcid",mktime()+86400,"/") or die("Could not set cookie");
	if ($_COOKIE['pcid']) {
		print "Cookie set!";
		return;
	}
}


function deletepcidcookie($pcid) {
	setcookie("pcid","$pcid",mktime()-86400,"/") or die("Could not delete cookie");
	if (!$_COOKIE['pcid']) {
		print "Cookie deleted!";
		return;
	}
}

?>
