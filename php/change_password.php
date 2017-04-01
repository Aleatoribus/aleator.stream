<?php
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
		if(!isset($_POST['current_password']) || !isset($_POST['new_password'])){
			$title = 'Error | Aleator Stream';
			include("/var/www/aleator.stream/html/inc/header.inc");
			print '<p>';
			print '<strong>Error</strong>';
			print '</p>';
			print '<p>';
			print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
			print '</p>';
			print '<p style="font-size: 90%; color: red">';
			print "Password/s missing from request.";
			print '</p>';
			include("/var/www/aleator.stream/html/inc/footer.inc");
			exit();
		}
		else{
			if($_POST['current_password'] == null || $_POST['new_password'] == null){
				$title = 'Error | Aleator Stream';
				include("/var/www/aleator.stream/html/inc/header.inc");
				print '<p>';
				print '<strong>Error</strong>';
				print '</p>';
				print '<p>';
				print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
				print '</p>';
				print '<p style="font-size: 90%; color: red">';
				print "Your password cannot be null.";
				print '</p>';
				include("/var/www/aleator.stream/html/inc/footer.inc");
				exit();
			}
			else{
				$db_source = "";
				$db_user = "";
				$db_passwd = "";
				$db_use = "";

				$username = $_SESSION['username'];
				$current_password = $_POST['current_password'];
				$new_password = $_POST['new_password'];
				$hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

				$db = new mysqli($db_source, $db_user, $db_passwd, $db_use);

				$verification = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
				$verification->bind_param("s", $username);
				$verification->execute();
				$result = $verification->get_result();

				if($result->num_rows == 1){
					$row = $result->fetch_array();
					$hashed_current_password = $row['password'];

					if(password_verify($current_password, $hashed_current_password)){
						$update = $db->prepare("UPDATE users SET password = ? where username = ? and password = ?");
						$update->bind_param("sss", $hashed_new_password, $username, $hashed_current_password);
						$update->execute();

						$title = 'Success | Aleator Stream';
						include("/var/www/aleator.stream/html/inc/header_blank.inc");
						print '<p>';
						print '<h2>Success!</h2>';
						print '</p>';
						print '<p>';
						print '<i class="fa fa-cog fa-spin" aria-hidden="true" style="font-size: 1000%;"></i>';
						print '</p>';
						print '<p style="font-size: 90%; color: green;">';
						print "Password changed.";
						print '</p>';
						include("/var/www/aleator.stream/html/inc/footer_blank.inc");
						session_destroy();
						header("refresh:1;url=/?register=2");
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
						print "Invalid current password.";
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
