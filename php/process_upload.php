<?php
	session_start();
		if(!isset($_SESSION['username'])){
			header("Location:login.php?login=2");
			exit(0);
		}

	define("UPLOAD_DIR", "/var/www/aleator.stream/html/uploads/");

	if(!empty($_FILES["uploadedFile"])){
		$uploadedFile = $_FILES["uploadedFile"];

		if($uploadedFile["error"] !== UPLOAD_ERR_OK){
			echo "An error occured";
			exit;
		}

		//ensure a safe filename
		$name = preg_replace("/[^A-Z0-9._-]/i", "_", $uploadedFile["name"]);

		//do not overwrite an existing file
		$i = 0;
		$parts = pathinfo($name);
		while (file_exists(UPLOAD_DIR . $name)) {
			$i++;
			$name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
		}

		//preserve file from temporary directory
		$success = move_uploaded_file($uploadedFile["tmp_name"], UPLOAD_DIR . $name);
		if(!$success){ 
            echo "Unable to save file.";
            exit;
        }
        else{
			$db_location = "127.0.0.1:3306";
			$db_user = "root";
			$db_passwd = 'Rmit1234';
			$db_name = "aleatoribus";
			$table = "uploads";

			$db = mysqli_connect($db_location, $db_user, $db_passwd, $db_name) or die(mysqli_error());

			$upload_name = $_POST['upload_name'];
			$username = $_SESSION['username'];

			$insert = "insert into $table values(null, '$upload_name', '$name', '$username')";

			mysqli_query($db, $insert) or die(mysqli_error());

			print '<p align="center"><strong>Uploaded. Redirecting...</strong></p>';
			header("refresh:1;url=/files.php");
        }

        //set proper permissions on the new file
        chmod(UPLOAD_DIR . $name, 0644);
    }
    else{
        echo "No file uploaded!";
    }
