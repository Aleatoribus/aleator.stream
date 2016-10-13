<?php
	include("/var/www/aleator.stream/html/inc/security.inc");
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
		print "You are not logged in. You must be logged in to upload content.";
		print '</p>';
		include("/var/www/aleator.stream/html/inc/footer.inc");
		exit();
	}
	else{
		$db_source = "127.0.0.1:3306";
		$db_user = "root";
		$db_passwd = "Rmit1234";
		$db_use = "aleatoribus";

		$db = new mysqli($db_source, $db_user, $db_passwd, $db_use);

		$username = $_SESSION['username'];
		$usrHash = md5(strtolower($username));
		$uploadDir = "/var/www/aleator.stream/uploads/" . $usrHash . "/";
		$tmpEncDir = "/var/www/aleator.stream/tmp/";

		if(!empty($_FILES["uploadedFile"])){
			$uploadedFile = $_FILES["uploadedFile"];

			if($uploadedFile["error"] !== UPLOAD_ERR_OK){
				$title = 'Error | Aleator Stream';
				include("/var/www/aleator.stream/html/inc/header.inc");
				print '<p>';
				print '<strong>Error</strong>';
				print '</p>';
				print '<p>';
				print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
				print '</p>';
				print '<p style="font-size: 90%; color: red;">';

				if($uploadedFile["error"] == 1 || $uploadedFile["error"] == 2){
					print 'File is larger than the server limit of 500MB.';
				}
				else if($uploadedFile["error"] == 3){
					print 'File was only partially uploaded.';
				}
				else if($uploadedFile["error"] == 4){
					print 'No file specified for upload.';
				}
				else{
					print 'Unknown upload error. Please report this.';
				}

				print '</p>';
				print '<p style="font-size: 80%; color: red;">Error code: ' . $uploadedFile["error"] . '</p>';
				include("/var/www/aleator.stream/html/inc/footer.inc");
				exit();
			}
			else{
				$plainName = $uploadedFile["name"];
				$parts = pathinfo($plainName);
				$id = md5(microtime() . $plainName . rand());
				$name = $id . "." . $parts["extension"];
				$check = $uploadDir . $name;

				while(file_exists($check)){
					$id = md5(microtime() . $plainName . rand());
					$name = $id . "." . $parts["extension"];
					$check = $uploadDir . $name;
				}

				$table = "uploads_" . $usrHash;

				if($_POST['upload_name'] != null){
					$upload_name = $_POST['upload_name'];
				}
				else{
					$upload_name = "Untitled";
				}

				if(isset($_POST['share'])){
					$shared = 1;
				}
				else{
					$shared = 0;
				}

				if(isset($_POST['encryption'])){
					$tmp_dir = $tmpEncDir . $name;
					$enc_dir = $uploadDir . $name . ".enc";
					$enc_name = $name . ".enc";
					$cipher = $_POST['cipher'];
					$key = $_POST['key'];
					$hashedKey = password_hash($key, PASSWORD_DEFAULT);

					if($key == null){
						$title = 'Error | Aleator Stream';
						include("/var/www/aleator.stream/html/inc/header.inc");
						print '<p>';
						print '<strong>Error</strong>';
						print '</p>';
						print '<p>';
						print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
						print '</p>';
						print '<p style="font-size: 90%; color: red;">';
						print "The file encryption key cannot be null.";
						print '</p>';
						include("/var/www/aleator.stream/html/inc/footer.inc");
						exit();
					}

					if(isset($_POST['decryption'])){
						$allowOnlineDecryption = 0;
					}
					else{
						$allowOnlineDecryption = 1;
					}
					
					$encSuccess = move_uploaded_file($uploadedFile["tmp_name"], $tmpEncDir . $name);
					if(!$encSuccess){ 
						$title = 'Error | Aleator Stream';
						include("/var/www/aleator.stream/html/inc/header.inc");
						print '<p>';
						print '<strong>Error</strong>';
						print '</p>';
						print '<p>';
						print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
						print '</p>';
						print '<p style="font-size: 90%; color: red;">';
						print "Unable to move file for encryption. Please report this.";
						print '</p>';
						include("/var/www/aleator.stream/html/inc/footer.inc");
						exit();
					}
					else{
						chmod($tmp_dir, 0700);

						$insert = $db->prepare("INSERT INTO $table VALUES(null, ?, ?, ?, 1, ?, ?, ?)");
						$insert->bind_param("ssssss", $upload_name, $enc_name, $shared, $cipher, $allowOnlineDecryption, $hashedKey);
						$insert->execute();

						$escapedCipher = escapeshellcmd($cipher);
						$escapedTmpDir = escapeshellcmd($tmp_dir);
						$escapedEncDir = escapeshellcmd($enc_dir);
						$escapedKey = escapeshellcmd($key);
						shell_exec("openssl $escapedCipher -a -salt -in $escapedTmpDir -out $escapedEncDir -pass pass:$escapedKey && rm -f $escapedTmpDir");

						chmod($enc_dir, 0700);

						$title = 'Uploaded | Aleator Stream';
						include("/var/www/aleator.stream/html/inc/header_blank.inc");
						print '<p>';
						print '<h2>Success!</h2>';
						print '</p>';
						print '<p>';
						print '<i class="fa fa-cog fa-spin" aria-hidden="true" style="font-size: 1000%;"></i>';
						print '</p>';
						print '<p style="font-size: 90%; color: green;">';
						print "File uploaded and encrypted!";
						print '</p>';
						print '<p style="font-size: 60%;">' . 'ID: ' . $id . '</p>';
						include("/var/www/aleator.stream/html/inc/footer_blank.inc");
						header("refresh:2;url=/uploads.php");
						exit();
					}	
				}
				else{
					$plnSuccess = move_uploaded_file($uploadedFile["tmp_name"], $uploadDir . $name);
					if(!$plnSuccess){ 
						$title = 'Error | Aleator Stream';
						include("/var/www/aleator.stream/html/inc/header.inc");
						print '<p>';
						print '<strong>Error</strong>';
						print '</p>';
						print '<p>';
						print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
						print '</p>';
						print '<p style="font-size: 90%; color: red;">';
						print "Unable to save file. Please report this.";
						print '</p>';
						include("/var/www/aleator.stream/html/inc/footer.inc");
						exit();
					}
					else{
						chmod($uploadDir . $name, 0700);

						$insertpln = $db->prepare("INSERT INTO $table VALUES(null, ?, ?, ?, 0, null, 1, null)");
						$insertpln->bind_param("sss", $upload_name, $name, $shared);
						$insertpln->execute();

						$title = 'Uploaded | Aleator Stream';
						include("/var/www/aleator.stream/html/inc/header_blank.inc");
						print '<p>';
						print '<h2>Success!</h2>';
						print '</p>';
						print '<p>';
						print '<i class="fa fa-cog fa-spin" aria-hidden="true" style="font-size: 1000%;"></i>';
						print '</p>';
						print '<p style="font-size: 90%; color: green;">';
						print "File uploaded!";
						print '</p>';
						print '<p style="font-size: 60%;">' . 'ID: ' . $id . '</p>';
						include("/var/www/aleator.stream/html/inc/footer_blank.inc");
						header("refresh:2;url=/uploads.php");
						exit();
					}
				}
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
			print '<p style="font-size: 90%; color: red;">';
			print "No file specified for upload.";
			print '</p>';
			include("/var/www/aleator.stream/html/inc/footer.inc");
			exit();
		}
	}
?>
