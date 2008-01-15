<?
include db.php
include header.php

print "Hello $username to the Downtime Manager\n";

// Looking for the cookie to set the game and PC used
if (!$_COOKIE['game']) {
	print "I see you have not chosen a game yet.\n";
	}
else {
	print "You are working with $_COOKIE['pcname'] in $_COOKIE['game'].  To fix that go <a href="pcsettings.php">here</a>\n";
}

?>
