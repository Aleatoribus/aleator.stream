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
				print "! Aleator Stream is in alpha development." . "\n";
			?>
		</h2>

		<p style="font-size: 80%;">New! - <a href="http://c6ogbcnl32dr6mwb.onion">c6ogbcnl32dr6mwb.onion</a> on TOR.</p>

		<hr>
		
		<?php
			if(isset($_SESSION['username'])){
				print '<p>' . "\n			";
				print '<strong>Upload a file</strong>' . "\n		";
				print '</p>' . "\n		\n		";
				print '<p>' . "\n			";
				print '<form action="php/process_upload.php" method="post" enctype="multipart/form-data">' . "\n				";
				print 'Title: <input type="text" name="upload_name"/>' . "\n				";
				print '<p style="font-size: 85%;">' . "\n					";
				print '<input type="checkbox" name="encryption" value="encrypt" onclick="displayUploadOptions(this)"> Use encryption' . "\n				";
				print '</p>' . "\n				";
				print '<div id="uploadEncryptionOptions" style="display:none;font-size: 85%;">' . "\n					";
				print '<p>' . "\n						";
				print 'Key: <input type="password" name="key"/>' . "\n						";
				print 'Cipher: ' . "\n						";
				print '<select name="cipher">' . "\n							";
				print '<option value="aes-256-cbc">AES-256-CBC</option>' . "\n							";
				print '<option value="aes-192-cbc">AES-192-CBC</option>' . "\n							";
				print '<option value="aes-128-cbc">AES-128-CBC</option>' . "\n							";
				print '<option value="bf-cbc">BF-CBC</option>' . "\n						";
				print '</select>' . "\n					";
				print '</p>' . "\n					";
				print '<p>' . "\n						";
				print '<input type="checkbox" name="decryption" value="disallow"> Disallow server-side decryption' . "\n					";
				print '</p>' . "\n				";
				print '</div>' . "\n				";
 				print '<input type="file" name="uploadedFile">' . "\n				";
 				print '<input type="submit" value="Upload">' . "\n			";
				print '</form>' . "\n		";
				print '</p>' . "\n		\n		";
				print '<p id="upload-data">' . "\n			";
				print '<a href="/uploads.php">My uploads</a>. Max filesize: 300MB' . "\n		";
				print '</p>' . "\n		\n		";
				print '<hr>' . "\n		\n		";
				print '<p>' . "\n			";
				print '<form action="php/process_note.php" method="post" enctype="multipart/form-data">' . "\n				";
				print '<p>' . "\n					";
				print '<strong>Write a note</strong>' . "\n				";
				print '</p>' . "\n				";
				print '<p>' . "\n					";
				print 'Title: <input type="text" name="title"/>' . "\n				";
				print '</p>' . "\n				";
				print '<p>' . "\n					";
				print '<textarea rows="8" cols="75" name="content"></textarea>' . "\n				";
				print '</p>' . "\n				";
				print '<p style="font-size: 85%;">' . "\n				";
				print '<input type="checkbox" name="publicity" value="public"> Publish this note publically.' . "\n					";
				print '</p>' . "\n				";
				print '<p style="font-size: 85%;">' . "\n				";
				print '<input type="checkbox" name="encryption" value="encrypt" onclick="displayNoteOptions(this)"> Use encryption.' . "\n					";
				print '</p>' . "\n				";
				print '<div id="noteEncryptionOptions" style="display:none;font-size: 85%;">' . "\n					";
				print '<p>' . "\n						";
				print 'Key: <input type="password" name="key"/>' . "\n						";
				print 'Cipher: ' . "\n						";
				print '<select name="cipher">' . "\n							";
				print '<option value="aes-256-cbc">AES-256-CBC</option>' . "\n							";
				print '<option value="aes-192-cbc">AES-192-CBC</option>' . "\n							";
				print '<option value="aes-128-cbc">AES-128-CBC</option>' . "\n							";
				print '<option value="bf-cbc">BF-CBC</option>' . "\n						";
				print '</select>' . "\n					";
				print '</p>' . "\n					";
				print '</div>' . "\n				";
 				print '<input type="submit" value="Publish">' . "\n			";
				print '</form>' . "\n		";
				print '</p>' . "\n		";
			}
			else{
				print '<p>' . "\n			";
				print '<strong>Login:</strong>' . "\n		";
				print '</p>' . "\n		\n		";
				if(isset($_GET['login'])){
					print '<p style="color:red; font-size:85%;">';
					$login = $_GET['login'];
					if($login == 1){
						print "Invalid credentials."; 
					}
					else if($login == 0){
						print "Username and/or password cannot be blank."; 
					}
					print '</p>' . "\n		\n		";
				}
				else if(isset($_GET['register'])){
					print '<p style="color:green; font-size:85%;">';
					$register = $_GET['register'];
					if($register == 1){
						print "You've created an account! You may now log in."; 
					}
					else if($register == 2){
						print "Password changed! Please log back in."; 
					}
					print '</p>' . "\n		\n		";
				}
				print '<p>' . "\n			";
				print '<form method="post" action="php/process_login.php">' . "\n				";
				print 'Username: <input type="text" name="username" /><br><br>' . "\n				";
				print 'Password: <input type="password" name="password" /><br><br>' . "\n				";
				print '<input type="submit" value="Login" />' . "\n			";
				print '</form>' . "\n		";
				print '</p>' . "\n		";
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
			<strong>Plus some <em>Scott</em> Joplin.</strong>
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
