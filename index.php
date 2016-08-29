<?php
	$title = 'Aleator Stream';
	include("inc/header.inc");
?>
		
		<div align="center">
		
		<h2>
			<?php 
				print "Hi ";
				if(isset($_SESSION['username'])){
					echo ucfirst($_SESSION['username']);
				}
				else{
					echo $_SERVER['REMOTE_ADDR'];
				}
				print "! Aleator Stream is in alpha development.";
				print "\n";
			?>
		</h2>

		<hr>
		
		<?php
			/* Log IP addresses of visitors */
			$db_location = "127.0.0.1:3306";
			$db_user = "root";
			$db_passwd = 'Rmit1234';
			$db_name = "aleatoribus";
			$table = "visitors";
			
			$db = mysqli_connect($db_location, $db_user, $db_passwd, $db_name) or die(mysqli_error());
			
			$ip = $_SERVER['REMOTE_ADDR'];
		
			$insert = "insert into $table values(null, '$ip')";
			
			mysqli_query($db, $insert) or die(mysqli_error());

			/* Print content to page depending on session status. */
			if(isset($_SESSION['username'])){
				print '<p>';
				print "\n			";
				print '<strong>Upload:</strong>';
				print "\n		";
				print '</p>';
				print "\n		";
				print "\n		";
				print '<p>';
				print "\n			";
				print '<form action="php/process_upload.php" method="post" enctype="multipart/form-data">';
				print "\n				";
				print 'Title: <input type="text" name="upload_name" /><br><br>';
				print "\n				";
 				print '<input type="file" name="uploadedFile">';
 				print "\n				";
 				print '<input type="submit" value="Upload">';
 				print "\n			";
				print '</form>';
				print "\n		";
				print '</p>';
				print "\n		";
				print "\n		";
				print '<p id="upload-data">';
				print "\n			";
				print '<a href="/files.php">My uploads</a>. Max filesize: 300MB';
				print "\n		";
				print '</p>';
				print "\n		";
			}
			else{
				print '<p>';
				print "\n			";
				print '<strong>Login:</strong>';
				print "\n		";
				print '</p>';
				print "\n		";
				print "\n		";
				if(isset($_GET['login'])){
					print '<p style="color:red;">';
					$login = $_GET['login'];
					if($login == 0){
						print "Invalid credentials."; 
					}
					else if($login == 1){
						print "Username and/or password cannot be blank."; 
					}
					print '</p>';
					print "\n		";
					print "\n		";
				}
				print '<p>';
				print "\n			";
				print '<form method="post" action="php\process_login.php">';
				print "\n				";
				print 'Username: <input type="text" name="username" /><br><br>';
				print "\n				";
				print 'Password: <input type="password" name="password" /><br><br>';
				print "\n				";
				print '<input type="submit" value="Login" />';
				print "\n			";
				print '</form>';
				print "\n		";
				print '</p>';
				print "\n		";
			}
		?>

		<hr>

		<p>
			<strong>Here's a video of a bear that you can stream in the meantime.</strong>
		</p>
		
		<p>
			<video width="320" height="240" controls>
				<source src="media/hunt.mp4" type="video/mp4">
				Your browser does not support HTML5 content.
			</video>
		</p>
		
		<p>
			<strong>Plus some Joplin.</strong>
		</p>
		
		<p>
			<audio controls>
				<?php
					$random = rand(0, 1);
					if($random == 1){
						print '<source src="media/joplin01.mp3" type="audio/mpeg">';
						print "\n";
					}
					else{
						print '<source src="media/joplin02.mp3" type="audio/mpeg">';
						print "\n";
					}
				?>
				Your browser does not support HTML5 content.
			</audio>
		</p>

		<footer>
			<hr>
			Aleator Stream is an open source project licensed under version 2.0 of the Apache Licence.
		</footer>

		</div>

	</body>
</html>
