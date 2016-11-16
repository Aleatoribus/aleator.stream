<?php
	$title = 'About | Aleator Stream';
	include("inc/header.inc");
?>
		
			<h2>The project</h2>

			<p>
				Aleator Stream is a group project by the Aleatoribus group as part of Building IT Systems at RMIT University in 2016.
			</p>

			<h2>Project contributors</h2>

			<p>
				<strong>
				<?php
					$i = 1;
					$contributors = array("Aaron", "Samuel", "Vanja", "Phu");
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
						Aaron is a final year Associate Degree of Information Technology student, <br>and has previously completed a Certificate IV in Information Technology Networking at RMIT.
					</p>
					<p style="font-size: 85%;">
						Aaron expects to complete the Bachelor’s Degree of Information Technology in 2017.
					</p>
					<p style="font-size: 85%;">
						<i class="fa fa-linkedin" aria-hidden="true"></i> - <a href="https://www.linkedin.com/in/aghorler">LinkedIn</a>
					</p>
				</div>

				<div id="Samuel" style="display: none;">
					<img src="media/samuel.png" alt="Sameul." width="20%" height="20%"/>
					<p style="font-size: 85%;">
						Samuel is a final year Associate Degree of Information Technology student, <br>and has previously completed a Certificate IV in Information Technology Networking at RMIT.
					</p>
					<p style="font-size: 85%;">
						Samuel expects to complete the Bachelor’s Degree of Information Technology in 2017.
					</p>
					<p style="font-size: 85%;">
						<i class="fa fa-linkedin" aria-hidden="true"></i> - <a href="https://www.linkedin.com/in/samuel-thwin-67285996">LinkedIn</a>
					</p>
				</div>

				<div id="Phu" style="display: none;">
					<img src="media/phu.png" alt="Phu." width="20%" height="20%"/>
					<p style="font-size: 85%;">
						Phu is a first year Bachelor’s Degree of Information Technology student.
					</p>
				</div>

				<div id="Vanja" style="display: none;">
					<img src="media/vanja.png" alt="Vanja." width="20%" height="20%"/>
					<p style="font-size: 85%;">
						Vanja is a first year Bachelor’s Degree of Information Technology student.
					</p>
					<p style="font-size: 85%;">
						<i class="fa fa-linkedin" aria-hidden="true"></i> - <a href="https://www.linkedin.com/in/vanja-novakovic-a07463123">LinkedIn</a>
					</p>
				</div>
			</p>

			<p style="font-size: 75%;">
				Click a contributors name for more information.
			</p>

			<hr>

			<h2>Project information</h2>

			<p>
				We're making an open source cloud file storage and sharing website with user-managed encryption that uses keys determined solely by the user on a per-upload basis.
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
			
<?php
	include("inc/footer.inc");
?>
