<?php
	$title = 'Uploads | Aleator Stream';
	include("inc/header.inc");
?>
			
			<?php
				if(isset($_SESSION['username'])){
					$db_location = "";
					$db_user = "";
					$db_passwd = "";
					$db_name = "";
					$username = $_SESSION['username'];
					$usrHash = md5(strtolower($username));
					$table = "uploads_" . $usrHash;
					
					$db = mysqli_connect($db_location, $db_user, $db_passwd, $db_name) or die(mysqli_error());
					
					$q = "select * from $table";
					$results = mysqli_query($db, $q) or die(mysqli_error());
					
					if(mysqli_num_rows($results) > 0){
						while($row = mysqli_fetch_array($results)){
						print "<p>";
						print $row['id'];
						print ". " . "<strong>" . $row['upload_name'] . "</strong>";
						if($row['encrypted'] == 1){
							print " | " . "<i class='fa fa-lock' aria-hidden='true'></i>";
							if($row['allow_server_decryption'] == 1){
								print " | Decrypt";
							}
						}
						else{
							print " | " . "<a href='https://aleator.stream/uploads/" . $usrHash ."/" . $row['upload_file'] . "'>" . "View online" . "</a>";
						}
						print " | <a href='https://aleator.stream/uploads/" . $usrHash ."/" . $row['upload_file'] . "' download>" . "<i class='fa fa-cloud-download' aria-hidden='true'></i></a>";
						print "</p>\n		";
						}
					}
					else{
						print "<p>You've uploaded nothing!</p>";
						print "<p>Thanks for saving us the space.</p>";
					}
				}
				else{
					print "<p><strong>You're not logged in!</strong></p>";
					print '<p style="font-size: 90%;">You should log in. Or maybe just keep refreshing the page.</p>';
					print "<p>";
					print '<img src="media/gogh0' . rand(1, 3) . '.jpg" alt="Van Gogh" style="width:50%;height:50%;">' . "\n";
					print "</p>";
				}
			?>

			<footer>
				<hr>
				Aleatoribus is an open source project licensed under version 2.0 of the Apache Licence.
			</footer>

	</body>
</html>
