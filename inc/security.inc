<?php
	if($_SERVER['HTTP_HOST'] == "www.aleator.stream"){
		header('Location: https://aleator.stream');
		exit();
	}

	header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
	header("Content-Security-Policy: default-src 'self'; font-src 'unsafe-inline' https://fonts.googleapis.com https://fonts.gstatic.com https://use.fontawesome.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://use.fontawesome.com; script-src 'self' 'unsafe-inline' https://use.fontawesome.com; child-src 'self' https://www.youtube-nocookie.com");
	header("X-Content-Security-Policy: default-src 'self'; font-src 'unsafe-inline' https://fonts.googleapis.com https://fonts.gstatic.com https://use.fontawesome.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://use.fontawesome.com; script-src 'self' 'unsafe-inline' https://use.fontawesome.com; child-src 'self' https://www.youtube-nocookie.com");
	header('X-Frame-Options: SAMEORIGIN');
	header('X-XSS-Protection: 1; mode=block');
	header('X-Content-Type-Options: nosniff');
	ini_set('session.cookie_httponly', 1);
	ini_set('session.cookie_secure', 1);
?>
