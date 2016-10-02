<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $title; ?></title>
		<meta charset="UTF-8"/>
		<link rel="icon" href="favicon.ico"/>
		<link rel="stylesheet" type="text/css" href="css/style.css"/>
		<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Ubuntu:regular,bold&subset=Latin"/>
		<!-- <link rel="stylesheet" href="simple/css/normalize.css">
  		<link rel="stylesheet" href="simple/css/skeleton.css"> -->
		<script src="https://use.fontawesome.com/646b36f2aa.js"></script>
	</head>
	<body>

		<div id="wrapper" align="center">

			<header>
				<h1 align="center" style="font-size:45px; text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;"><span style="color: red;">A</span><span style="color: white;">l</span><span style="color: blue;">e</span><span style="color: red;">a</span><span style="color: white;">t</span><span style="color: blue;">o</span><span style="color: red;">r</span><span style="color: white;"> S</span><span style="color: blue;">t</span><span style="color: red;">r</span><span style="color: white;">e</span><span style="color: blue;">a</span><span style="color: red;">m</span></h1>
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
