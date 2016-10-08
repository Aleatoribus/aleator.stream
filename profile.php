<?php
	$title = 'Profile | Aleator Stream';
	include("inc/header.inc");
?>
		
		<?php
			if(isset($_SESSION['username'])){
				$username = $_SESSION['username'];
				$usrHash = md5(strtolower($username));

				if(!isset($_GET['config'])){				
					print "<h2>User profile: $username</h2>";
					print '<p style="font-size: 80%; ">';
					print "This page is only visible to you.";
					print '</p>';

					$db_location = "";
					$db_user = "";
					$db_passwd = '';
					$db_name = "";
					$db = mysqli_connect($db_location, $db_user, $db_passwd, $db_name) or die(mysqli_error());
					
					/* Uploads */

					print '<p><strong>Upload statistics</strong></p>';

					$uploadsTable = "uploads_" . $usrHash;
					$qUploads = "select * from $uploadsTable";
					$rUploads = mysqli_query($db, $qUploads) or die(mysqli_error());

					$uploadDir = "/var/www/aleator.stream/uploads/" . $usrHash . "/";
					$total = 0;
					$totalEnc = 0;
					
					if(mysqli_num_rows($rUploads) > 0){
						while($row = mysqli_fetch_array($rUploads)){
							$bytes =  $bytes + filesize($uploadDir . $row['upload_file']);
							$total++;
							if($row['encrypted'] == 1){
								$totalEnc++;
							}
						}

						$usage = number_format($bytes / 1048576, 2);

						print '<p>';
						print "<meter align='left' value='$usage' min='0' max='2000'></meter>";
						print '</p>';
						if(($usage/5000)*100 > 90){
						print '<p style="font-size: 85%; color: red;">You\'re approaching the end of your storage quota!</p>';
						}
						print '<p style="font-size: 85%; ">';
						print "Disk usage: " . $usage . '/2000MB';
						print '</p>';
						print '<p style="font-size: 85%; ">';
						print "Number of uploads: " . $total;
						print '</p>';
						print '<p style="font-size: 85%; ">';
						print "Number of encrypted uploads: " . $totalEnc ."/" . $total;
						print '</p>';
					}
					else{
						print '<p style="font-size: 80%; ">';
						print "You've uploaded nothing!";
						print '</p>';
					}

					/* Notes */

					print '<p><strong>Note statistics</strong></p>';

					$notesTablePrivate = "notes_" . $usrHash;
					$qNotesPrivate = "select * from $notesTablePrivate";
					$rNotesPrivate = mysqli_query($db, $qNotesPrivate) or die(mysqli_error());

					$noteDir = "/var/www/aleator.stream/html/notes/" . $usrHash . "/";
					$totalNotesPrivate = 0;
					$totalEncNotesPrivate = 0;
					
					if(mysqli_num_rows($rNotesPrivate) > 0){
						while($row = mysqli_fetch_array($rNotesPrivate)){
							$totalNotesPrivate++;
							if($row['encrypted'] == 1){
								$totalEncNotes++;
							}
						}
						print '<p>';
						print '<strong style="font-size: 85%; ">Private</strong>';
						print '</p>';
						print '<p style="font-size: 85%; ">';
						print "Number of notes: " . $totalNotesPrivate;
						print '</p>';
						print '<p style="font-size: 85%; ">';
						print "Number of encrypted notes: " . $totalEncNotesPrivate ."/" . $totalNotesPrivate;
						print '</p>';
					}
					else{
						print '<strong style="font-size: 85%; ">Private</strong>';
						print '<p style="font-size: 80%; ">';
						print "You've published zero private notes!";
						print '</p>';
					}

					$qNotesPublic = "select * from notes where uploader='$username'";
					$rNotesPublic = mysqli_query($db, $qNotesPublic) or die(mysqli_error());

					$totalNotesPublic = 0;
					$totalEncNotesPublic = 0;
					
					if(mysqli_num_rows($rNotesPublic) > 0){
						while($row = mysqli_fetch_array($rNotesPublic)){
							$totalNotesPublic++;
							if($row['encrypted'] == 1){
								$totalEncNotesPublic++;
							}
						}
						print '<strong style="font-size: 85%; ">Public</strong>';
						print '<p style="font-size: 85%; ">';
						print "Number of notes: " . $totalNotesPublic;
						print '</p>';
						print '<p style="font-size: 85%; ">';
						print "Number of encrypted notes: " . $totalEncNotesPublic ."/" . $totalNotesPublic;
						print '</p>';
					}
					else{
						print '<strong style="font-size: 85%; ">Public</strong>';
						print '<p style="font-size: 80%; ">';
						print "You've published zero public notes!";
						print '</p>';
					}

					print '<strong>User preferences</strong>';
					print '<p style="font-size: 80%; ">';
					print '<a href="?config=password">Change password</a>';
					print '</p>';
					print '<p style="font-size: 80%; ">';
					print '<a href="?config=account">Delete account</a>';
					print '</p>';
				}
				else{
					if($_GET['config'] == "password"){
						print "<h2>Change password</h2>";
						print '<p style="font-size: 80%; ">';
						print "Password changes are instantaneous. You will be immediately logged out, and asked to log in with your new password. ";
						print '<p style="font-size: 80%; ">';
						print "Password recovery is not yet implemented. Do not forget your password!";
						print '</p>';
						print '<strong>Change your password</strong>';
						if(isset($_GET['error'])){
							print '<p style="color:red; font-size:85%;">';
							$error = $_GET['error'];
							if($error == 0){
								print "You must enter both your current and new password."; 
							}
							else if($error == 1){
								print "Invalid current password."; 
							}
							print '</p>' . "\n		\n		";
						}
						print '<form method="post" action="php\change_password.php">';
						print '<p style="font-size: 80%; ">';
						print 'Current password: <input type="password" name="current_password"/>';
						print '</p>';
						print '<p style="font-size: 80%; ">';
						print 'New password: <input type="password" name="new_password"/>';
						print '</p>';
						print '<input type="submit" value="Change password" name="submit"/>';
						print '</form>';
					}
					else if($_GET['config'] == "account"){
						print "<h2>Delete account</h2>";
						print '<p style="font-size: 80%; ">';
						print "Account deletion is permanent and irreversible. All of your data will be deleted without any chance of recovery.";
						print '<p style="font-size: 80%; ">';
						print "This includes:";
						print '</p>';
						print '<p style="font-size: 80%; ">';
						print 'Your uploaded media, including media you\'ve shared with others.<br>';
						print 'Your public and private notes.<br>';
						print 'All references to your account in our database.';
						print '</p>';
						print '<strong>Delete your account</strong>';
						print '<form method="post" action="php\delete_account.php">';
						print '<p style="font-size: 80%; ">';
						print 'Password: <input type="password" name="password"/>';
						print '</p>';
						print '<input type="submit" value="Delete my account" name="submit"/>';
						print '</form>';
					}
					else{
						exit();
					}
				}
			}
			else{
				print "You're not logged in.";
				header("refresh:1;url=/");
				exit();
			}
		?>
		
		<footer>
			<hr>
			Aleatoribus is an <a href="https://github.com/Aleatoribus">open source</a> project licensed under version 2.0 of the Apache Licence.
		</footer>
	</div>

	</body>
</html>
