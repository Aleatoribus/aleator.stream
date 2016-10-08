<?php
	session_start();
	if(isset($_SESSION['username'])){
		header("Location:/");
		exit();
	}

	$title = 'Register | Aleator Stream';
	include("inc/header.inc");

	if(isset($_GET['access'])){
		$access = $_GET['access'];
		if($access == "" || $access == ""){
			print '<p>';
			print "\n			";
			print '<strong>Register a test account</strong>';
			print "\n		";
			print '</p>';
			print "\n		";
			print "\n		";
			print '<form method="post" action="php\process_register.php">';
			print "\n			";
			print '<p>';
			print "\n				";
			print 'Username: <input type="text" name="username"/>';
			print "\n			";
			print '</p>';
			print "\n			";
			print '<p>';
			print "\n				";
			print 'Password: <input type="password" name="password"/>';
			print '</p>';
			print "\n			";
			print '<input type="submit" value="Register" name="submit"/>';
			print "\n		";
			print '</form>';
			print "\n		";
		}
		else{
			print '<p>';
			print "\n			";
			print '<strong>Registration is closed during development.</strong>';
			print "\n		";
			print '</p>';
			print "\n		";
			print '<p>';
			print "\n			";
			print '<img src="media/nope.gif" alt="Nope">';
			print "\n		";
			print '</p>';
		}
	}
	else{
		print '<p>';
		print "\n			";
		print '<strong>Registration is closed during development.</strong>';
		print "\n		";
		print '</p>';
		print "\n		";
		print "\n		";
		print '<p>';
		print "\n			";
		print '<img src="media/nope.gif" alt="Nope">';
		print "\n		";
		print '</p>';
	}
	include("inc/footer.inc");
?>
