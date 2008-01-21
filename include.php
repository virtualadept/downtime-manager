<?

include "db.php";

function titleCase($string) {
	return ucwords(strtolower($string));
}

function getuseridfromusername($dbh,$username) {
	if ($getuserid = $dbh->prepare("SELECT userid FROM users WHERE username=?")) {
		$getuserid->bind_param('s',$username);
		$getuserid->execute();
		$getuserid->bind_result($userid);
		$getuserid->fetch();
		return $userid;
	}
}

function getplayerinfofrompcid($dbh,$pcid,$userid) {
	if ($getplayerinfo = $dbh->query("SELECT * FROM players WHERE pcid=\"$pcid\" AND userid=\"$userid\"")) {
		$playerinfo = $getplayerinfo->fetch_assoc();
		return $playerinfo;
	}
}

function getgameinfofromgameid($dbh,$gameid,$stuserid) {
	if ($getgameinfo = $dbh->query("SELECT * FROM games WHERE gameid=\"$gameid\" AND stuserid=\"$stuserid\"")) {
		$gameinfo = $getgameinfo->fetch_assoc();
		return $gameinfo;
	}
}
/*
function getgameaccessfromuserid($dbh,$userid) {
	if ($getgameaccess = $dbh->query("SELECT games.name from games,access WHERE type='U' AND 
*/


function scookie($name,$pcid) {
	setcookie("$name","$pcid",mktime()+86400,"/") or die("Could not set cookie");
}


function dcookie($name,$pcid) {
	setcookie("$name","$pcid",mktime()-86400,"/") or die("Could not delete cookie");
}
?>
