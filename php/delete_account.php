<?php
	include("/var/www/aleator.stream/html/inc/security.inc");
	session_start();
	if(!isset($_SESSION['username'])){
		$title = 'Error | Aleator Stream';
		include("/var/www/aleator.stream/html/inc/header.inc");
		print '<p>';
		print '<strong>Error</strong>';
		print '</p>';
		print '<p>';
		print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
		print '</p>';
		print '<p style="font-size: 90%; color: red">';
		print "You must be logged in to view this page.";
		print '</p>';
		include("/var/www/aleator.stream/html/inc/footer.inc");
		exit();
	}
	else{
		if(!isset($_POST['password'])){
			$title = 'Error | Aleator Stream';
			include("/var/www/aleator.stream/html/inc/header.inc");
			print '<p>';
			print '<strong>Error</strong>';
			print '</p>';
			print '<p>';
			print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
			print '</p>';
			print '<p style="font-size: 90%; color: red">';
			print "You must be logged in to view this page.";
			print '</p>';
			include("/var/www/aleator.stream/html/inc/footer.inc");
			exit();
		}
		else{
			if($_POST['password'] == null){
				header("Location:/profile.php?config=account&error=1");
				exit();
			}
			else{
				$db_source = "";
				$db_user = "";
				$db_passwd = "";
				$db_use = "";

				$username = $_SESSION['username'];
				$password = $_POST['password'];

				$db = new mysqli($db_source, $db_user, $db_passwd, $db_use);
				$verification = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
				$verification->bind_param("s", $username);
				$verification->execute();
				$result = $verification->get_result();

				if($result->num_rows == 1){
					$row = $result->fetch_array();
					$hashed_password = $row['password'];

					if(password_verify($password, $hashed_password)){
						$usrHash = md5(strtolower($username));
						$uploadsDir = "/var/www/aleator.stream/uploads/" . $usrHash . "/";
						$notesDir = "/var/www/aleator.stream/html/notes/" . $usrHash . "/";
						$uploadsTable = "uploads_" . $usrHash;
						$notesTable = "notes_" . $usrHash;

						/* Delete uploads directory */
						$escapedUploadsDir = escapeshellcmd($uploadsDir);
						shell_exec("rm -rf $escapedUploadsDir");

						/* Delete notes directory */
						$escapedNotesDir = escapeshellcmd($notesDir);
						shell_exec("rm -rf $escapedNotesDir");

						/* Delete MySQL tables */
						$deleteUploadsTable = "drop table $uploadsTable";
						mysqli_query($db, $deleteUploadsTable) or die(mysqli_error());

						$deleteNotesTable = "drop table $notesTable";
						mysqli_query($db, $deleteNotesTable) or die(mysqli_error());

						/* Delete public notes from table */
						$deletePublicNotes = $db->prepare("DELETE FROM notes WHERE uploader = ?");
						$deletePublicNotes->bind_param("s", $username);
						$deletePublicNotes->execute();

						/* Delete user */
						$deleteUser = $db->prepare("DELETE FROM users WHERE username = ?");
						$deleteUser->bind_param("s", $username);
						$deleteUser->execute();

						$title = 'Goodbye | Aleator Stream';
						include("/var/www/aleator.stream/html/inc/header.inc");
						print '<p>';
						print '<strong>Goodbye</strong>';
						print '</p>';
						print '<p>';
						print '<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/q27u5YvgEdU?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>';
						print '</p>';
						print '<p style="font-size: 90%">';
						print "Account deleted! Goodbye.";
						print '</p>';
						include("/var/www/aleator.stream/html/inc/footer.inc");
						session_destroy();
						exit();
					}
					else{
						$title = 'Error | Aleator Stream';
						include("/var/www/aleator.stream/html/inc/header.inc");
						print '<p>';
						print '<strong>Error</strong>';
						print '</p>';
						print '<p>';
						print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
						print '</p>';
						print '<p style="font-size: 90%; color: red">';
						print "Invalid user password.";
						print '</p>';
						include("/var/www/aleator.stream/html/inc/footer.inc");
						exit();
					}
				}
				else{
					$title = 'Error | Aleator Stream';
					include("/var/www/aleator.stream/html/inc/header.inc");
					print '<p>';
					print '<strong>Error</strong>';
					print '</p>';
					print '<p>';
					print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
					print '</p>';
					print '<p style="font-size: 90%; color: red">';
					print "User verification error. Please report this.";
					print '</p>';
					include("/var/www/aleator.stream/html/inc/footer.inc");
					exit();
				}
			}
		}
	}
?>
