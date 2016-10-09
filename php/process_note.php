<?php
	include("inc/security.inc");
	session_start();
	if(!isset($_SESSION['username'])){
		header("Location:/");
		exit();
	}
	else{
		$db_location = "";
		$db_user = "";
		$db_passwd = '';
		$db_name = "";

		$db = mysqli_connect($db_location, $db_user, $db_passwd, $db_name) or die(mysqli_error());

		$username = mysqli_real_escape_string($db, $_SESSION['username']);
		$usrHash = md5(strtolower($username));

		if($_POST['title'] != null){
			$title = mysqli_real_escape_string($db, $_POST['title']);
		}
		else{
			print "You must provide a title.";
			header("refresh:1;url=/");
			exit();
		}

		if($_POST['content'] != null){
			$content = $_POST['content'];
		}
		else{
			print "You must write something.";
			header("refresh:1;url=/");
			exit();
		}
		
		$name = md5(microtime() . $title . rand()) . ".txt";

		$noteDir = "/var/www/aleator.stream/html/notes/" . $usrHash . "/" . $name;

		while(file_exists("$noteDir")){
			$name = md5(microtime() . $title . rand()) . ".txt";
		}

		if(isset($_POST['publicity'])){
			$table = "notes"; //public notes table
		}
		else{
			$table = "notes_" . $usrHash; //private notes table
		}

		if(isset($_POST['encryption'])){
			if($_POST['key'] != null && $_POST['cipher'] != null){
				$key = mysqli_real_escape_string($db, $_POST['key']);
				$cipher = mysqli_real_escape_string($db, $_POST['cipher']);

				$tmpDir = "/var/www/aleator.stream/tmp/" . $name;

				$note = fopen("$tmpDir", "w") or die("Error.");
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
