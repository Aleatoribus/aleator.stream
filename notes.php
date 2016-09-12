<?php
	$title = 'Notes | Aleator Stream';
	include("inc/header.inc");
?>
			
			<?php
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

					$db_location = "";
					$db_user = "";
					$db_passwd = "";
					$db_name = "";
					
					$db = mysqli_connect($db_location, $db_user, $db_passwd, $db_name) or die(mysqli_error());

					$q = "select * from $table where note_dir='$note'";
					$results = mysqli_query($db, $q) or die(mysqli_error($db));
					
					if(mysqli_num_rows($results) == 1){
						$row = mysqli_fetch_array($results);
						print '<p>';
						print '<strong>';
						print $row['title'];
						print '</strong>';
						print '</p>';
					}
					else{
						header("Location:/"); //error
						exit();
					}

					print '<code>';

					if(file_exists("$noteDir")){
						$file = fopen("$noteDir","r");

						while(!feof($file)){
							echo fgets($file). "<br />";
						}

						fclose($file);
					}
					else{
						print "404";
					}

					print '</code>';

					print '<p>';
					print "<a href='https://aleator.stream/notes/" . md5(strtolower($row['uploader'])) . "/" . $row['note_dir'] . "'>" . "Raw format" . "</a>";
					print " | " . "<a href='https://aleator.stream/notes/" . md5(strtolower($row['uploader'])) . "/" . $row['note_dir'] . "' download>" . "Download" . "</a>";
					
					if(isset($_SESSION['username'])){
						$username = $_SESSION['username'];
						$usrHash = md5(strtolower($username));

						if($dir == $usrHash){
							print " | ";
							print '<span style="color: red;">';
							print "Delete";
							print '</span>';
						}
					}
					print '</p>';
				}
				else{
					$db_location = "";
					$db_user = "";
					$db_passwd = "";
					$db_name = "";
					
					$db = mysqli_connect($db_location, $db_user, $db_passwd, $db_name) or die(mysqli_error());

					if(isset($_SESSION['username'])){
						$username = $_SESSION['username'];
						$usrHash = md5(strtolower($username));

						print '<h2>Your notes</h2>';
						print '<strong>Public</strong>';
						
						$qPersonalPublic = "select * from notes where uploader='$username'";
						$rPersonalPublic = mysqli_query($db, $qPersonalPublic) or die(mysqli_error());
						
						if(mysqli_num_rows($rPersonalPublic) > 0){
							while($row = mysqli_fetch_array($rPersonalPublic)){
								print "<p>";
								print "<a href='https://aleator.stream/notes.php?public=1&dir=" . md5(strtolower($row['uploader'])) . "&note=" . $row['note_dir'] . "'>" . $row['title'] . "</a>";
								print "</p>";
							}
						}
						else{
							print '<p style="font-size: 85%;">You\'ve published no public notes.</p>';
						}

						//break

						print '<strong>Private</strong>';

						$tPersonalPrivate = "notes_" . $usrHash;
						$qPersonalPrivate = "select * from $tPersonalPrivate";
						$rPersonalPrivate = mysqli_query($db, $qPersonalPrivate) or die(mysqli_error());

						if(mysqli_num_rows($rPersonalPrivate) > 0){
							while($row = mysqli_fetch_array($rPersonalPrivate)){
								print "<p>";
								print "<a href='https://aleator.stream/notes.php?public=0&dir=" . md5(strtolower($row['uploader'])) . "&note=" . $row['note_dir'] . "'>" . $row['title'] . "</a>";
								print "</p>";
							}
						}
						else{
							print '<p style="font-size: 85%;">You\'ve published no private notes.</p>';
						}
						print '<hr>';
					}

					print '<h2>Public notes</h2>';

					$qPublic = "select * from notes";
					$rPublic = mysqli_query($db, $qPublic) or die(mysqli_error());
					
					if(mysqli_num_rows($rPublic) > 0){
						while($row = mysqli_fetch_array($rPublic)){
							print "<p>";
							print "<a href='https://aleator.stream/notes.php?public=1&dir=" . md5(strtolower($row['uploader'])) . "&note=" . $row['note_dir'] . "'>" . $row['title'] . "</a>";
							print "</p>";
						}
					}
					else{
						print '<p style="font-size: 85%;">There are no public notes on the server.</p>';
					}
				}
			?>

			<footer>
				<hr>
				Aleatoribus is an open source project licensed under version 2.0 of the Apache Licence.
			</footer>

	</body>
</html>
