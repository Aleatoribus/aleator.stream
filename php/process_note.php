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

		$insert = "insert into $table values(null, '$title', '$name', '$username', 0)";

		mysqli_query($db, $insert) or die(mysqli_error("Error"));

		$note = fopen("$noteDir", "w") or die("Error.");
		$content = $_POST['content'];
		fwrite($note, $content);
		fclose($note);

		print "Done!";
		header("refresh:1;url=/notes.php");
		exit();
	}
?>
