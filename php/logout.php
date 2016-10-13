<?php
	include("/var/www/aleator.stream/html/inc/security.inc");
	session_start();
	session_destroy();
	header("Location:/");
	exit();
?>
