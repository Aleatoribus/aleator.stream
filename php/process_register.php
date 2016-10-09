<?php
	include("inc/security.inc");
	session_start();
	if(!isset($_SESSION['username'])){
		if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password_verify'])){
			if($_POST['password'] != $_POST['password_verify']){
				$title = 'Error | Aleator Stream';
				include("/var/www/aleator.stream/html/inc/header.inc");
				print '<p>';
				print '<strong>Error</strong>';
				print '</p>';
				print '<p>';
				print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
				print '</p>';
				print '<p style="font-size: 90%; color: red">';
				print "Passwords do not match.";
				print '</p>';
				include("/var/www/aleator.stream/html/inc/footer.inc");
				exit();
			}

			$db_source = "";
			$db_user = "";
			$db_passwd = "";
			$db_use = "";
			
			$db = mysqli_connect($db_source, $db_user, $db_passwd, $db_use) or die(mysqli_error());
			
			$username = mysqli_real_escape_string($db, $_POST['username']);
			$password = mysqli_real_escape_string($db, $_POST['password']);
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);
			
			if($username != null && $password != null){
				$verify = "select * from users where username='$username'";
				$verification = mysqli_query($db, $verify) or die(mysqli_error($db));
				
				if(mysqli_num_rows($verification) > 0){
					$title = 'Error | Aleator Stream';
					include("/var/www/aleator.stream/html/inc/header.inc");
					print '<p>';
					print '<strong>Error</strong>';
					print '</p>';
					print '<p>';
					print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
					print '</p>';
					print '<p style="font-size: 90%; color: red">';
					print "An account with that username already exists.";
					print '</p>';
					include("/var/www/aleator.stream/html/inc/footer.inc");
					exit();
				}
				else{
					$usrHash = md5(strtolower($username));

					if(!mkdir("/var/www/aleator.stream/uploads/$usrHash", 0700)){
						$title = 'Error | Aleator Stream';
						include("/var/www/aleator.stream/html/inc/header.inc");
						print '<p>';
						print '<strong>Error</strong>';
						print '</p>';
						print '<p>';
						print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
						print '</p>';
						print '<p style="font-size: 90%; color: red">';
						print "Failed to create user uploads directory. Please report this.";
						print '</p>';
						include("/var/www/aleator.stream/html/inc/footer.inc");
						exit();
					}
					else{
						if(!mkdir("/var/www/aleator.stream/html/notes/$usrHash", 0755)){
							$title = 'Error | Aleator Stream';
							include("/var/www/aleator.stream/html/inc/header.inc");
							print '<p>';
							print '<strong>Error</strong>';
							print '</p>';
							print '<p>';
							print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
							print '</p>';
							print '<p style="font-size: 90%; color: red">';
							print "Failed to create user notes directory. Please report this.";
							print '</p>';
							include("/var/www/aleator.stream/html/inc/footer.inc");
							exit();
						}
						else{
							$insertUser = "insert into users values(null,'$username', '$hashed_password', 'free')";
							mysqli_query($db, $insertUser) or die(mysqli_error($db));
							$userUploadTable = "uploads_" . $usrHash;
							$createUserUploadTable = "create table if not exists $userUploadTable(
							id serial primary key,
							upload_name varchar(64), 
							upload_file varchar(128),
							shared boolean,
							encrypted boolean,
							cipher varchar(16),
							allow_server_decryption boolean,
							password varchar(255)
							)";
							mysqli_query($db, $createUserUploadTable) or die(mysqli_error($db));

							$userNoteTable = "notes_" . $usrHash;
							$createUserNoteTable = "create table if not exists $userNoteTable(
							id serial primary key,
							title varchar(64), 
							note_dir varchar(128),
							uploader varchar(32),
							encrypted boolean,
							cipher varchar(16)
							)";

							mysqli_query($db, $createUserNoteTable) or die(mysqli_error($db));

							header("Location:/index.php?register=1");
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
				print '<p style="font-size: 90%; color: red">';
				print "Username and/or password cannot be null.";
				print '</p>';
				include("/var/www/aleator.stream/html/inc/footer.inc");
				exit();
			}
		}
		else{
			header("Location:/");
			exit();
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
		print '<p style="font-size: 90%; color: red">';
		print "Please log out of your current account before creating a new one.";
		print '</p>';
		include("/var/www/aleator.stream/html/inc/footer.inc");
		exit();
	}
?>
