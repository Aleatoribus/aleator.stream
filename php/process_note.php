<?php
	include("inc/security.inc");
	session_start();
	if(!isset($_SESSION['username'])){
		header("Location:/");
		exit();
	}
	else{
		$username = $_SESSION['username'];
		$usrHash = md5(strtolower($username));
		$title = $_POST['title'];
		$name = md5(microtime() . $title . rand()) . ".txt";

		$noteDir = "/var/www/aleator.stream/html/notes/" . $usrHash . "/" . $name;

		while(file_exists("$noteDir")){
			$name = md5(microtime() . $title . rand()) . ".txt";
		}

		$db_location = "";
		$db_user = "";
		$db_passwd = "";
		$db_name = "";

		if(isset($_POST['publicity'])){
			$table = "notes"; //public notes table
		}
		else{
			$table = "notes_" . $usrHash; //private notes table
		}

		$db = mysqli_connect($db_location, $db_user, $db_passwd, $db_name) or die(mysqli_error());

		if(isset($_POST['encryption'])){
			if($_POST['key'] != null && $_POST['cipher'] != null){
				$key = $_POST['key'];
				$cipher = $_POST['cipher'];

				$tmpDir = "/var/www/aleator.stream/tmp/" . $name;

				$note = fopen("$tmpDir", "w") or die("Error.");
				$content = $_POST['content'];
				fwrite($note, $content);
				fclose($note);

				shell_exec("openssl $cipher -a -salt -in $tmpDir -out $noteDir -pass pass:$key && rm -f $tmpDir");

				$insert = "insert into $table values(null, '$title', '$name', '$username', 1, '$cipher')";

				//chmod($uploadDir . $enc_name, 0644);
			}
			else{
				print "Key and/or cipher cannot be null.";
				header("refresh:1;url=/");
				exit();
			}
		}
		else{
			$note = fopen("$noteDir", "w") or die("Error.");
			$content = $_POST['content'];
			fwrite($note, $content);
			fclose($note);

			$insert = "insert into $table values(null, '$title', '$name', '$username', 0, 'null')";
		}

		mysqli_query($db, $insert) or die(mysqli_error("Error"));

		print "Done!";
		header("refresh:1;url=/notes.php");
		exit();
	}
?>
