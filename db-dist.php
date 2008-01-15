<?php
// Since this area is 100% auth'd already, no point in cookie setting
$username = "{$_SERVER['PHP_AUTH_USER']}";

// Connect to MySQL
$mysqli = new mysqli('host','username','password','db')
	or die("Problem connecting:".mysqli_error());
?> 
