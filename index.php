<?
include "db.php";

$mode = $mysqli->real_escape_string($_POST['mode']);
$pcid = $mysqli->real_escape_string($_POST['pcid']);
//$username = $mysqli->real_escape_string($_POST['username']);

if ($mode == 'setcookie') {
	createpcidcookie($pcid);
}

if ($_COOKIE['pcid'] && $username) {
	checkpcidcookie(($_COOKIE['pcid']),$username);
}

switch ($mode) {
default:
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
 		print "<input type=\"hidden\" name=\"mode\" value=\"setcookie\">\n";
 		print "<input type=\"submit\" value=\"Select PC\">\n";
 	}
	if ($_COOKIE['pcid'] ) {
		print "Ah, you are here for " . $_COOKIE['pcid'];
	}
	break;

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

function checkpcidcookie($pcid,$username) {
	$getuserid = $authmysqli->prepare("SELECT userid FROM users WHERE username=?");
	$getuserid->bind_param('s',$username);
	$getuserid->execute();
	if ($getuserid) {
		$getuserid->bind_result($userid);
		$getuserid->fetch();
		// Use that userid to find out what characters they are playing
		$getpcid = $mysqli->prepare("SELECT name FROM players WHERE userid=? AND pcid=?");
		$getpcid->bind_param('ii',$userid,$pcid);
		$getpcid->execute();
		if (!$getpcid) {
			print "Forgery of cookie!<br>\n";
			deletepcidcookie($pcid);
			return;
		}

	}
}
?>
