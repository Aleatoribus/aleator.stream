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
					<img src="media/incognito.png" alt="No contributor selected." width="20%" height="20%"/>
				</div>

				<div id="Aaron" style="display: none;">
					<img src="media/aaron.png" alt="Aaron." width="20%" height="20%"/>
					<p style="font-size: 85%;">
						Aaron is a final year Associate degree student, and has a Cert IV in IT.
					</p>
				</div>

				<div id="Samuel" style="display: none;">
					<img src="media/samuel.png" alt="Sameul." width="20%" height="20%"/>
					<p style="font-size: 85%;">
						Samuel is a final year Associate degree student, and has a Cert IV in IT.
					</p>
				</div>

				<div id="Phu" style="display: none;">
					<img src="media/phu.png" alt="Phu." width="20%" height="20%"/>
					<p style="font-size: 85%;">
						Phu is a first year Bachelor degree student.
					</p>
				</div>

				<div id="Vanja" style="display: none;">
					<img src="media/vanja.png" alt="Vanja." width="20%" height="20%"/>
					<p style="font-size: 85%;">
						Vanja is a first year Bachelor degree student.
					</p>
				</div>

				<div id="Zac" style="display: none;">
					<img src="media/zac.png" alt="Zac." width="20%" height="20%"/>
					<p style="font-size: 85%;">
						Zac is a first year Bachelor degree student.
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

			<h2>Contact</h2>

			<p>
				For general matters contact <a href="mailto:admin@aleator.stream">admin@aleator.stream</a>.
			</p>

			<p>
				For matters of security requiring urgent attention contact <a href="mailto:security@aleator.stream">security@aleator.stream</a>.
			</p>

			<p>
				<i class="fa fa-key" aria-hidden="true"></i> <a href="media/public.asc">Our PGP public key.</a>
			</p>
			
			<footer>
				<hr>
				Aleatoribus is an <a href="https://github.com/Aleatoribus">open source</a> project licensed under version 2.0 of the Apache Licence.
			</footer>
		</div>

	</body>
</html>
