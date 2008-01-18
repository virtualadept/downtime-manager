<?

include "db.php";

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


function scookie($name,$pcid) {
	setcookie("$name","$pcid",mktime()+86400,"/") or die("Could not set cookie");
}


function dcookie($name,$pcid) {
	setcookie("$name","$pcid",mktime()-86400,"/") or die("Could not delete cookie");
}
?>
