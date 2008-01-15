<?
include "db.php";
include "header.php";

print "Hello $username!<br><br>\n";


$gameid = $mysqli->real_escape_string($_POST['gameid']);
$username = $mysqli->real_escape_sring($username);

if (!$_COOKIE['pcid'] {
	print "First off, I need to know which character you are playing in today.<br>\n";
	print "Please select your PC:<br>\n";
	print "<form action=\"pcsettings.php\" method=\"post\">\n";
	$getpcnames = $mysqli->query("SELECT pcid,name FROM players WHERE player=



/*
if (!$_COOKIE['game'] && $gameid != "newgame") {
	print "First off, I need to know what game you are playing in.  Please select the game:\n";
	print "<form action=\"pcsettings.php\" method=\"post\">\n";
	print "<select name=\"gameid\">\n";
	$getgames = $mysqli->query("SELECT gameid,gamename FROM games");
	if ($getgames->affected_rows > 0) {
		$getgames->bind_result($gameid,$gamename);
		while ($getgames->fetch()) {
			print "<option value=\"$gameid\">$gamename</option>\n";
		}
	}
	print "<option value=\"newgame\">Add New Game</option>\n";
	print "</select>\n";
	print "<input type=\"submit\" value=\"Edit Game\">\n";
}


if ($gameid == "newgame") {
        print "Enter new Game<br>\n";
        print "<form action=\"pcsettings.php\" method=\"post\">\n";
        print "<li>Name: <input type=\"text\" name=\"gamename\">\n";
        print "<input type=\"hidden\" name=\"mode\" value=\"addgame\">\n";
        print "<br><input type=\"submit\" value=\"Save Changes\">\n";
}
*/

?>
