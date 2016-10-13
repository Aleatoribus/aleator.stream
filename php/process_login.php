<?php
	include("/var/www/aleator.stream/html/inc/security.inc");
	session_start();
	if(!isset($_SESSION['username'])){
		if(isset($_POST['username']) && isset($_POST['password'])){
			$db_source = "127.0.0.1:3306";
			$db_user = "root";
			$db_passwd = "Rmit1234";
			$db_use = "aleatoribus";

			$username = $_POST['username'];
			$password = $_POST['password'];
	
			if($username != null && $password != null){
				$db = new mysqli($db_source, $db_user, $db_passwd, $db_use);
				$verification = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
				$verification->bind_param("s", $username);
				$verification->execute();
				$result = $verification->get_result();

				if($result->num_rows == 1){
					$row = $result->fetch_array();
					$hashed_password = $row['password'];

					if(password_verify($password, $hashed_password)){
						$_SESSION['username'] = $username;
						header("Location:/");
						exit();
					}
					else{
						header("Location:/?login=1");
						exit();
					}
				}
				else{
					header("Location:/?login=1");
					exit();
				}
			}
			else{
				header("Location:/?login=0");
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
			print "Username and password missing from this request.";
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
		print "You are already logged in.";
		print '</p>';
		include("/var/www/aleator.stream/html/inc/footer.inc");
		exit();
	}
?>
