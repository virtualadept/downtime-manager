<?
include "db.php";

$mode = $mysqli->real_escape_string($_POST['mode']);
//$username = $mysqli->real_escape_string($_POST['username']);

switch ($mode) {
default:
	print "Hello $username to the Downtime Manager!<br>\n";

	if (!$_COOKIE['pcid']) {
		print "Ah, I see that you have not picked your character.  Lets fix that.<br>\n";
		print "<form action=\"index.php\" method=\"post\">\n";
		print "<select name=\"pcid\">\n";
		$getuserid = $authmysqli->prepare("SELECT userid FROM users WHERE username=\"$username\"");
		$getuserid->execute();
		$getuserid->bind_result($userid);
		$getuserid->fetch();
		$getpcid = $mysqli->prepare("SELECT pcid,name FROM players WHERE userid=\"$userid\"");
		$getpcid->execute();
		$rows = $getpcid->num_rows();
		print "$userid $rows";
		if ($getpcid->num_rows > 0) {
			$getpcid->bind_result($pcid,$pcname);
			while ($getpcid->fetch()) {
				print "<option value=\"$pcid\">$pcname</option>\n";
			}
		}		
		print "<option value=\"createnew\">Create New PC</option>\n";
		print "</select>\n";
 		print "<input type=\"submit\" value=\"Select PC\">\n";
 	}

	break;
}




?>
