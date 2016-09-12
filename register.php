<?php
	session_start();
	if(isset($_SESSION['username'])){
		header("Location:/");
		exit();
	}

	$title = 'Register | Aleator Stream';
	include("inc/header.inc");
?>

	<div align="center">

		<?php
			if(isset($_GET['access'])){
				$access = $_GET['access'];
				if($access == ""){
					print '<p>';
					print "\n			";
					print '<strong>Register a demo account</strong>';
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
		?>

		<hr>

		<div style="font-size: 70%;">
			<p>
				<em>If you create an account now that'll be the end of it.</em>
			</p>
			<p>
				<em>We will not look for you, we will not pursue you, but if you don't, we will look for you, we will find you and we will store your files securely.</em>
			</p>
		</div>

		<hr>
		
		<footer>
			<!-- <hr> -->
			<!-- Aleatoribus is an open source project licensed under version 2.0 of the Apache Licence. -->
		</footer>
	</div>

	</body>
</html>
