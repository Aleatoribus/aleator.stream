<?php
	$title = 'Aleator Stream';
	include("inc/header.inc");
?>
		
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

		<p style="font-size: 80%;">New! - <a href="http://z54pzh3e2qg4phj5.onion">z54pzh3e2qg4phj5.onion</a> on TOR.</p>

		<hr>
		
		<?php
			if(isset($_SESSION['username'])){
				$username = $_SESSION['username'];
				$usrHash = md5(strtolower($username));
				$uploadDir = "/var/www/aleator.stream/uploads/" . $usrHash . "/";
				print '<p>' . "\n			";
				print '<strong>Upload a file</strong>' . "\n		";
				print '</p>' . "\n		\n		";

				//Check if usage exceeds quota

				$db_location = "127.0.0.1:3306";
				$db_user = "root";
				$db_passwd = 'Rmit1234';
				$db_name = "aleatoribus";
				$table = "uploads_" . $usrHash;

				$db = mysqli_connect($db_location, $db_user, $db_passwd, $db_name) or die(mysqli_error());

				$qUploads = "select * from $table";
				$rUploads = mysqli_query($db, $qUploads) or die(mysqli_error());

				while($row = mysqli_fetch_array($rUploads)){
					$bytes =  $bytes + filesize($uploadDir . $row['upload_file']);
				}

				$usage = number_format($bytes / 1048576, 2);

				if(($usage/5000)*100 > 100){
					print '<p>';
					print '<i class="fa fa-frown-o" aria-hidden="true" style="font-size: 500%;"></i>';
					print '</p>';
					print '<p style="font-size: 85%; color: red;">You\'ve exceeded your storage quota!</p>';
				}
				else{
					print '<p>' . "\n			";
					print '<form action="php/process_upload.php" method="post" enctype="multipart/form-data">' . "\n				";
					print 'Title: <input type="text" name="upload_name"/>' . "\n				";
					print '<p style="font-size: 85%;">' . "\n					";
					print '<input type="checkbox" name="share" value="shared"> Make shareable.' . "\n				";
					print '</p>' . "\n				";
					print '<p style="font-size: 85%;">' . "\n					";
					print '<input type="checkbox" name="encryption" value="encrypt" onclick="displayUploadOptions(this)"> Use encryption.' . "\n				";
					print '</p>' . "\n				";
					print '<div id="uploadEncryptionOptions" style="display:none;font-size: 85%;">' . "\n					";
					print '<p>' . "\n						";
					print '<i class="fa fa-key" aria-hidden="true"></i> Key: <input type="password" name="key"/>' . "\n						";
					print 'Cipher: ' . "\n						";
					print '<select name="cipher">' . "\n							";
					print '<option value="aes-256-cbc">AES-256-CBC</option>' . "\n							";
					print '<option value="aes-192-cbc">AES-192-CBC</option>' . "\n							";
					print '<option value="aes-128-cbc">AES-128-CBC</option>' . "\n							";
					print '<option value="camellia-256-cbc">Camellia-256-CBC</option>' . "\n							";
					print '<option value="camellia-192-cbc">Camellia-192-CBC</option>' . "\n							";
					print '<option value="camellia-128-cbc">Camellia-128-CBC</option>' . "\n							";
					print '<option value="bf-cbc">BF-CBC</option>' . "\n						";
					print '</select>' . "\n					";
					print '</p>' . "\n					";
					print '<p>' . "\n						";
					print '<input type="checkbox" name="decryption" value="disallow" disabled> Disallow server-side decryption' . "\n					";
					print '</p>' . "\n				";
					print '</div>' . "\n				";
	 				print '<input type="file" name="uploadedFile" onchange="getFileInfo(this)">' . "\n				";
	 				print '<input type="submit" id="upload" value="Upload">' . "\n			";
					print '</form>' . "\n		";
					print '</p>' . "\n		\n		";
					print '<p id="uploadError" style="font-size: 80%; color: red;"></p>' . "\n		\n		";
					print '<p id="upload-data" style="font-size: 85%;">' . "\n			";
					print '<a href="/uploads.php">My uploads</a>. Max filesize: ' . ini_get('upload_max_filesize') . "B\n		";
					print '</p>' . "\n		\n		";
				}

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
				print '<p style="font-size: 85%;">' . "\n					";
				print '<input type="checkbox" name="publicity" value="public"> Publish this note publically.' . "\n				";
				print '</p>' . "\n				";
				print '<p style="font-size: 85%;">' . "\n					";
				print '<input type="checkbox" name="encryption" value="encrypt" onclick="displayNoteOptions(this)"> Use encryption.' . "\n				";
				print '</p>' . "\n				";
				print '<div id="noteEncryptionOptions" style="display:none;font-size: 85%;">' . "\n					";
				print '<p>' . "\n						";
				print '<i class="fa fa-key" aria-hidden="true"></i> Key: <input type="password" name="key"/>' . "\n						";
				print 'Cipher: ' . "\n						";
				print '<select name="cipher">' . "\n							";
				print '<option value="aes-256-cbc">AES-256-CBC</option>' . "\n							";
				print '<option value="aes-192-cbc">AES-192-CBC</option>' . "\n							";
				print '<option value="aes-128-cbc">AES-128-CBC</option>' . "\n							";
				print '<option value="camellia-256-cbc">Camellia-256-CBC</option>' . "\n							";
				print '<option value="camellia-192-cbc">Camellia-192-CBC</option>' . "\n							";
				print '<option value="camellia-128-cbc">Camellia-128-CBC</option>' . "\n							";
				print '<option value="bf-cbc">BF-CBC</option>' . "\n						";
				print '</select>' . "\n					";
				print '</p>' . "\n				";
				print '</div>' . "\n				";
 				print '<input type="submit" value="Publish">' . "\n			";
				print '</form>' . "\n		";
				print '</p>' . "\n		";
			}
			else{
				print '<p>' . "\n			";
				print '<strong>Login</strong>' . "\n		";
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

		<?php
			/*if(isset($_SESSION['username'])){
				print '<script src="js/displayOptions.js"></script>' . "\n		\n		";
			}*/
			include("inc/footer.inc");
		?>
