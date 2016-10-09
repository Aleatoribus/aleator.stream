<?php
	include("inc/security.inc");
	session_start();
	if(!isset($_SESSION['username'])){
		header("Location:/");
		exit();
	}
	else{
		if(!isset($_POST['current_password']) || !isset($_POST['new_password'])){
			header("Location:/");
			exit();
		}
		else{
			if($_POST['current_password'] == null || $_POST['new_password'] == null){
				header("Location:/profile.php?config=password&error=0");
				exit();
			}
			else{
				$db_source = "";
				$db_user = "";
				$db_passwd = "";
				$db_use = "";
				
				$db = mysqli_connect($db_source, $db_user, $db_passwd, $db_use) or die(mysqli_error());

				$username = mysqli_real_escape_string($db, $_SESSION['username']);
				$current_password = mysqli_real_escape_string($db, $_POST['current_password']);
				$new_password = mysqli_real_escape_string($db, $_POST['new_password']);
				$hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

				$q = "select * from users where username='$username'";
				$results = mysqli_query($db, $q) or die(mysqli_error($db));

				if(mysqli_num_rows($results) == 1){
					$row = mysqli_fetch_array($results);
					$hashed_current_password = $row['password'];

					if(password_verify($current_password, $hashed_current_password)){
						$update = "update users set password='$hashed_new_password' where username='$username' and password='$hashed_current_password'";
						mysqli_query($db, $update) or die(mysqli_error($db));

						print "Password changed!";

						session_destroy();
						header("refresh:1;url=/?register=2");
						exit();
					}
					else{
						header("Location:/profile.php?config=password&error=1");
						exit();
					}
				}
				else{
					header("Location:/profile.php?config=password&error=1");
					exit();
				}
			}
		}
	}
?>
