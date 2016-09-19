<?php
	$title = 'About | Aleator Stream';
	include("inc/header.inc");
?>
		
			<h2>The project</h2>

			<p>
				Aleator Stream is a group project by the Aleatoribus group as part of Building IT Systems at RMIT University.
			</p>

			<h2>Project contributors</h2>

			<p>
				<strong>
				<?php
					$i = 1;
					$contributors = array("Aaron", "Samuel", "Zac", "Vanja", "Phu");
					shuffle($contributors);
					foreach ($contributors as $contributor){
						if($i > 1){
							print "					";
						}
						else{
							print "	";
						}
					    print '<a href="javascript:displayContributor(' . "'" . "$contributor" . "'" . ")\">$contributor.</a> " . "\n";
						$i++;
					}
				?>
				</strong>
			</p>

			<p>
				<div id="default">
					<img src="media/incognito.png" alt="No contributor selected." width="15%" height="15%"/>
				</div>

				<div id="Aaron" style="display: none;">
					<img src="media/aaron.png" alt="Aaron." width="15%" height="15%"/>
					<p style="font-size: 85%;">
						Information about Aaron.
					</p>
				</div>

				<div id="Samuel" style="display: none;">
					<img src="media/samuel.png" alt="Sameul." width="15%" height="15%"/>
					<p style="font-size: 85%;">
						Information about Samuel.
					</p>
				</div>

				<div id="Phu" style="display: none;">
					<img src="media/phu.png" alt="Phu." width="15%" height="15%"/>
					<p style="font-size: 85%;">
						Information about Phu.
					</p>
				</div>

				<div id="Vanja" style="display: none;">
					<img src="media/vanja.png" alt="Vanja." width="15%" height="15%"/>
					<p style="font-size: 85%;">
						Information about Vanja.
					</p>
				</div>

				<div id="Zac" style="display: none;">
					<img src="media/zac.png" alt="Zac." width="15%" height="15%"/>
					<p style="font-size: 85%;">
						Information about Zac.
					</p>
				</div>
			</p>

			<p style="font-size: 75%;">
				Click a contributors name for more information.
			</p>

			<h2>Project information</h2>

			<p>
				We're making an open source file sharing service with optional strong encryption. 
			</p>

			<script src="js/displayOptions.js"></script>
			
			<footer>
				<hr>
				Aleatoribus is an <a href="https://github.com/Aleatoribus">open source</a> project licensed under version 2.0 of the Apache Licence.
			</footer>
		</div>

	</body>
</html>
