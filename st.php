<?
include "include.php";

$gameid = $mysqli->real_escape_string($_POST['gameid']);
$cookie = $mysqli->real_escape_string($_POST['cookie']);
$userslist = ($_POST['userslist']);

if ($cookie == "set" && $gameid) {
	scookie("st","$gameid");
}

if (!$_COOKIE['st']) {
	$userid = getuseridfromusername($authmysqli,$username);
	if ($getstlist = $mysqli->prepare("SELECT gameid,name FROM games WHERE stuserid=?")) {
		print "<form action=\"st.php\" method=\"post\">\n";
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
	print "You are the ST for " . $gameinfo["name"] . "<br>\n";
	print "Please select which people are a part of your game:<br>\n";
	if ($getuserslist = $authmysqli->query("SELECT userid,username FROM users ORDER BY username ASC")) {
		while ($users = $getuserslist->fetch_assoc()) {
			print "<input type=\"checkbox\" name=\"userlists[]\" value=\"".$users['userid']."\">".$users['username']."<br>\n";
		}
	print "<input type=\"hidden\" name=\"mode\" value=\"userset\">\n";
	print "<input type=\"submit\" value=\"Go\">\n";
	}

	if ($mode == "userset") {
		// Check to see if their cookie is legit
		$userid = getuseridfromusername($authmysqli,$username);
		$cookiegameid = $_COOKIE['st'];
		if ($checkstcookie = $mysqli->prepare("SELECT name FROM games WHERE gameid=? AND stuserid=?") {
			$checkstcookie->bind_param('ii',$cookiegameid,$userid);
			$checkstcookie->execute();
			$checkstcookie->bind_result($gamename);
			if (!$gamename) {
				print "Warning! Some cookie douchebaggery is going on here!<br>\n";
				exit;
			}
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
