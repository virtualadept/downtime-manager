<?
include "include.php";

$gameid = $mysqli->real_escape_string($_GET['gameid']);
$cookie = $mysqli->real_escape_string($_GET['cookie']);
$mode = $mysqli->real_escape_string($_POST['mode']);
$userslist = ($_POST['userslist']);

if ($cookie == "set" && $gameid) {
	scookie("st","$gameid");
}

if (!$_COOKIE['st']) {
	$userid = getuseridfromusername($authmysqli,$username);
	if ($getstlist = $mysqli->prepare("SELECT gameid,name FROM games WHERE stuserid=?")) {
		print "<form action=\"st.php\" method=\"get\">\n";
		print "<select name=\"gameid\">\n";
		$getstlist->bind_param('i',$userid);
		$getstlist->execute();
		$getstlist->bind_result($gameid,$gamename);
		while ($getstlist->fetch()) {
			print "<option value=\"$gameid\">$gamename</option>\n";
		}
		print "</select>";
		print "<input type=\"hidden\" name=\"cookie\" value=\"set\">\n";
		print "<input type=\"submit\" value=\"Select Game\">\n";
		print "</form>\n";
	} else {
		print "Sorry! You have no games that you ST! :(\n";
	}
}

if ($_COOKIE['st']) {
	$cookiegameid = $_COOKIE['st'];
	$userid = getuseridfromusername($authmysqli,$username);
	$gameinfo = getgameinfofromgameid($mysqli,$cookiegameid,$userid);


	if ($mode == "userset" && $userslist) {
		// Check to see if their cookie is legit
		$userid = getuseridfromusername($authmysqli,$username);
		$cookiegameid = $_COOKIE['st'];
		$gameinfo = getgameinfofromgameid($mysqli,$cookiegameid,$userid);
		if (!$gameinfo) {
			print "Warning! Some cookie douchebaggery is going on here!<br>\n";
		//	exit;
		}
		// First, we'll just delete everyone out of the DB who is a part of that game
		$deleteaccessusers = $mysqli->query("DELETE FROM access WHERE gameid=\"$cookiegameid\" AND type='U'");
		// If we are at this point, all went well, lets add people back.
		if ($addaccessusers = $mysqli->prepare("INSERT INTO access (gameid,type,id) VALUES (?,'U',?)")) {
			foreach ($userslist as $entereduserid) {
				$addaccessusers->bind_param('ii',$cookiegameid,$entereduserid);
				$addaccessusers->execute();
			}
			print "Done!<br><br>\n";
		}
	}


	print "You are the ST for " . $gameinfo["name"] . "<br>\n";
	print "Please select which people are a part of your game:<br>\n";
	print "<form action=\"st.php\" method=\"post\">\n";
	// User list.  First we'll nab from the DB all of the people who are in the game
	if ($getactive = $mysqli->prepare("SELECT id FROM access WHERE gameid=? AND type=\"U\"")) {
		$getactive->bind_param('i',$cookiegameid);
		$getactive->execute();
		$getactive->bind_result($activeuser);
		while ($getactive->fetch()) {
			$activehash[$activeuser] = "present";
		}
	}
	if ($getuserslist = $authmysqli->query("SELECT userid,username FROM users ORDER BY username ASC")) {
		while ($users = $getuserslist->fetch_assoc()) {
			$out = "<input type=\"checkbox\" name=\"userslist[]\" value=\"".$users['userid']."\"";
			if (isset($activehash[$users['userid']])) {
				$out .= " checked ";
			}
			$out .= ">".$users['username']."<br>";
			print "$out\n";
		}
	print "<input type=\"hidden\" name=\"mode\" value=\"userset\">\n";
	print "<input type=\"submit\" value=\"Go\">\n";
	}

}

// TODO
// Get userid from username
// Get list of games that userid ST's
// Print list
// Set cookie
// Show players involved and characters involved in game.
// Add/Edit/Delete users access to games.
// ? Add/edit/del players in a game.


?>
