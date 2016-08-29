<?php
	//$title = '';
	include("inc/header.inc");
?>

	<div align="center">
		
		<?php
			if(isset($_SESSION['username'])){
				$db_location = "";
				$db_user = "";
				$db_passwd = '';
				$db_name = "";
				$table = "uploads";
				$username = $_SESSION['username'];
				
				$db = mysqli_connect($db_location, $db_user, $db_passwd, $db_name) or die(mysqli_error());
				
				$q = "select * from $table where username='$username'";
				$results = mysqli_query($db, $q) or die(mysqli_error());
				
				if(mysqli_num_rows($results) > 0){
					while($row = mysqli_fetch_array($results)){
					print "<p>";
					print $row['id'];
					print ". ";
					print "<a href='https://aleator.stream/uploads/";
					print $row['upload'];
					print "'>";
					print $row['upload_name'];
					print "</a>";
					print "</p>\n		";
					}
				}
				else{
					print "<p>You've uploaded nothing!</p>";
					print "<p>Thanks for saving us the space.</p>";
				}
			}
			else{
				print "<p>You're not logged in!</p>";
			}
			/*mysqli_query($db, $insert) or die(mysqli_error());*/
		?>
		
		<footer>
			<!-- <hr> -->
			<!-- Aleatoribus is an open source project licensed under version 2.0 of the Apache Licence. -->
		</footer>
	</div>

	</body>
</html>
