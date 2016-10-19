<?php
	$title = 'Uploads | Aleator Stream';
	include("inc/header.inc");

	if(isset($_SESSION['username'])){
		$db_source = "";
		$db_user = "";
		$db_passwd = "";
		$db_use = "";

		$db = new mysqli($db_source, $db_user, $db_passwd, $db_use);

		$username = $_SESSION['username'];
		$usrHash = md5(strtolower($username));
		$table = "uploads_" . $usrHash;
		
		$get_note = $db->prepare("SELECT * FROM $table");
		$get_note->execute();
		$result = $get_note->get_result();

		if($result->num_rows > 0){
			$i = 1;
			while($row = $result->fetch_array()){
				print "<p>";
				print $i;
				print ". " . "<strong>" . $row['upload_name'] . "</strong>";
				if($row['encrypted'] == 1){
					print " | " . "<a href='display.php?dir=" . $usrHash ."&content=" . $row['upload_file'] . "&decrypt" . "' target='_blank'>" . "<i class='fa fa-lock' aria-hidden='true' title='Decrypt and open' onmouseover='unlockVisual(this)' onmouseout='unlockVisualReset(this)'></i>" . "</a>";
				}
				else{
					print " | " . "<a href='display.php?dir=" . $usrHash ."&content=" . $row['upload_file'] . "' target='_blank'>" . '<i class="fa fa-folder" aria-hidden="true" title="Open" onmouseover="openVisual(this)" onmouseout="openVisualReset(this)""></i>' . "</a>";
				}
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
