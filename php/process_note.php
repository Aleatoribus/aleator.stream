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
		print "You are not logged in. You must be logged in to process a note.";
		print '</p>';
		include("/var/www/aleator.stream/html/inc/footer.inc");
		exit();
	}
	else{
		$db_source = "";
		$db_user = "";
		$db_passwd = "";
		$db_use = "";

		$db = new mysqli($db_source, $db_user, $db_passwd, $db_use);

		$username = $_SESSION['username'];
		$usrHash = md5(strtolower($username));

		if($_POST['title'] != null){
			$title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
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
			print "You must provide a title with your note.";
			print '</p>';
			include("/var/www/aleator.stream/html/inc/footer.inc");
			exit();
		}

		if($_POST['content'] != null){
			$content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
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
			print "Empty note. Please write something.";
			print '</p>';
			include("/var/www/aleator.stream/html/inc/footer.inc");
			exit();
		}
		
		$name = md5(microtime() . $title . rand()) . ".txt";

		$noteDir = "/var/www/aleator.stream/html/notes/" . $usrHash . "/" . $name;

		while(file_exists("$noteDir")){
			$name = md5(microtime() . $title . rand()) . ".txt";
		}

		if(isset($_POST['publicity'])){
			$table = "notes";
		}
		else{
			$table = "notes_" . $usrHash;
		}

		if(isset($_POST['encryption'])){
			if($_POST['key'] != null && $_POST['cipher'] != null){
				$key = $_POST['key'];

				$supported_ciphers = array("aes-256-cbc", "aes-192-cbc", "aes-128-cbc", "camellia-256-cbc", "camellia-192-cbc", "camellia-128-cbc");
				if(in_array($_POST['cipher'], $supported_ciphers)){
					$cipher = $_POST['cipher'];
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
					print '<p style="font-size: 90%; color: red;">';
					print "Unsupported cipher specified.";
					print '</p>';
					include("/var/www/aleator.stream/html/inc/footer.inc");
					exit();
				}

				$tmpDir = "/var/www/aleator.stream/tmp/" . $name;

				$note = fopen("$tmpDir", "w") or die("Error writing note to file.");
				fwrite($note, $content);
				fclose($note);

				$escapedCipher = escapeshellcmd($cipher);
				$escapedKey = escapeshellcmd($key);
				$escapedTmpDir = escapeshellcmd($tmpDir);
				$escapedNoteDir = escapeshellcmd($noteDir);
				shell_exec("openssl $escapedCipher -a -salt -in $escapedTmpDir -out $escapedNoteDir -pass pass:$escapedKey && rm -f $escapedTmpDir");

				$insert = $db->prepare("INSERT INTO $table VALUES(null, ?, ?, ?, 1, ?)");
				$insert->bind_param("ssss", $title, $name, $username, $cipher);
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
				print "Key and/or cipher cannot be null.";
				print '</p>';
				include("/var/www/aleator.stream/html/inc/footer.inc");
				exit();
			}
		}
		else{
			$note = fopen("$noteDir", "w") or die("Error writing note to file.");
			fwrite($note, $content);
			fclose($note);

			$insert = $db->prepare("INSERT INTO $table VALUES(null, ?, ?, ?, 0, null)");
			$insert->bind_param("sss", $title, $name, $username);
		}
		chmod($noteDir, 0644);

		$insert->execute();

		$title = 'Published | Aleator Stream';
		include("/var/www/aleator.stream/html/inc/header_blank.inc");
		print '<p>';
		print '<h2>Success!</h2>';
		print '</p>';
		print '<p>';
		print '<i class="fa fa-cog fa-spin" aria-hidden="true" style="font-size: 1000%;"></i>';
		print '</p>';
		print '<p style="font-size: 90%; color: green;">';
		print "Note published!";
		print '</p>';
		include("/var/www/aleator.stream/html/inc/footer_blank.inc");
		header("refresh:2;url=/notes.php");
		exit();
	}
?>
