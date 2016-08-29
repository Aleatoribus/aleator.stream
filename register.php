<?php
	session_start();
	if(isset($_SESSION['username'])){
		//redirect to error page
		header("Location:/");
		exit(0);
	}

	$title = 'Aleator Stream';
	include("inc/header.inc");
?>

	<div align="center">
		
		<div style="font-size: 70%;">
			<p>
				<em>We don't know who you are.</em>
			</p>
			<p>
				<em>We don't know what you want.</em>
			</p>
			<p>
				<em>If you're looking for Dropbox we can tell you we don't have the money, but what we do have are a very particular set of skills.</em>
			</p>
			<p>
				<em>Skills we have acquired over a very long education.</em>
			</p>
			<p>
				<em>Skills that make us a godsend for people like you.</em>
			</p>
			<p>
				<em>If you create an account now that'll be the end of it.</em>
			</p>
			<p>
				<em>We will not look for you, we will not pursue you, but if you don't, we will look for you, we will find you and we will store your files securely.</em>
			</p>
		</div>

		<hr>

		<?php
			if(isset($_GET['access'])){
				$access = $_GET['access'];
				if($access == ""){
					print '<p>';
					print "\n			";
					print '<strong>Register an account</strong>';
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
				}
			}
			else{
				print '<p>';
				print "\n			";
				print '<strong>Registration is closed during development.</strong>';
				print "\n		";
				print '</p>';
				print "\n		";
			}
		?>
		
		<footer>
			<!-- <hr> -->
			<!-- Aleatoribus is an open source project licensed under version 2.0 of the Apache Licence. -->
		</footer>
	</div>

	</body>
</html>
