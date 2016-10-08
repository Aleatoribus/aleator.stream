<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $title; ?></title>
		<meta charset="UTF-8"/>
		<link rel="icon" href="favicon.ico"/>
		<link rel="stylesheet" type="text/css" href="/css/style.css"/>
		<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Ubuntu:regular,bold&subset=Latin"/>
		<script src="js/displayOptions.js"></script>
		<!-- <link rel="stylesheet" href="simple/css/normalize.css">
  		<link rel="stylesheet" href="simple/css/skeleton.css"> -->
		<script src="https://use.fontawesome.com/646b36f2aa.js"></script>
	</head>
	<body>

		<div id="wrapper" align="center">

			<header>
				<h1 align="center" style="font-size:45px; text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000; color: #2c3e50;">
					<span id="1" onmouseover="changeColor(this)" onmouseout="resetColor(this)">A</span><span id="2" onmouseover="changeColor(this)" onmouseout="resetColor(this)">l</span><span id="3" onmouseover="changeColor(this)" onmouseout="resetColor(this)">e</span><span id="4" onmouseover="changeColor(this)" onmouseout="resetColor(this)">a</span><span id="5" onmouseover="changeColor(this)" onmouseout="resetColor(this)">t</span><span id="6" onmouseover="changeColor(this)" onmouseout="resetColor(this)">o</span><span id="7" onmouseover="changeColor(this)" onmouseout="resetColor(this)">r</span><span id="8" onmouseover="changeColor(this)" onmouseout="resetColor(this)"> S</span><span id="9" onmouseover="changeColor(this)" onmouseout="resetColor(this)">t</span><span id="10" onmouseover="changeColor(this)" onmouseout="resetColor(this)">r</span><span id="11" onmouseover="changeColor(this)" onmouseout="resetColor(this)">e</span><span id="12" onmouseover="changeColor(this)" onmouseout="resetColor(this)">a</span><span id="13" onmouseover="changeColor(this)" onmouseout="resetColor(this)">m</span></h1>
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
								print '<a href="php/logout.php" onmouseover="logoutHover()">Logout</a>';
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
