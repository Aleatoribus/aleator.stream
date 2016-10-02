<?php
	$title = 'Notes | Aleator Stream';
	include("inc/header.inc");
?>
			
			<?php
				$db_location = """";
				$db_user = """";
				$db_passwd = '""';
				$db_name = """";

				if(isset($_GET['public']) && isset($_GET['dir']) && isset($_GET['note'])){
					$dir = $_GET['dir'];
					$note = $_GET['note'];
					$isPublic = $_GET['public'];
					$noteDir = "/var/www/aleator.stream/html/notes/" . $dir . "/" . $note;

					if($isPublic == 1){
						$table = "notes";
					}
					else{
						$table = "notes_" . $dir;
					}
					
					$db = mysqli_connect($db_location, $db_user, $db_passwd, $db_name) or die(mysqli_error());

					$q = "select * from $table where note_dir='$note'";
					$results = mysqli_query($db, $q) or die(mysqli_error($db));
					
					if(mysqli_num_rows($results) == 1){
						$row = mysqli_fetch_array($results);
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
						header("Location:/"); //error
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
						print "404 - Requested note not found on server.";
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

										$q = "select * from users where username='$username'";
										$results = mysqli_query($db, $q) or die(mysqli_error($db));

										if(mysqli_num_rows($results) == 1){
											$row = mysqli_fetch_array($results);
											$hashed_password = $row['password'];

											if(password_verify($password, $hashed_password)){
												//delete note
												shell_exec("rm -f $noteDir");

												//remove from table
												$deleteNote = "delete from $table where note_dir='$note'";
												mysqli_query($db, $deleteNote) or die(mysqli_error($db));

												header("Location:/notes.php");
												exit();
											}
											else{
												header("Location:/notes.php?error=delete");
												exit();
											}
										}
										else{
											header("Location:/notes.php?error=delete");
											exit();
										}
									}
									header("Location:/notes.php?error=delete");
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

								shell_exec("openssl $cipher -d -a -in $noteDir -out $tmpDir -pass pass:$key");

								$output = shell_exec("cat $tmpDir && rm -f $tmpDir");

								print "<p>" . "\n				";
								print "<pre>$output</pre>" . "\n			";
								print "</p>" . "\n			\n			";
							}
						}
					}
					print '<script src="js/displayOptions.js"></script>' . "\n			";
				}
				else{
					$db = mysqli_connect($db_location, $db_user, $db_passwd, $db_name) or die(mysqli_error());

					if(isset($_SESSION['username'])){
						$username = $_SESSION['username'];
						$usrHash = md5(strtolower($username));

						print '<h2>Your notes</h2>' . "\n			\n			";

						if(isset($_GET['error'])){
							$error = $_GET['error'];
							if($error == "delete"){
								print '<p style="font-size: 85%; color: red;">Failed to delete note. Incorrect user password.</p>';
							}
						}

						print '<strong>Public</strong>' . "\n			\n			";
						
						$qPersonalPublic = "select * from notes where uploader='$username'";
						$rPersonalPublic = mysqli_query($db, $qPersonalPublic) or die(mysqli_error());
						
						if(mysqli_num_rows($rPersonalPublic) > 0){
							while($row = mysqli_fetch_array($rPersonalPublic)){
								print "<p>" . "\n				";
								print "<a href='notes.php?public=1&dir=" . md5(strtolower($row['uploader'])) . "&note=" . $row['note_dir'] . "'>" . $row['title'] . "</a>" . "\n			";
								print "</p>" . "\n			\n			";
							}
						}
						else{
							print '<p style="font-size: 85%;">You\'ve published no public notes.</p>' . "\n				";
						}

						print '<strong>Private</strong>' . "\n			\n			";

						$tPersonalPrivate = "notes_" . $usrHash;
						$qPersonalPrivate = "select * from $tPersonalPrivate";
						$rPersonalPrivate = mysqli_query($db, $qPersonalPrivate) or die(mysqli_error());

						if(mysqli_num_rows($rPersonalPrivate) > 0){
							while($row = mysqli_fetch_array($rPersonalPrivate)){
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

					$qPublic = "select * from notes";
					$rPublic = mysqli_query($db, $qPublic) or die(mysqli_error());
					
					if(mysqli_num_rows($rPublic) > 0){
						while($row = mysqli_fetch_array($rPublic)){
							print "<p>" . "\n				";
							print "<a href='notes.php?public=1&dir=" . md5(strtolower($row['uploader'])) . "&note=" . $row['note_dir'] . "'>" . $row['title'] . "</a>" . "\n			";
							print "</p>" . "\n			";
						}
					}
					else{
						print '<p style="font-size: 85%;">There are no public notes on the server.</p>';
					}
				}
			?>

			<footer>
				<hr>
				Aleatoribus is an <a href="https://github.com/Aleatoribus">open source</a> project licensed under version 2.0 of the Apache Licence.
			</footer>

			</div>

	</body>
</html>
