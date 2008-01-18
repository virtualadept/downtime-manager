<?
include "include.php";

$gameid = $mysqli->real_escape_string($_POST['gameid']);
$cookie = $mysqli->real_escape_string($_POST['cookie']);

if ($cookie == "set" && $gameid) {
	scookie("st","$gameid");
}

if (!$_COOKIE['st']) {
	$userid = getuseridfromusername($authmysqli,$username);
	if ($getstlist = $mysqli->prepare("SELECT gameid,name FROM games WHERE stuserid=?")) {
		print "<form action=\"st.php\" method=\"post\">";
		print "<select name=\"gameid\">";
		$getstlist->bind_param('i',$userid);
		$getstlist->execute();
		$getstlist->bind_result($gameid,$gamename);
		while ($getstlist->fetch()) {
			print "<option value=\"$gameid\">$gamename</option>\n";
		}
		print "</select>";
		print "<input type=\"hidden\" name=\"cookie\" value=\"set\">\n";
		print "<input type=\"submit\" value=\"Select Game\">\n";
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
	if ($getplayerlist = $authmysqli->query("SELECT userid,username FROM users ORDER BY username ASC")) {
		while ($players = $getplayerlist->fetch_assoc()) {
			print "<input type=\"checkbox\" name=\"playerlist[]\" value=\"".$players['userid']."\">".$players['username']."<br>\n";
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
