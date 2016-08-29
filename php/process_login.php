<?php
	if(isset($_POST['username']) && isset($_POST['password'])){
		$username = $_POST['username'];
		$password = $_POST['password'];
	
		if($username != null && $password != null){
			$db_source = "";
			$db_user = "";
			$db_passwd = "";
			$db_use = "aleatoribus";
			
			$db = mysqli_connect($db_source, $db_user, $db_passwd, $db_use) or die(mysqli_error());
			
			$q = "select * from users where username='$username' and password=SHA('$password')";
			$results = mysqli_query($db, $q) or die(mysqli_error($db));
			
			if(mysqli_num_rows($results) > 0){
				session_start();
				$_SESSION['username'] = $username;
				header("Location:/");
				exit(0);
			}
			else{
				header("Location:/?login=0");
			}
		}
		else{
			header("Location:/?login=1");
		}
	}
	else{
		print("Error.");
		//Redirect to error page.
	}
?>
