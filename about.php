<?php
	@session_start();
	$title = 'About | Aleator Stream';
	include("inc/header.inc");
?>

	<div align="center">
		
		<h2>The project</h2>

		<p>
			Aleator Stream is a group project as part of Building IT Systems at RMIT University.
		</p>

		<h2>Project contributors</h2>

		<p>
			<strong>
			<?php
				$contributors = array("Aaron", "Samuel", "Zac", "Vanja", "Phu");
				shuffle($contributors);
				foreach ($contributors as $contributor) {
				    print "$contributor" . ". ";
				}
			?>
			</strong>
		</p>

		<p>
			Additional information pending.
		</p>
		
		<footer>
			<!-- <hr> -->
			<!-- Aleatoribus is an open source project licensed under version 2.0 of the Apache Licence. -->
		</footer>
	</div>

	</body>
</html>
