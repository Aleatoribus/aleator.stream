<?php
	$title = 'Uploads | Aleator Stream';
	include("inc/header.inc");

	if(isset($_SESSION['username'])){
		$db_location = "";
		$db_user = "";
		$db_passwd = '';
		$db_name = "";

		$db = mysqli_connect($db_location, $db_user, $db_passwd, $db_name) or die(mysqli_error());

		$username = mysqli_real_escape_string($db, $_SESSION['username']);
		$usrHash = md5(strtolower($username));
		$table = "uploads_" . $usrHash;
		
		$q = "select * from $table";
		$results = mysqli_query($db, $q) or die(mysqli_error());
		$i = 1;
		
		if(mysqli_num_rows($results) > 0){
			while($row = mysqli_fetch_array($results)){
			print "<p>";
			print $i;
			//print $row['id'];
			print ". " . "<strong>" . $row['upload_name'] . "</strong>";
			if($row['encrypted'] == 1){
				if($row['allow_server_decryption'] == 1){
					print " | " . "<a href='display.php?dir=" . $usrHash ."&content=" . $row['upload_file'] . "&decrypt" . "' target='_blank'>" . "<i class='fa fa-lock' aria-hidden='true' title='Decrypt and open' onmouseover='unlockVisual(this)' onmouseout='unlockVisualReset(this)'></i>" . "</a>";
				}
				else{
					//this needs to open to details, the user at least needs to be able to delete the file.
					print " | " . "<a href='display.php?dir=" . $usrHash ."&content=" . $row['upload_file'] . "&download=2>" . "<i class='fa fa-lock' aria-hidden='true' title='This file cannot be decrypted by the server due to user preference'></i>" . "</a>";
				}
			}
			else{
				//add file type check here
				//print " | " . "<a href='https://aleator.stream/uploads/" . $usrHash ."/" . $row['upload_file'] . "'>" . "View online" . "</a>";
				print " | " . "<a href='display.php?dir=" . $usrHash ."&content=" . $row['upload_file'] . "'>" . '<i class="fa fa-external-link" aria-hidden="true" title="Open"></i>' . "</a>";
			}
			//this needs to be changed for new system
			//print " | <a href='https://aleator.stream/uploads/" . $usrHash ."/" . $row['upload_file'] . "' download>" . "<i class='fa fa-cloud-download' aria-hidden='true'></i></a>";
			print "</p>\n		";
			$i++;
			}
		}
		else{
			print "<p><strong>You've uploaded nothing!</strong></p>";
			print "<p style='font-size: 90%;'>Thanks for saving us the space. <i class='fa fa-smile-o' aria-hidden='true'></i></p>";
		}
	}
	else{
		print "<p><strong>You're not logged in!</strong></p>";
		print '<p style="font-size: 90%;">You should log in. Or maybe just keep refreshing the page.</p>';
		print "<p>";
		print '<img src="media/gogh0' . rand(1, 3) . '.jpg" alt="Van Gogh" style="width:50%; height:50%;">' . "\n";
		print "</p>";
	}

	include("inc/footer.inc");
?>
