<?php
get_header();
$stylesheet_uri = get_stylesheet_directory_uri();
?>

<div id="content">
	<section id="hero" class="row-fluid">
		<div class="span8">
			<div class="embed-container">
				<iframe width="560" height="315" src="//www.youtube.com/embed/xVGUWZzQUY0" frameborder="0" allowfullscreen></iframe>
			</div>
		</div>
		<div class="span4">
			<div id="mc_embed_signup">
				<h3>For Early Access To Impaq.me, Sign Up Here:</h3>
				<form action="http://investigativenewsnetwork.us1.list-manage.com/subscribe/post?u=81670c9d1b5fbeba1c29f2865&amp;id=9076e3e80b" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
					<div class="mc-field-group">
						<!--<label for="mce-EMAIL">Email Address </label>-->
						<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
					</div>
					<div class="mc-field-group">
						<label for="mce-FNAME">First Name </label>
						<input type="text" value="" name="FNAME" class="required" id="mce-FNAME">
					</div>
					<div class="mc-field-group">
						<label for="mce-LNAME">Last Name </label>
						<input type="text" value="" name="LNAME" class="required" id="mce-LNAME">
					</div>
					<div class="mc-field-group">
						<label for="mce-MMERGE3">Organization </label>
						<input type="text" value="" name="MMERGE3" class="required" id="mce-MMERGE3">
					</div>
					<div id="mce-responses" class="clear">
						<div class="response" id="mce-error-response" style="display:none"></div>
						<div class="response" id="mce-success-response" style="display:none"></div>
					</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
				    <div style="position: absolute; left: -5000px;"><input type="text" name="b_81670c9d1b5fbeba1c29f2865_9076e3e80b" tabindex="-1" value=""></div>
				    <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
				</form>
			</div>
		</div>
	</section>

	<h3>Impaq.Me Participants</h3>
	<section id="participants" class="row-fluid">
		<div class="span2"><a href="http://cironline.org/"><img src="<?php echo $stylesheet_uri . '/img/cir.png'; ?>" alt="Center for Investigative Reporting Logo" /></a></div>
		<div class="span2"><a href="http://www.minnpost.com/"><img src="<?php echo $stylesheet_uri . '/img/minnpost.png'; ?>" alt="MinnPost Logo" /></a></div>
		<div class="span2"><a href="http://www.motherjones.com/"><img src="<?php echo $stylesheet_uri . '/img/motherjones.png'; ?>" alt="Mother Jones Logo" /></a></div>
		<div class="span2"><a href="http://www.propublica.org/"><img src="<?php echo $stylesheet_uri . '/img/propublica.png'; ?>" alt="ProPublica Logo" /></a></div>
		<div class="span2"><a href="http://voiceofsandiego.org/"><img src="<?php echo $stylesheet_uri . '/img/vosd.png'; ?>" alt="Voice of San Diego Logo" /></a></div>
		<div class="span2"><a href="http://thelensnola.org/"><img src="<?php echo $stylesheet_uri . '/img/thelens.png'; ?>" alt="The Lens Logo" /></a></div>
	</section>
</div>

<?php get_footer(); ?>