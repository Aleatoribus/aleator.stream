<?php
	include("inc/security.inc");
	session_start();
	if(!isset($_SESSION['username'])){
		if(isset($_POST['username']) && isset($_POST['password'])){
			$db_source = "";
			$db_user = "";
			$db_passwd = "";
			$db_use = "";
			
			$db = mysqli_connect($db_source, $db_user, $db_passwd, $db_use) or die(mysqli_error());

			$username = mysqli_real_escape_string($db, $_POST['username']);
			$password = mysqli_real_escape_string($db, $_POST['password']);
	
			if($username != null && $password != null){
				$q = "select * from users where username='$username'";
				$results = mysqli_query($db, $q) or die(mysqli_error($db));
				
				if(mysqli_num_rows($results) == 1){
					$row = mysqli_fetch_array($results);
					$hashed_password = $row['password'];

					if(password_verify($password, $hashed_password)){
						//session_start();
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
			header("Location:/");
			exit();
		}
	}
	else{
		print "You're already logged in!";
		header("refresh:2;url=/");
		exit();
	}
?>
