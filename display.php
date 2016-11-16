<?php
	function displayContent($file, $type){
		/* https://github.com/tuxxin/MP4Streaming/blob/master/streamer.php */
		if(file_exists($file)){
			$fp = @fopen($file, 'rb');
			$size = filesize($file);
			$length = $size;
			$start = 0;
			$end = $size - 1;
			header('Content-type: ' . $type);
			header("Accept-Ranges: bytes");
			if(isset($_SERVER['HTTP_RANGE'])){
				$c_start = $start;
				$c_en = $end;
				list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
				if(strpos($range, ',') !== false){
					header('HTTP/1.1 416 Requested Range Not Satisfiable');
					header("Content-Range: bytes $start-$end/$size");
					exit();
				}
				if($range == '-'){
					$c_start = $size - substr($range, 1);
				}
				else{
					$range = explode('-', $range);
					$c_start = $range[0];
					$c_end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
				}
				$c_end = ($c_end > $end) ? $end : $c_end;
				if($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size){
					header('HTTP/1.1 416 Requested Range Not Satisfiable');
					header("Content-Range: bytes $start-$end/$size");
					exit();
				}
				$start = $c_start;
				$end = $c_end;
				$length = $end - $start + 1;
				fseek($fp, $start);
				header('HTTP/1.1 206 Partial Content');
			}
			header("Content-Range: bytes $start-$end/$size");
			header("Content-Length: ".$length);
			$buffer = 1024 * 8;
			while(!feof($fp) && ($p = ftell($fp)) <= $end){
				if($p + $buffer > $end){
					$buffer = $end - $p + 1;
				}
				set_time_limit(0);
				echo fread($fp, $buffer);
				flush();
			}
			fclose($fp);
		}
		exit();
	}

	function downloadContent($file){
		if(file_exists($file)){
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.basename($file).'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			readfile($file);
		}
		exit();
	}

	function isChrome(){
		$user_agent = $_SERVER['HTTP_USER_AGENT'];

		if(strpos($user_agent, 'Chrome')){
			return true;
		}
		else if(strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')){
			return true;
		}
		else{
			return false;
		}
	}

	function getFiletype($undefinedContent){
		$ext = strtolower(pathinfo($undefinedContent, PATHINFO_EXTENSION));
		$contentIndex = array(
			"mp4" => "video/mp4", 
			"png" => "image/png", 
			"mp3" => "audio/mp3", 
			"jpg" => "image/jpeg", 
			"jpeg" => "image/jpeg",
			"gif" => "image/gif",
			"tiff" => "image/tiff",
			"pdf" => "application/pdf",
			"aac" => "audio/x-aac",
			"aif" => "audio/x-aiff",
			"bmp" => "image/bmp",
			"webm" => "video/webm",
			"weba" => "audio/webm",
			"ogv" => "video/ogg",
			"oga" => "audio/ogg");
		
		if($ext == "mkv" && isChrome() == true){
			return "video/mp4";
		}
		else if($ext == "mkv" && isChrome() == false){
			return "error/mkv";
		}
		else{
			if($contentIndex[$ext] != null){
				return $contentIndex[$ext];
			}
			else{
				return "unknown";
			}
		}
	}

	include("inc/security.inc");
	session_start();

	$db_source = "";
	$db_user = "";
	$db_passwd = "";
	$db_use = "";

	$db = new mysqli($db_source, $db_user, $db_passwd, $db_use);

	if(isset($_GET['view'])){
		$dir = $_GET['dir'];
		$name = $_GET['content'];

		if($dir != null || $name != null){
			/* Check if $dir is a valid MD5. */
			if(preg_match('/^[a-f0-9]{32}$/', $dir)){
				$table = "uploads_" . $dir;
			}
			else{
				exit("Invalid directory!");
			}

			$file_verification = $db->prepare("SELECT * FROM $table WHERE upload_file = ? LIMIT 1");
			$file_verification->bind_param("s", $name);
			$file_verification->execute();
			$result = $file_verification->get_result();

			if($result->num_rows == 1){
				$row = $result->fetch_array();

				if($row['shared'] == 1){
					$allowAccess = 1;
				}
				else{
					if(isset($_SESSION['username'])){
						$username = $_SESSION['username'];
						if(md5(strtolower($username)) == $dir){
							$allowAccess = 1;
						}
					}
					else{
						$allowAccess = 0;
					}
				}

				if($allowAccess == 1){
					if($_GET['view'] == 1){ //encrypted
						$prContent = session_id() . "-" . substr($name, 0, -4);
						$tmpContent = "/var/www/aleator.stream/tmp/" . $prContent;

						if(file_exists($tmpContent)){
							$filetype = getFiletype($tmpContent);

							displayContent($tmpContent, $filetype);
						}
						else{
							$title = 'Error | Aleator Stream';
							include("inc/header.inc");
							print '<p>';
							print '<strong>Error</strong>';
							print '</p>';
							print '<p>';
							print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
							print '</p>';
							print '<p style="font-size: 90%; color: red">';
							print "The file you requested was not found on the server. This could be a session ID mismatch.";
							print '</p>';
							include("inc/footer.inc");
						}
						exit();
					}
					else if($_GET['view'] == 2){ //plaintext
						$plnContent = "/var/www/aleator.stream/uploads/" . $dir . "/" . $name;

						if(file_exists($plnContent)){
							$filetype = getFiletype($plnContent);

							displayContent($plnContent, $filetype);
						}
						else{
							$title = 'Error | Aleator Stream';
							include("inc/header.inc");
							print '<p>';
							print '<strong>Error</strong>';
							print '</p>';
							print '<p>';
							print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
							print '</p>';
							print '<p style="font-size: 90%; color: red">';
							print "The file you requested was not found on the server.";
							print '</p>';
							include("inc/footer.inc");
						}
						exit();
					}
				}
				else{
					$title = 'Error | Aleator Stream';
					include("inc/header.inc");
					print '<p>';
					print '<strong>Error</strong>';
					print '</p>';
					print '<p>';
					print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
					print '</p>';
					print '<p style="font-size: 90%; color: red">';
					print "You are not authorised to access this content.";
					print '</p>';
					include("inc/footer.inc");
					exit();
				}
			}
			else{
				$title = 'Error | Aleator Stream';
				include("inc/header.inc");
				print '<p>';
				print '<strong>Error</strong>';
				print '</p>';
				print '<p>';
				print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
				print '</p>';
				print '<p style="font-size: 90%; color: red">';
				print "Content not found in database.";
				print '</p>';
				include("inc/footer.inc");
				exit();
			}
		}
		exit();
	}
	else if(isset($_GET['download'])){
		$dir = $_GET['dir'];
		$name = $_GET['content'];

		if($dir != null || $name != null){
			/* Check if $dir is a valid MD5. */
			if(preg_match('/^[a-f0-9]{32}$/', $dir)){
				$table = "uploads_" . $dir;
			}
			else{
				exit("Invalid directory!");
			}

			$download_verification = $db->prepare("SELECT * FROM $table WHERE upload_file = ? LIMIT 1");
			$download_verification->bind_param("s", $name);
			$download_verification->execute();
			$result = $download_verification->get_result();

			if($result->num_rows == 1){
				$row = $result->fetch_array();

				if($row['shared'] == 1){
					$allowDownload = 1;
				}
				else{
					if(isset($_SESSION['username'])){
						$username = $_SESSION['username'];
						if(md5(strtolower($username)) == $dir){
							$allowDownload = 1;
						}
					}
					else{
						$allowDownlaod = 0;
					}
				}

				if($allowDonwload = 1){
					if($_GET['download'] == 1){ //encrypted
						$prContent = session_id() . "-" . substr($name, 0, -4);
						$tmpContent = "/var/www/aleator.stream/tmp/" . $prContent;

						if(file_exists($tmpContent)){
							downloadContent($tmpContent);
							exit();
						}
						else{
							$title = 'Error | Aleator Stream';
							include("inc/header.inc");
							print '<p>';
							print '<strong>Error</strong>';
							print '</p>';
							print '<p>';
							print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
							print '</p>';
							print '<p style="font-size: 90%; color: red">';
							print "The file you requested was not found on the server. This could be a session ID mismatch.";
							print '</p>';
							include("inc/footer.inc");
							exit();
						}
					}
					else if($_GET['download'] == 2){ //plaintext
						$plnContent = "/var/www/aleator.stream/uploads/" . $dir . "/" . $name;
						if(file_exists($plnContent)){
							downloadContent($plnContent);
							exit();
						}
						else{
							$title = 'Error | Aleator Stream';
							include("inc/header.inc");
							print '<p>';
							print '<strong>Error</strong>';
							print '</p>';
							print '<p>';
							print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
							print '</p>';
							print '<p style="font-size: 90%; color: red">';
							print "The file you requested was not found on the server.";
							print '</p>';
							include("inc/footer.inc");
							exit();
						}
					}
				}
				else{
					$title = 'Error | Aleator Stream';
					include("inc/header.inc");
					print '<p>';
					print '<strong>Error</strong>';
					print '</p>';
					print '<p>';
					print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
					print '</p>';
					print '<p style="font-size: 90%; color: red">';
					print "You are not authorised to download this content.";
					print '</p>';
					include("inc/footer.inc");
					exit();
				}
			}
			else{
				$title = 'Error | Aleator Stream';
				include("inc/header.inc");
				print '<p>';
				print '<strong>Error</strong>';
				print '</p>';
				print '<p>';
				print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
				print '</p>';
				print '<p style="font-size: 90%; color: red">';
				print "Content not found in database.";
				print '</p>';
				include("inc/footer.inc");
				exit();
			}
		}
		exit();
	}
	else if(isset($_GET['delete'])){
		$dir = $_GET['dir'];
		$name = $_GET['content'];

		if($dir == null || $name == null){
			$title = 'Error | Aleator Stream';
			include("inc/header.inc");
			print '<p>';
			print '<strong>Error</strong>';
			print '</p>';
			print '<p>';
			print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
			print '</p>';
			print '<p style="font-size: 90%; color: red">';
			print "Nothing to delete. Invalid link.";
			print '</p>';
			include("inc/footer.inc");
			exit();
		}
		else if(isset($_SESSION['username'])){
			$username = $_SESSION['username'];
			if(md5(strtolower($username)) == $dir){
				if(isset($_POST['password']) && $_POST['password'] != null){
					$password = $_POST['password'];

					$user_verification = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
					$user_verification->bind_param("s", $username);
					$user_verification->execute();
					$result = $user_verification->get_result();

					if($result->num_rows == 1){
						$row = $result->fetch_array();
						$hashed_password = $row['password'];

						if(password_verify($password, $hashed_password)){
							//define locations
							$fileDir = "/var/www/aleator.stream/uploads/" . $dir . "/" . $name;
							$table = "uploads_" . md5(strtolower($username));

							//delete file
							$escapedFileDir = escapeshellcmd($fileDir);
							shell_exec("rm -f $escapedFileDir");

							//remove from table
							$delete_file = $db->prepare("DELETE FROM $table WHERE upload_file = ?");
							$delete_file->bind_param("s", $name);
							$delete_file->execute();

							header("Location:/uploads.php");
							exit();
						}
						else{
							$title = 'Error | Aleator Stream';
							include("inc/header.inc");
							print '<p>';
							print '<strong>Error</strong>';
							print '</p>';
							print '<p>';
							print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
							print '</p>';
							print '<p style="font-size: 90%; color: red">';
							print "Invalid user password specified for content deletion.";
							print '</p>';
							include("inc/footer.inc");
							exit();
						}
					}
				}
				else{
					$title = 'Error | Aleator Stream';
					include("inc/header.inc");
					print '<p>';
					print '<strong>Error</strong>';
					print '</p>';
					print '<p>';
					print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
					print '</p>';
					print '<p style="font-size: 90%; color: red">';
					print "User password not specified. We require your password to delete content.";
					print '</p>';
					include("inc/footer.inc");
					exit();
				}
			}
			else{
				$title = 'Error | Aleator Stream';
				include("inc/header.inc");
				print '<p>';
				print '<strong>Error</strong>';
				print '</p>';
				print '<p>';
				print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
				print '</p>';
				print '<p style="font-size: 90%; color: red">';
				print "You cannot delete this content because you did not upload it.";
				print '</p>';
				include("inc/footer.inc");
				exit();
			}
		}
		else{
			$title = 'Error | Aleator Stream';
			include("inc/header.inc");
			print '<p>';
			print '<strong>Error</strong>';
			print '</p>';
			print '<p>';
			print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
			print '</p>';
			print '<p style="font-size: 90%; color: red">';
			print "You must be logged in to delete content.";
			print '</p>';
			include("inc/footer.inc");
			exit();
		}
	}
	else if(isset($_GET['dir']) && isset($_GET['content'])){
		$content = '/var/www/aleator.stream/uploads/' . $_GET['dir'] . '/' . $_GET['content'];

		if(file_exists($content)){
			$dir = $_GET['dir'];
			$name = $_GET['content'];
			/* Check if $dir is a valid MD5. */
			if(preg_match('/^[a-f0-9]{32}$/', $dir)){
				$table = "uploads_" . $dir;
			}
			else{
				exit("Invalid directory!");
			}

			$file_verification = $db->prepare("SELECT * FROM $table WHERE upload_file = ? LIMIT 1");
			$file_verification->bind_param("s", $name);
			$file_verification->execute();
			$result = $file_verification->get_result();

			if($result->num_rows == 1){
				$row = $result->fetch_array();

				if($row['shared'] == 1){
					$allowAccess = 1;
				}
				else{
					if(isset($_SESSION['username'])){
						$username = $_SESSION['username'];
						if(md5(strtolower($username)) == $dir){
							$allowAccess = 1;
						}
						else{
							$title = 'Error | Aleator Stream';
							include("inc/header.inc");
							print '<p>';
							print '<strong>Error</strong>';
							print '</p>';
							print '<p>';
							print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
							print '</p>';
							print '<p style="font-size: 90%; color: red">';
							print "You are not authorised to access this content.";
							print '</p>';
							include("inc/footer.inc");
							exit();
						}
					}
					else{
						$title = 'Error | Aleator Stream';
						include("inc/header.inc");
						print '<p>';
						print '<strong>Error</strong>';
						print '</p>';
						print '<p>';
						print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
						print '</p>';
						print '<p style="font-size: 90%; color: red">';
						print "You cannot be authorised to access this content because you're not logged in.";
						print '</p>';
						include("inc/footer.inc");
						exit();
					}
				}

				if($row['encrypted'] == 1){
					if($row['allow_server_decryption'] == 1){
						$cipher = $row['cipher'];
						$decTitle = $row['upload_name'];

						if(isset($_GET['decrypt'])){
							$title = 'Decrypt | Aleator Stream';
							include("inc/header.inc");

							print '<p>';
							print '<strong>File decryption: ' . $decTitle . '</strong>';
							print '</p>';
							print '<p>';
							print '<i class="fa fa-unlock-alt" aria-hidden="true" style="font-size: 1000%;"></i>';
							print '</p>';
							print '<p>';
							print "<form action='display.php?dir=$dir&content=$name' method='post' enctype='multipart/form-data'>";
							print 'Key: <input type="password" name="key"/>';
							print ' <input type="submit" value="Decrypt"> ' . '<span style="font-size: 70%;"> (' . strtoupper($cipher) . ')</span>';
							print '</form>';
							print '</p>';

							include("inc/footer.inc");
							exit();
						}
						else{
							if(isset($_POST['key']) && $_POST['key'] != null){
								$key = $_POST['key'];
								$hashedKey = $row['password'];

								if(password_verify($key, $hashedKey)){
									$tmpContent = "/var/www/aleator.stream/tmp/" . session_id() . "-" . substr($name, 0, -4);

									$escapedCipher = escapeshellcmd($cipher);
									$escapedContent = escapeshellcmd($content);
									$escapedTmpConent = escapeshellcmd($tmpContent);
									$escapedKey = escapeshellcmd($key);
									shell_exec("openssl $escapedCipher -d -a -in $escapedContent -out $escapedTmpConent -pass pass:$escapedKey");

									$filetype = getFiletype($tmpContent);

									$title = $decTitle . ' | Aleator Stream';
									include("inc/header.inc");
									print '<p>';
									print '<h3>';
									if($row['shared'] == 1){
										print '<i class="fa fa-users" aria-hidden="true" title="Shared with others"></i> ';
									}
									print '<i class="fa fa-unlock-alt" aria-hidden="true" title="Decrypted by you"></i> ';
									print $decTitle . '</h3>';
									print '</p>';
									print '<p>';
									if(strstr($filetype, '/', TRUE) == "video"){
										print "<video controls style='max-height: 100%; max-width: 100%; width: auto; height: auto;'>";
										print "<source src='display.php?dir=$dir&content=$name&view=1' type='$filetype'>";
										print "</video>";
									}
									else if(strstr($filetype, '/', TRUE) == "audio"){
										print "<audio controls style='max-height: 100%; max-width: 100%; width: auto; height: auto;'>";
										print "<source src='display.php?dir=$dir&content=$name&view=1' type='$filetype'>";
										print "</audio>";
									}
									else if(strstr($filetype, '/', TRUE) == "image"){
										print "<img src='display.php?dir=$dir&content=$name&view=1' style='max-height: 100%; max-width: 100%; width: auto; height: auto;'>";
									}
									else if(strstr($filetype, '/', TRUE) == "application"){ //maybe check check entire string for pdf support only
										print "<object data='display.php?dir=$dir&content=$name&view=1' width='100%' height='800'></object>";
									}
									else if($filetype == "error/mkv"){
										$download = true;
										print '<p>';
										print '<i class="fa fa-cloud-download" aria-hidden="true" style="font-size: 1000%;"></i>';
										print '</p>';
										print "MKV videos can only be played back in Chrome. <a href='display.php?dir=$dir&content=$name&download=1'>Download this video</a>.";
									}
									else if($filetype == "unknown"){
										$download = true;
										print '<p>';
										print '<i class="fa fa-cloud-download" aria-hidden="true" style="font-size: 1000%;"></i>';
										print '</p>';
										print "<a href='display.php?dir=$dir&content=$name&download=1'>Download this file.</a>";
									}
									print '</p>';
									if($download != true){
										print '<p>';
										print "<i class='fa fa-cloud-download' aria-hidden='true'></i> <a href='display.php?dir=$dir&content=$name&download=1'>Download</a>";
										print '</p>';
									}
									print '<p>';
									print '<i class="fa fa-calculator" aria-hidden="true" title="Checksums can be used to verify the authenticity of a file"></i> <span style="font-size: 85%;"><strong>MD5:</strong> ' . md5_file($tmpContent) . '</span>';
									print '<span style="font-size: 85%;"> | <strong>SHA256:</strong> ' . hash_file('sha256', $tmpContent) . '</span>';
									print '</p>';
									if(isset($_SESSION['username'])){
										$username = $_SESSION['username'];
										if(md5(strtolower($username)) == $dir){
											print '<p>';
											print "<form action='display.php?dir=$dir&content=$name&delete' method='post' enctype='multipart/form-data' style='font-size: 85%;'>";
											print '<i class="fa fa-hdd-o" aria-hidden="true"></i> Size: ' . number_format(filesize($tmpContent) / 1048576, 2) . 'MB | <i class="fa fa-trash" aria-hidden="true" title="Only you can delete this file"></i> Upload deletion: <input type="password" name="password" placeholder="User password"/>';
											print ' <input type="submit" value="Delete">';
											print '</form>';
											print '</p>';
										}
									}
									include("inc/footer.inc");
									exit();
								}
								else{
									$title = 'Error | Aleator Stream';
									include("inc/header.inc");
									print '<p>';
									print '<strong>Error</strong>';
									print '</p>';
									print '<p>';
									print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
									print '</p>';
									print '<p style="font-size: 90%; color: red">';
									print "You entered an incorrect file decryption key.";
									print '</p>';
									include("inc/footer.inc");
									exit();
								}
							}
							else{
								header("Location:display.php?dir=$dir&content=$name&decrypt");
								exit();
							}
						}
					}
					else{
						$title = 'Error | Aleator Stream';
						include("inc/header.inc");
						print '<p>';
						print '<strong>Error</strong>';
						print '</p>';
						print '<p>';
						print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
						print '</p>';
						print '<p style="font-size: 90%; color: red">';
						print "Server-side decryption is not allowed for this file.";
						print '</p>';
						include("inc/footer.inc");
						exit();
					}
				}
				else{
					$plnTitle = $row['upload_name'];
					$filetype = getFiletype($content);

					$title = $plnTitle . ' | Aleator Stream';
					include("inc/header.inc");
					print '<p>';
					print '<h3>';
					if($row['shared'] == 1){
						print '<i class="fa fa-users" aria-hidden="true" title="Shared with others"></i> ';
					}
					print $plnTitle . '</h3>';
					print '</p>';
					print '<p>';
					if(strstr($filetype, '/', TRUE) == "video"){
						print "<video controls style='max-height: 100%; max-width: 100%; width: auto; height: auto;'>";
						print "<source src='display.php?dir=$dir&content=$name&view=2' type='$filetype'>";
						print "</video>";
					}
					else if(strstr($filetype, '/', TRUE) == "audio"){
						print "<audio controls style='max-height: 100%; max-width: 100%; width: auto; height: auto;'>";
						print "<source src='display.php?dir=$dir&content=$name&view=2' type='$filetype'>";
						print "</audio>";
					}
					else if(strstr($filetype, '/', TRUE) == "image"){
						print "<img src='display.php?dir=$dir&content=$name&view=2' style='max-height: 100%; max-width: 100%; width: auto; height: auto;'>";
					}
					else if(strstr($filetype, '/', TRUE) == "application"){
						print "<object data='display.php?dir=$dir&content=$name&view=2' width='100%' height='800'></object>";
					}
					else if($filetype == "error/mkv"){
						$download = true;
						print '<p>';
						print '<i class="fa fa-cloud-download" aria-hidden="true" style="font-size: 1000%;"></i>';
						print '</p>';
						print "MKV videos can only be played back in Chrome. <a href='display.php?dir=$dir&content=$name&download=2'>Download this video</a>.";
					}
					else if($filetype == "unknown"){
						$download = true;
						print '<p>';
						print '<i class="fa fa-cloud-download" aria-hidden="true" style="font-size: 1000%;"></i>';
						print '</p>';
						print "<a href='display.php?dir=$dir&content=$name&download=2'>Download this file.</a>";
					}
					print '</p>';
					if($download != true){
						print '<p>';
						print "<i class='fa fa-cloud-download' aria-hidden='true'></i> <a href='display.php?dir=$dir&content=$name&download=2'>Download</a>";
						print '</p>';
					}
					print '</p>';
					print '<p>';
					print '<i class="fa fa-calculator" aria-hidden="true" title="Checksums can be used to verify the authenticity of a file"></i> <span style="font-size: 85%;"><strong>MD5:</strong> ' . md5_file($content) . '</span>';
					print '<span style="font-size: 85%;"> | <strong>SHA256:</strong> ' . hash_file('sha256', $content) . '</span>';
					print '</p>';
					if(isset($_SESSION['username'])){
						$username = $_SESSION['username'];
						if(md5(strtolower($username)) == $dir){
							print '<p>';
							print "<form action='display.php?dir=$dir&content=$name&delete' method='post' enctype='multipart/form-data' style='font-size: 85%;'>";
							print '<i class="fa fa-hdd-o" aria-hidden="true"></i> Size: ' . number_format(filesize($content) / 1048576, 2) . 'MB | <i class="fa fa-trash" aria-hidden="true" title="Only you can delete this file"></i> Upload deletion: <input type="password" name="password" placeholder="User password"/>';
							print ' <input type="submit" value="Delete">';
							print '</form>';
							print '</p>';
						}
					}
					include("inc/footer.inc");
					exit();
				}
			}
			else{
				$title = 'Error | Aleator Stream';
				include("inc/header.inc");
				print '<p>';
				print '<strong>Error</strong>';
				print '</p>';
				print '<p>';
				print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
				print '</p>';
				print '<p style="font-size: 90%; color: red">';
				print "Content not found in database. It may have been removed.";
				print '</p>';
				include("inc/footer.inc");
				exit();
			}
		}
		else{
			$title = 'Error | Aleator Stream';
			include("inc/header.inc");
			print '<p>';
			print '<strong>Error</strong>';
			print '</p>';
			print '<p>';
			print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
			print '</p>';
			print '<p style="font-size: 90%; color: red">';
			print "File not found on server.";
			print '</p>';
			include("inc/footer.inc");
			exit();
		}
	}
	else{
		$title = 'Error | Aleator Stream';
		include("inc/header.inc");
		print '<p>';
		print '<strong>Error</strong>';
		print '</p>';
		print '<p>';
		print '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size: 1000%;"></i>';
		print '</p>';
		print '<p style="font-size: 90%; color: red">';
		print "Content and directory insufficently specified. Invalid link.";
		print '</p>';
		include("inc/footer.inc");
		exit();
	}
?>
