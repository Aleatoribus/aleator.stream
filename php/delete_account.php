<?php
	include("inc/security.inc");
	session_start();
	if(!isset($_SESSION['username'])){
		header("Location:/");
		exit();
	}
	else{
		if(!isset($_POST['password'])){
			header("Location:/");
			exit();
		}
		else{
			if($_POST['password'] == null){
				header("Location:/profile.php?config=account&error=1");
				exit();
			}
			else{
				$username = $_SESSION['username'];
				$password = $_POST['password'];

				$db_source = "";
				$db_user = "";
				$db_passwd = "";
				$db_use = "";
				
				$db = mysqli_connect($db_source, $db_user, $db_passwd, $db_use) or die(mysqli_error());
				
				$q = "select * from users where username='$username'";
				$results = mysqli_query($db, $q) or die(mysqli_error($db));

				if(mysqli_num_rows($results) == 1){
					$row = mysqli_fetch_array($results);
					$hashed_password = $row['password'];

					if(password_verify($password, $hashed_password)){
						$usrHash = md5(strtolower($username));
						$uploadsDir = "/var/www/aleator.stream/html/uploads/" . $usrHash . "/";
						$notesDir = "/var/www/aleator.stream/html/notes/" . $usrHash . "/";
						$uploadsTable = "uploads_" . $usrHash;
						$notesTable = "notes_" . $usrHash;

						/* Delete uploads directory */
						shell_exec("rm -rf $uploadsDir");

						/* Delete notes directory */
						shell_exec("rm -rf $notesDir");

						/* Delete MySQL tables */
						$deleteUploadsTable = "drop table $uploadsTable";
						mysqli_query($db, $deleteUploadsTable) or die(mysqli_error($db));

						$deleteNotesTable = "drop table $notesTable";
						mysqli_query($db, $deleteNotesTable) or die(mysqli_error($db));

						/* Delete public notes from table */
						$deletePublicNotes = "delete from notes where uploader='$username'";
						mysqli_query($db, $deletePublicNotes) or die(mysqli_error($db));

						/* Delete user */
						$deleteUser = "delete from users where username='$username'";
						mysqli_query($db, $deleteUser) or die(mysqli_error($db));

						print "Account deleted. Goodbye!";

						session_destroy();
						header("refresh:1;url=/");
						exit();
					}
					else{
						header("Location:/profile.php?config=account&error=1");
						exit();
					}
				}
				else{
					header("Location:/profile.php?config=account&error=1");
					exit();
				}
			}
		}
	}
?>
