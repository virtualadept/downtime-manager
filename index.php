<?
include "db.php";

$mode = $mysqli->real_escape_string($_POST['mode']);
$pcid = $mysqli->real_escape_string($_POST['pcid']);
$cookie = $mysqli->real_escape_string($_POST['cookie']);
//$username = $mysqli->real_escape_string($_POST['username']);

if ($cookie == 'set' && $pcid) {
	scookie('pcid',$pcid);
}
print "Hello $username to the Downtime Manager!<br><br><br>\n";

// If they dont have a cookie set, ask them which character they want.
if (!$_COOKIE['pcid']) {
	print "Ah, I see that you have not picked your character.  Lets fix that.<br>\n";
	print "<form action=\"index.php\" method=\"post\">\n";
	print "<select name=\"pcid\">\n";
	// Pull the userid of the logged in user from the apacheauth db.
//	$getuserid = $authmysqli->prepare("SELECT userid FROM users WHERE username=?");
//	$getuserid->bind_param('s',$username);
//	$getuserid->execute();
//	if ($getuserid) {
//		$getuserid->bind_result($userid);
//		$getuserid->fetch();
	$userid = getuseridfromusername($username);	
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
	$pcid = getuseridfromusername($username);
	$pinfo = getplayerinfofrompcid($cookiepcid,$username);
//	$getplayername = $mysqli->prepare("SELECT name FROM players WHERE pcid=?");
//	$getplayername->bind_param('i',$cookiepcid);
//	$getplayername->execute();
//	$getplayername->bind_result($playername);
//	$getplayername->fetch();
	print "Ah, You are here for $pinfo["name"], excellent!<br>\n";
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
		$playerinfo =  $getplayerinfo->fetch_array(MYSQLI_ASSOC);
		return $playerinfo;
	}
}


function scookie($name,$pcid) {
	setcookie("$name","$pcid",mktime()+86400,"/") or die("Could not set cookie");
}


function dcookie($pcid) {
	setcookie("pcid","$pcid",mktime()-86400,"/") or die("Could not delete cookie");
}

?>
