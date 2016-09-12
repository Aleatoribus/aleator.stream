<?php
	include("inc/security.inc");
	session_start();
	if(!isset($_SESSION['username'])){
		if(isset($_POST['username']) && isset($_POST['password'])){
			$username = $_POST['username'];
			$password = $_POST['password'];
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);
			
			if($username != null && $password != null){
				$db_source = "";
				$db_user = "";
				$db_passwd = "";
				$db_use = "";
				
				$db = mysqli_connect($db_source, $db_user, $db_passwd, $db_use) or die(mysqli_error());
				
				$verify = "select * from users where username='$username'";
				$verification = mysqli_query($db, $verify) or die(mysqli_error($db));
				
				if(mysqli_num_rows($verification) > 0){
					header("Location:/register.php?error=1");
					exit();
				}
				else{
					$usrHash = md5(strtolower($username));

					if(!mkdir("/var/www/aleator.stream/html/uploads/$usrHash", 0755)){
						print "Error: Failed to create user uploads directory. Please report this.";
						exit();
					}
					else{
						if(!mkdir("/var/www/aleator.stream/html/notes/$usrHash", 0755)){
							print "Error: Failed to create user notes directory. Please report this.";
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
							encrypted boolean,
							cipher varchar(16),
							allow_server_decryption boolean
							)";
							mysqli_query($db, $createUserUploadTable) or die(mysqli_error($db));

							$userNoteTable = "notes_" . $usrHash;
							$createUserNoteTable = "create table if not exists $userNoteTable(
							id serial primary key,
							title varchar(64), 
							note_dir varchar(128),
							uploader varchar(32),
							encrypted boolean
							)";

							mysqli_query($db, $createUserNoteTable) or die(mysqli_error($db));

							header("Location:/index.php?register=1");
							exit();
						}
					}
				}
			}
			else{
				header("Location:/register.php?error=0");
				exit();
			}
		}
		else{
			header("Location:/");
			exit();
		}
	}
	else{
		print "You cannot register an account when you already have one!";
		header("refresh:2;url=/");
		exit();
	}
?>
