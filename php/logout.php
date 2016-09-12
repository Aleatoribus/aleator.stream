<?php
	include("inc/security.inc");
	session_start();
	session_destroy();
	header("Location:/");
?>
