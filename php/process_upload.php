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

		$uploadDir = "/var/www/aleator.stream/html/uploads/" . $usrHash . "/";
		$tmpEncDir = "/var/www/aleator.stream/tmp/";

		if(!empty($_FILES["uploadedFile"])){
			$uploadedFile = $_FILES["uploadedFile"];

			if($uploadedFile["error"] !== UPLOAD_ERR_OK){
				print '<div align="center">';
				print '<p><strong>Whoops!</strong></p>';
				if($uploadedFile["error"] == 1 || $uploadedFile["error"] == 2){
					print '<p>File is larger than the server limit of 300MB.</p>';
				}
				else if($uploadedFile["error"] == 3){
					print '<p>File was only partially uploaded.</p>';
				}
				else if($uploadedFile["error"] == 4){
					print '<p>No file specified for upload.</p>';
				}
				else{
					print 'Unknown upload error. Please report this.</p>';
				}
				print '<p>Error code: ' . $uploadedFile["error"] . '</p>';
				print '</div>';
				header("refresh:2;url=/");
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

				$db_location = "";
				$db_user = "";
				$db_passwd = "";
				$db_name = "";
				$table = "uploads_" . $usrHash;

				if($_POST['upload_name'] != null){
					$upload_name = $_POST['upload_name'];
				}
				else{
					$upload_name = "Untitled";
				}

				if(isset($_POST['encryption'])){
					$tmp_dir = $tmpEncDir . $name;
					$enc_dir = $uploadDir . $name . ".enc";
					$enc_name = $name . ".enc";
					$cipher = $_POST['cipher'];
					$key = $_POST['key'];

					if(isset($_POST['decryption'])){
						$allowOnlineDecryption = 0;
					}
					else{
						$allowOnlineDecryption = 1;
					}
					
					$encSuccess = move_uploaded_file($uploadedFile["tmp_name"], $tmpEncDir . $name);
					if(!$encSuccess){ 
						print '<div align="center">';
						print '<p><strong>Whoops!</strong></p>';
						print '<p>An error occured: Unable to move file for encryption. </p>';
						print '</div>';
						header("refresh:1;url=/");
						exit();
					}
					else{
						$db = mysqli_connect($db_location, $db_user, $db_passwd, $db_name) or die(mysqli_error());

						$insert = "insert into $table values(null, '$upload_name', '$enc_name', 1, '$cipher', '$allowOnlineDecryption')";

						mysqli_query($db, $insert) or die(mysqli_error());

						shell_exec("openssl $cipher -a -salt -in $tmp_dir -out $enc_dir -pass pass:$key && rm -f $tmp_dir");

						chmod($uploadDir . $enc_name, 0644);

						print '<div align="center">';
						print '<p><strong>Uploaded encrypted file. Redirecting...</strong></p>';
						print '<p>' . 'ID: ' . $id . '</p>';
						print '</div>';
						header("refresh:2;url=/uploads.php");
						exit();
					}	
				}
				else{
					$plnSuccess = move_uploaded_file($uploadedFile["tmp_name"], $uploadDir . $name);
					if(!$plnSuccess){ 
						print '<div align="center">';
						print '<p><strong>Whoops!</strong></p>';
						print '<p>An error occured: Unable to save file. </p>';
						print '</div>';
						header("refresh:1;url=/");
						exit();
					}
					else{
						chmod($uploadDir . $name, 0644);

						$db = mysqli_connect($db_location, $db_user, $db_passwd, $db_name) or die(mysqli_error());

						$insert = "insert into $table values(null, '$upload_name', '$name', 0, 'null', 0)";

						mysqli_query($db, $insert) or die(mysqli_error());

						print '<div align="center">';
						print '<p><strong>Uploaded unencrypted file. Redirecting...</strong></p>';
						print '<p>' . 'ID: ' . $id . '</p>';
						print '</div>';
						header("refresh:2;url=/uploads.php");
						exit();
					}
				}
			}
		}
		else{
			print '<div align="center">';
			print '<p><strong>No file specified for upload!</strong></p>';
			print '<p>You must upload a file.</p>';
			print '</div>';
			header("refresh:2;url=/");
			exit();
		}
	}
?>
