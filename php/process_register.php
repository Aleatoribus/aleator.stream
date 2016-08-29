<?php
	if(isset($_POST['username']) && isset($_POST['password'])){
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		if($username != null && $password != null){
			$db_source = "127.0.0.1:3306";
			$db_user = "root";
			$db_passwd = "Rmit1234";
			$db_use = "aleatoribus";
			
			$db = mysqli_connect($db_source, $db_user, $db_passwd, $db_use) or die(mysqli_error());
			
			//verify the username does not already exist
			$verify = "select * from users where username='$username'";
			$verification = mysqli_query($db, $verify) or die(mysqli_error($db));
			
			if(mysqli_num_rows($verification) > 0){
				header("Location:/register.php?exists=1");
				exit(0);
			}
			else{
				$insert = "insert into users values(null,'$username', SHA('$password'), 'free')";
				mysqli_query($db, $insert) or die(mysqli_error($db));
				header("Location:/index.php?register=1");
			}
		}
		else{
			print "Username and/or password cannot be null.";
			header("refresh:2;url=/register.php");
		}
	}
	else{
		print("No data");
		header("refresh:1;url=/register.php");
	}
?>
