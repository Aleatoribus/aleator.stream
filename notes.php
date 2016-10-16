<?php
	$title = 'Notes | Aleator Stream';
	include("inc/header.inc");
?>
			
			<?php
				$db_source = "";
				$db_user = "";
				$db_passwd = '';
				$db_use = "";

				$db = new mysqli($db_source, $db_user, $db_passwd, $db_use);

				if(isset($_GET['public']) && isset($_GET['dir']) && isset($_GET['note'])){
					$dir = $_GET['dir'];
					$note = $_GET['note'];
					$isPublic = $_GET['public'];
					$noteDir = "/var/www/aleator.stream/html/notes/" . $dir . "/" . $note;

					/* Check if $dir is a valid MD5. */
					if(preg_match('/^[a-f0-9]{32}$/', $dir)){
						if($isPublic == 1){
							$table = "notes";
						}
						else{
							$table = "notes_" . $dir;
						}
					}
					else{
						print '<p>';
						print '<strong>Error</strong>';
						print '</p>';
						print '<p>';
						print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
						print '</p>';
						print '<p style="font-size: 90%; color: red">';
						print "Invalid directory format.";
						print '</p>';
						include("inc/footer.inc");
						exit();
					}

					$get_note = $db->prepare("SELECT * FROM $table WHERE note_dir = ?");
					$get_note->bind_param("s", $note);
					$get_note->execute();
					$result = $get_note->get_result();

					if($result->num_rows == 1){
						$row = $result->fetch_array();
						print '<p>' . "\n				";
						print '<strong>' . "\n					";
						if($row['encrypted'] == 1){
							$isEncrypted = true;
							print "<i class='fa fa-lock' aria-hidden='true'></i> ";
						}
						print $row['title'] . "\n				";
						print '</strong>' . "\n			";
						print '</p>' . "\n			";
					}
					else{
						print '<p>';
						print '<strong>Error</strong>';
						print '</p>';
						print '<p>';
						print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
						print '</p>';
						print '<p style="font-size: 90%; color: red">';
						print "Note not found in database.";
						print '</p>';
						include("inc/footer.inc");
						exit();
					}

					print '<code>' . "\n				";

					if(file_exists("$noteDir")){
						$file = fopen("$noteDir","r");

						while(!feof($file)){
							echo fgets($file) . "\n				" . "<br>";
						}
						print "\n			";
						fclose($file);
					}
					else{
						print '<p>';
						print '<strong>Error</strong>';
						print '</p>';
						print '<p>';
						print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
						print '</p>';
						print '<p style="font-size: 90%; color: red">';
						print "Note not found on server.";
						print '</p>';
						include("inc/footer.inc");
						exit();
					}

					print '</code>' . "\n			\n			";

					print '<p>' . "\n				";
					print "<a href='notes/" . md5(strtolower($row['uploader'])) . "/" . $row['note_dir'] . "'>" . "Raw format" . "</a>" . " | " . "\n				";
					print "<a href='notes/" . md5(strtolower($row['uploader'])) . "/" . $row['note_dir'] . "' download>" . "Download" . "</a>";
					
					if(isset($_SESSION['username'])){
						$username = $_SESSION['username'];
						$usrHash = md5(strtolower($username));

						if($dir == $usrHash){
							if(isset($_GET['delete'])){
								if(isset($_POST['password'])){
									if($_POST['password'] != null){
										$password = $_POST['password'];

										$verification = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
										$verification->bind_param("s", $username);
										$verification->execute();
										$result = $verification->get_result();

										if($result->num_rows == 1){
											$row = $result->fetch_array();
											$hashed_password = $row['password'];

											if(password_verify($password, $hashed_password)){
												if(file_exists("$noteDir")){
													//delete note
													$escapedNoteDir = escapeshellcmd($noteDir);
													shell_exec("rm -f $escapedNoteDir");

													//remove from table
													$delete_note = $db->prepare("DELETE FROM $table WHERE note_dir = ?");
													$delete_note->bind_param("s", $note);
													$delete_note->execute();

													header("Location:/notes.php");
													exit();
												}
												else{
													print '<p>';
													print '<strong>Error</strong>';
													print '</p>';
													print '<p>';
													print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
													print '</p>';
													print '<p style="font-size: 90%; color: red">';
													print "Note not found on server.";
													print '</p>';
													include("inc/footer.inc");
													exit();
												}
											}
											else{
												print '<p>';
												print '<strong>Error</strong>';
												print '</p>';
												print '<p>';
												print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
												print '</p>';
												print '<p style="font-size: 90%; color: red">';
												print "Invalid user password.";
												print '</p>';
												include("inc/footer.inc");
												exit();
											}
										}
										else{
											print '<p>';
											print '<strong>Error</strong>';
											print '</p>';
											print '<p>';
											print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
											print '</p>';
											print '<p style="font-size: 90%; color: red">';
											print "Note not found in database.";
											print '</p>';
											include("inc/footer.inc");
											exit();
										}
									}
									print '<p>';
									print '<strong>Error</strong>';
									print '</p>';
									print '<p>';
									print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
									print '</p>';
									print '<p style="font-size: 90%; color: red">';
									print "User password cannot be null.";
									print '</p>';
									include("inc/footer.inc");
									exit();
								}
								else{
									print '<p>';
									print '<strong>Error</strong>';
									print '</p>';
									print '<p>';
									print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
									print '</p>';
									print '<p style="font-size: 90%; color: red">';
									print "User password missing from this deletion request.";
									print '</p>';
									include("inc/footer.inc");
									exit();
								}
							}
							print " | " . "\n				" . '<a href="javascript:displayNoteDelete()" style="color: red;">Delete</a>' . "\n				\n				";
							print '<div id="noteDeletion" style="display:none; font-size: 85%;">' . "\n					";
							print '<p>' . "\n						";
							print '<strong>Note deletion</strong>' . "\n					";
							print '</p>' . "\n					";
							print '<p style="font-size: 65%;">For security, your user password is required to delete content.</p>' . "\n						";
							print "<form action='notes.php?public=$isPublic&dir=$dir&note=$note&delete' method='post' enctype='multipart/form-data'>" . "\n							";
							print 'Password: <input type="password" name="password"/>' . "\n							";
							print '<input type="submit" value="Delete">' . "\n						";
							print '</form>' . "\n					";
							print '</p>' . "\n				";
							print '</div>' . "\n			";
						}
					}
					print '</p>' . "\n			\n			";

					if($isEncrypted){
						$cipher = $row['cipher'];

						print '<div id="noteDecryption" style="font-size: 85%;">' . "\n				";
						print '<p>' . "\n					";
						print '<strong>Note decryption</strong>' . "\n					";
						print "<form action='notes.php?public=$isPublic&dir=$dir&note=$note&decrypt' method='post' enctype='multipart/form-data'>" . "\n						";
						print 'Key: <input type="password" name="key"/>' . "\n						";
						print '<input type="submit" value="Decrypt">' . '<span style="font-size: 70%;"> (' . strtoupper($cipher) . ')</span>' . "\n					";
						print '</form>' . "\n				";
						print '</p>' . "\n			";
						print '</div>' . "\n			\n			";

						if(isset($_GET['decrypt'])){
							if($_POST['key'] != null){
								$key = $_POST['key'];

								print '<strong>Decrypted note</strong>' . "\n			\n			";
								print '<p style="font-size: 70%;">Only see Jibberish? You probably entered an invalid key!</p>' . "\n			\n			";

								$tmpDir = "/var/www/aleator.stream/tmp/" . $note;

								$escapedCipher = escapeshellcmd($cipher);
								$escapedNoteDir = escapeshellcmd($noteDir);
								$escapedTmpDir = escapeshellcmd($tmpDir);
								$escapedKey = escapeshellcmd($key);
								shell_exec("openssl $escapedCipher -d -a -in $escapedNoteDir -out $escapedTmpDir -pass pass:$escapedKey");

								$output = shell_exec("cat $escapedTmpDir && rm -f $escapedTmpDir");

								print "<p>" . "\n				";
								print "<pre>$output</pre>" . "\n			";
								print "</p>" . "\n			\n			";
							}
						}
					}
				}
				else{
					if(isset($_SESSION['username'])){
						$username = $_SESSION['username'];
						$usrHash = md5(strtolower($username));

						print '<h2>Your notes</h2>' . "\n			\n			";
						print '<strong>Public</strong>' . "\n			\n			";

						$get_personal_public_notes = $db->prepare("SELECT * FROM notes WHERE uploader = ?");
						$get_personal_public_notes->bind_param("s", $username);
						$get_personal_public_notes->execute();
						$result_personal_public = $get_personal_public_notes->get_result();

						if($result_personal_public->num_rows > 0){
							while($row = $result_personal_public->fetch_array()){
								print "<p>" . "\n				";
								print "<a href='notes.php?public=1&dir=" . md5(strtolower($row['uploader'])) . "&note=" . $row['note_dir'] . "'>" . $row['title'] . "</a>" . "\n			";
								print "</p>" . "\n			\n			";
							}
						}
						else{
							print '<p style="font-size: 85%;">You\'ve published no public notes.</p>' . "\n				";
						}

						print '<strong>Private</strong>' . "\n			\n			";

						$personal_private_table = "notes_" . $usrHash;
						$get_personal_private_notes = $db->prepare("SELECT * FROM $personal_private_table");
						$get_personal_private_notes->execute();
						$result_personal_private = $get_personal_private_notes->get_result();

						if($result_personal_private->num_rows > 0){
							while($row = $result_personal_private->fetch_array()){
								print "<p>" . "\n				";
								print "<a href='notes.php?public=0&dir=" . md5(strtolower($row['uploader'])) . "&note=" . $row['note_dir'] . "'>" . $row['title'] . "</a>" . "\n			";
								print "</p>" . "\n			\n			";
							}
						}
						else{
							print '<p style="font-size: 85%;">You\'ve published no private notes.</p>' . "\n			\n			";
						}
						print '<hr>' . "\n			\n			";
					}

					print '<h2>Public notes</h2>' . "\n			\n			";

					$get_public_notes = $db->prepare("SELECT * FROM notes");
					$get_public_notes->execute();
					$result_public = $get_public_notes->get_result();

					
					if($result_public->num_rows > 0){
						while($row = $result_public->fetch_array()){
							print "<p>" . "\n				";
							print "<a href='notes.php?public=1&dir=" . md5(strtolower($row['uploader'])) . "&note=" . $row['note_dir'] . "'>" . $row['title'] . "</a>" . "\n			";
							print "</p>" . "\n			";
						}
					}
					else{
						print '<p style="font-size: 85%;">There are no public notes on the server.</p>';
					}
				}
				include("inc/footer.inc");
			?>

			</div>

	</body>
</html>
