<!DOCTYPE html>
<html>
	<head>
		<title>Diagnostics</title>
		<meta charset="UTF-8"/>
		<link rel="icon" href="favicon.ico"/>
	</head>
	<body>
		<?php
			include("inc/security.inc");
			function get_browser_name(){
				$user_agent = $_SERVER['HTTP_USER_AGENT'];
				if(strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')){
					return 'Opera';
				} 
				else if(strpos($user_agent, 'Edge')){
					return 'Edge';
				} 
				else if(strpos($user_agent, 'Chrome')){
					return 'Chrome';
				}
				else if(strpos($user_agent, 'Safari')){
					return 'Safari';
				}
				else if(strpos($user_agent, 'Firefox')){
					return 'Firefox';
				}
				else if(strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')){
					return 'Internet Explorer';
				}
				else{
					return 'Unknown';
				}
			}

			print '<h2>Session diagnostics</h2>';
			session_start();
			if(isset($_SESSION['username'])){
				print '<p>You are logged in as: ' . $_SESSION['username'] . '</p>';
			}
			else{
				print '<p>You are not logged in</p>';
			}

			print '<p>We see your IP address as: ' . $_SERVER['REMOTE_ADDR'] . '</p>';

			print '<p>You are accessing our website via: ' . $_SERVER['HTTP_HOST'] . ' on remote port: ' . $_SERVER['SERVER_PORT'] . ' and local port: ' . $_SERVER['REMOTE_PORT'] . '</p>';

			print '<p>This virtual host is: ' . $_SERVER['SERVER_NAME'] . ' on ' . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . '</p>';

			if($_SERVER['HTTPS'] == "on"){
				print '<p>You are using HTTPS connection security. We\'ve enabled this by force.</p>';
			}
			else if($_SERVER['HTTPS'] != "on" && $_SERVER['HTTP_HOST'] == "c6ogbcnl32dr6mwb.onion"){
				print '<p>You are not using HTTPS connection security, but you are connecting via TOR.</p>';
			}
			else{
				print '<p>You are not using HTTPS connection security. This is very bad, please report this!</p>';
			}

			print '<p id="noscript">JavaScript is not enabled, or is being blocked on this page.</p>';
			print '<p id="script"></p>';

			print '<p>Do Not Track header: ' . $_SERVER['HTTP_DNT'];

			print '<h2>MKV playback test</h2>';
			print '<p>You\'re using: ' . get_browser_name() . '</p>';

			if(get_browser_name() == 'Chrome'){
				print '<p>The video below should play!</p>';
			}
			else{
				print '<p>The video below might not play.</p>';
			}

			print '<video width="25%" height="25%" controls><source src="media/jellyfish.mkv" type="video/mp4"></video>';

			print '<h2>OpenSSL string encryption test</h2>';

			$plaintext = shell_exec('echo "The quick brown fox jumps over the lazy dog." >> /var/www/aleator.stream/tmp/diag.txt && cat /var/www/aleator.stream/tmp/diag.txt');

			print '<p><strong>Original string: </strong>' . $plaintext . '</p>';

			$aes128 = shell_exec('openssl aes-128-cbc -a -salt -in /var/www/aleator.stream/tmp/diag.txt  -out /var/www/aleator.stream/tmp/diag-aes128.txt -pass pass:password && cat /var/www/aleator.stream/tmp/diag-aes128.txt && rm -f /var/www/aleator.stream/tmp/diag-aes128.txt');

			print '<p><strong>AES-128-CBC: </strong>' . $aes128 . '</p>';

			$aes192 = shell_exec('openssl aes-192-cbc -a -salt -in /var/www/aleator.stream/tmp/diag.txt  -out /var/www/aleator.stream/tmp/diag-aes192.txt -pass pass:password && cat /var/www/aleator.stream/tmp/diag-aes192.txt && rm -f /var/www/aleator.stream/tmp/diag-aes192.txt');

			print '<p><strong>AES-192-CBC: </strong>' . $aes192 . '</p>';

			$aes256 = shell_exec('openssl aes-256-cbc -a -salt -in /var/www/aleator.stream/tmp/diag.txt  -out /var/www/aleator.stream/tmp/diag-aes256.txt -pass pass:password && cat /var/www/aleator.stream/tmp/diag-aes256.txt && rm -f /var/www/aleator.stream/tmp/diag-aes256.txt');

			print '<p><strong>AES-256-CBC: </strong>' . $aes256 . '</p>';

			$camellia128 = shell_exec('openssl camellia-128-cbc -a -salt -in /var/www/aleator.stream/tmp/diag.txt  -out /var/www/aleator.stream/tmp/diag-camellia128.txt -pass pass:password && cat /var/www/aleator.stream/tmp/diag-camellia128.txt && rm -f /var/www/aleator.stream/tmp/diag-camellia128.txt');

			print '<p><strong>Camellia-128-CBC: </strong>' . $camellia128 . '</p>';

			$camellia192 = shell_exec('openssl camellia-192-cbc -a -salt -in /var/www/aleator.stream/tmp/diag.txt  -out /var/www/aleator.stream/tmp/diag-camellia192.txt -pass pass:password && cat /var/www/aleator.stream/tmp/diag-camellia192.txt && rm -f /var/www/aleator.stream/tmp/diag-camellia192.txt');

			print '<p><strong>Camellia-192-CBC: </strong>' . $camellia192 . '</p>';

			$camellia256 = shell_exec('openssl camellia-256-cbc -a -salt -in /var/www/aleator.stream/tmp/diag.txt  -out /var/www/aleator.stream/tmp/diag-camellia256.txt -pass pass:password && cat /var/www/aleator.stream/tmp/diag-camellia256.txt && rm -f /var/www/aleator.stream/tmp/diag-camellia256.txt');

			print '<p><strong>Camellia-256-CBC: </strong>' . $camellia256 . '</p>';

			$bf = shell_exec('openssl bf-cbc -a -salt -in /var/www/aleator.stream/tmp/diag.txt  -out /var/www/aleator.stream/tmp/diag-bf.txt -pass pass:password && cat /var/www/aleator.stream/tmp/diag-bf.txt && rm -f /var/www/aleator.stream/tmp/diag-bf.txt');

			print '<p><strong>BF-CBC: </strong>' . $bf . '</p>';

			shell_exec('rm -f /var/www/aleator.stream/tmp/diag.txt');
		?>

		<script>
			function scriptTest(){
				var noscript = document.getElementById('noscript');
				var script = document.getElementById('script');
				noscript.style.display = 'none';
				script.innerHTML = "JavaScript is enabled.";
			}
			window.onload = scriptTest();
		</script>
	</body>
</html>
