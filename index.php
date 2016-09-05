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
			$db_location = "";
			$db_user = "";
			$db_passwd = '';
			$db_name = "";
			$table = "";
			
			$db = mysqli_connect($db_location, $db_user, $db_passwd, $db_name) or die(mysqli_error());
			
			$ip = $_SERVER['REMOTE_ADDR'];
			
			$log = "insert into $table values(null, '$ip')";
			
			mysqli_query($db, $log) or die(mysqli_error());

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
				print 'Title: <input type="text" name="upload_name"/>';
				print "\n				";
				print '<p style="font-size: 85%;">';
				print "\n					";
				print '<input type="checkbox" name="encryption" value="encrypt" onclick="displayOptions(this)"> Use encryption';
				print "\n				";
				print '</p>';
				print "\n				";
				print '<div id="encryptionOptions" style="display:none;font-size: 85%;">';
				print "\n					";
				print '<p>';
				print "\n						";
				print 'Key: <input type="password" name="key"/>';
				print "\n						";
				print 'Cipher: ';
				print "\n						";
				print '<select name="cipher">';
				print "\n							";
				print '<option value="aes-256-cbc">AES-256-CBC</option>';
				print "\n							";
				print '<option value="aes-128-cbc">AES-128-CBC</option>';
				print "\n							";
				print '<option value="bf-cbc">BF-CBC</option>';
				print "\n						";
				print '</select>';
				print "\n					";
				print '</p>';
				print "\n					";
				print '<p>';
				print "\n						";
				print '<input type="checkbox" name="decryption" value="disallow"> Disallow server-side decryption';
				print "\n					";
				print '</p>';
				print "\n				";
				print '</div>';
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
				print '<a href="/uploads.php">My uploads</a>. Max filesize: 300MB';
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
					print '<p style="color:red; font-size:85%;">';
					$login = $_GET['login'];
					if($login == 1){
						print "Invalid credentials."; 
					}
					else if($login == 0){
						print "Username and/or password cannot be blank."; 
					}
					print '</p>';
					print "\n		";
					print "\n		";
				}
				else if(isset($_GET['register'])){
					print '<p style="color:green; font-size:85%;">';
					$register = $_GET['register'];
					if($register == 1){
						print "You've created an account! You may now log in."; 
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
					print '<source src="media/joplin0' . rand(1, 2) . '.mp3" type="audio/mpeg">' . "\n";
				?>
				Your browser does not support HTML5 content.
			</audio>
		</p>

		<footer>
			<hr>
			Aleator Stream is an <a href="https://github.com/Aleatoribus">open source</a> project licensed under version 2.0 of the Apache Licence.
		</footer>

		</div>

		<?php
			if(isset($_SESSION['username'])){
				print '<script src="js/displayOptions.js"></script>' . "\n";
			}
		?>

	</body>
</html>
