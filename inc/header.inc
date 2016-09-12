<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $title; ?></title>
		<meta charset="UTF-8"/>
		<link rel="icon" href="favicon.ico"/>
		<link rel="stylesheet" type="text/css" href="css/style.css"/>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Ubuntu:regular,bold&subset=Latin"/>
		<script src="https://use.fontawesome.com/646b36f2aa.js"></script>
	</head>
	<body>

		<div id="wrapper" align="center">

			<header>
				<h1 align="center" style="font-size:45px">Aleator Stream</h1>
				<div class="nav">
					<ul>
						<li class="home" style="font-size: 85%">
							<a href="/">Home</a>
						</li>
						<li class="about" style="font-size: 85%">
							<a href="/about.php">About</a>
						</li>
						<li class="uploads" style="font-size: 85%">
							<a href="/uploads.php">Uploads</a>
						</li>
						<li class="notes" style="font-size: 85%">
							<a href="/notes.php">Notes</a>
						</li>
						<?php
							include("inc/security.inc");
							@session_start();
							if(isset($_SESSION['username'])){
								print '<li class="profile" style="font-size: 85%">';
								print "\n						";
								print '<a href="/profile.php">Profile</a>';
								print "\n					";
								print '</li>';
								print "\n					";
								print '<li class="logout" style="font-size: 85%">';
								print "\n						";
								print '<a href="php/logout.php">Logout</a>';
								print "\n					";
								print '</li>';
								print "\n";		
							}
							else{
								print '<li class="register" style="font-size: 85%">';
								print "\n						";
								print '<a href="/register.php">Register</a>';
								print "\n					";
								print '</li>';
								print "\n";
							}
						?>
					</ul>
				</div>
			</header>
