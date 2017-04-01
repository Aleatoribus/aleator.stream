<?php
	$title = 'Aleator Stream';
	include("inc/header.inc");
?>
		
		<h2>
			<?php 
				print "Hi ";
				if(isset($_SESSION['username'])){
					print ucfirst($_SESSION['username']);
				}
				else if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), "googlebot")){
					print "Googlebot";
				}
				else{
					print $_SERVER['REMOTE_ADDR'];
				}
				print "! Aleator Stream is no longer in development." . "\n";
			?>
		</h2>

		<hr>
		
		<?php
			if(isset($_SESSION['username'])){
				$username = $_SESSION['username'];
				$usrHash = md5(strtolower($username));
				$uploadDir = "/var/www/aleator.stream/uploads/" . $usrHash . "/";
				print '<p>' . "\n			";
				print '<strong>Upload a file</strong>' . "\n		";
				print '</p>' . "\n		\n		";

				/* Check if usage exceeds quota */
				$db_source = "";
				$db_user = "";
				$db_passwd = "";
				$db_use = "";
				$table = "uploads_" . $usrHash;

				$db = new mysqli($db_source, $db_user, $db_passwd, $db_use);

				$get_uploads = $db->prepare("SELECT * FROM $table");
				$get_uploads->execute();
				$result = $get_uploads->get_result();

				while($row = $result->fetch_array()){
					$bytes =  $bytes + filesize($uploadDir . $row['upload_file']);
				}

				$usage = str_replace(",", "", number_format($bytes / 1048576, 2));

				if(($usage/2000)*100 > 100){
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
					print '<input type="checkbox" name="share" value="shared"> Make shareable. <i class="fa fa-info-circle" aria-hidden="true" title="A shareable file can be accessed by others using its link."></i>' . "\n				";
					print '</p>' . "\n				";
					print '<p style="font-size: 85%;">' . "\n					";
					print '<input type="checkbox" name="encryption" value="encrypt" onclick="displayUploadOptions(this)"> Use encryption. <i class="fa fa-info-circle" aria-hidden="true" title="Encryption protects a file from unauthorised viewers."></i>' . "\n				";
					print '</p>' . "\n				";
					print '<div id="uploadEncryptionOptions" style="display:none;font-size: 85%;">' . "\n					";
					print '<p>' . "\n						";
					print '<i class="fa fa-key" aria-hidden="true" title="This is a password you later use to decrypt the file."></i> Key: <input type="password" name="key"/>' . "\n						";
					print 'Cipher: ' . "\n						";
					print '<select name="cipher">' . "\n							";
					print '<option value="aes-256-cbc">AES-256-CBC</option>' . "\n							";
					print '<option value="aes-192-cbc">AES-192-CBC</option>' . "\n							";
					print '<option value="aes-128-cbc">AES-128-CBC</option>' . "\n							";
					print '<option value="camellia-256-cbc">Camellia-256-CBC</option>' . "\n							";
					print '<option value="camellia-192-cbc">Camellia-192-CBC</option>' . "\n							";
					print '<option value="camellia-128-cbc">Camellia-128-CBC</option>' . "\n						";
					print '</select>' . "\n					";
					print '</p>' . "\n					";
					print '</div>' . "\n				";
	 				print '<input type="file" name="uploadedFile" onchange="getFileInfo(this)">' . "\n				";
	 				print '<input type="submit" id="upload" value="Upload">' . "\n			";
					print '</form>' . "\n		";
					print '</p>' . "\n		\n		";
					print '<p id="uploadError" style="font-size: 80%; color: red;"></p>' . "\n		\n		";
					print '<p style="font-size: 60%;">';
					print '<i class="fa fa-gavel" aria-hidden="true"></i> By using Aleator Stream you agree to our <a href="/terms.php">terms</a>.';
					print '</p>';
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
				print '<input type="checkbox" name="publicity" value="public"> Make public. <i class="fa fa-info-circle" aria-hidden="true" title="Public notes are displayed on the Notes page for anyone to see."></i>' . "\n				";
				print '</p>' . "\n				";
				print '<p style="font-size: 85%;">' . "\n					";
				print '<input type="checkbox" name="encryption" value="encrypt" onclick="displayNoteOptions(this)"> Use encryption. <i class="fa fa-info-circle" aria-hidden="true" title="Encryption protects a file from unauthorised viewers."></i>' . "\n				";
				print '</p>' . "\n				";
				print '<div id="noteEncryptionOptions" style="display:none; font-size: 85%;">' . "\n					";
				print '<p>' . "\n						";
				print '<i class="fa fa-key" aria-hidden="true" title="This is a password you later use to decrypt the file."></i> Key: <input type="password" name="key"/>' . "\n						";
				print 'Cipher: ' . "\n						";
				print '<select name="cipher">' . "\n							";
				print '<option value="aes-256-cbc">AES-256-CBC</option>' . "\n							";
				print '<option value="aes-192-cbc">AES-192-CBC</option>' . "\n							";
				print '<option value="aes-128-cbc">AES-128-CBC</option>' . "\n							";
				print '<option value="camellia-256-cbc">Camellia-256-CBC</option>' . "\n							";
				print '<option value="camellia-192-cbc">Camellia-192-CBC</option>' . "\n							";
				print '<option value="camellia-128-cbc">Camellia-128-CBC</option>' . "\n						";
				print '</select>' . "\n					";
				print '</p>' . "\n				";
				print '</div>' . "\n				";
 				print '<input type="submit" value="Publish">' . "\n			";
				print '</form>' . "\n		";
				print '</p>' . "\n		";
				print '<p style="font-size: 60%;">';
				print '<i class="fa fa-gavel" aria-hidden="true"></i> By using Aleator Stream you agree to our <a href="/terms.php">terms</a>.';
				print '</p>';
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
			<strong>Here's a video of a bear that you can stream instead.</strong>
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
	include("inc/footer.inc");
?>
