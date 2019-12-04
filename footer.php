	</section><!-- #main-content -->
<footer id="colophon">
	<ul class="nav clearfix">
		<!-- <li class="label by">Project by</li> -->
		<!--<li class="label supported">Supported by</li> -->
		<li><a class="icon siej" href="http://www.siej.or.id/" target="_blank">Society of Indonesian Environmental Journalists</a></li>
		<li><a class="icon internews" href="http://www.internews.org" target="_blank">Internews</a></li>
		<li><a class="icon packard" href="http://www.packard.org/" target="_blank">Packard Foundation</a></li>
		<li><a class="icon ecolab" href="http://ecolab.oeco.org.br/" target="_blank">EcoLab</a></li>
	</ul>
</footer>
<footer class="global-footer">
	<div class="global-footer__bd">

		<nav class="footer-directional">
			<ul>
				<?php wp_nav_menu( array( 'theme_location' => 'footer-section-1' ) ); ?>
			</ul>
		</nav>

		<nav class="footer-utility">
			<ul>
				<?php wp_nav_menu( array( 'theme_location' => 'footer-section-2' ) ); ?>
			</ul>
		</nav>

		<div class="footer-partners">
			<h6>Partner Sites</h6>
			<ul>
				<?php wp_nav_menu( array( 'theme_location' => 'footer-section-3' ) ); ?>
			</ul>
		</div>

		<div class="footer-social">
			<h6>Follow us on social media</h6>
			<a href="https://twitter.com/MekongEye"><img class="social" src="<?php bloginfo('stylesheet_directory');?>/images/twitter-icon.png" alt="twitter"></a>
			<a href="https://www.facebook.com/MekongEye"><img class="social" src="<?php bloginfo('stylesheet_directory');?>/images/facebook-icon.png" alt="facebook"></a>
		</div>

		<div class="footer-subscribe">
			<h6>Subscribe for email updates</h6>
			<?php dynamic_sidebar( 'subscriber_widgets' ); ?>
		</div>

		<div class="footer-sponsors">
			<p>
				<a href="http://earthjournalism.net" style="width: 150px;"><img src="<?php bloginfo('stylesheet_directory');?>/images/ejn-logo-hi.png" alt="Earth Journalism Network"></a>
				<a href="https://www.internews.org" style="width: 180px;"><img src="<?php bloginfo('stylesheet_directory');?>/images/internews-logo-colorized.png" alt="Internews"></a>
			</p>
		</div>

		<div class="footer-contact">
			<p>
				<a href="mailto:editor@mekongeye.com">editor@mekongeye.com</a><br>
				518/5 Maneeya Center Bldg., 10th Flr.,<br>
				Ploenchit Rd., Pathumwan,<br>
				Bangkok, 10330<br>
				Thailand
			</p>
		</div>

	</div>
</footer>
<?php wp_footer(); ?>

	</div>
    <!-- end container -->

	<!-- local scripts -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <!-- <script src="/wp-content/themes/jeo-aggregator/js/mekong-global.js"></script> -->

        <script> 
	/**
	* Function that tracks a click on an outbound link in Analytics.
	* This function takes a valid URL string as an argument, and uses that URL string
	* as the event label. Setting the transport method to 'beacon' lets the hit be sent
	* using 'navigator.sendBeacon' in browser that support it.
	*/
	var trackOutboundLink = function(url) {
	   ga('send', 'event', 'outbound', 'click', url, {
	     'transport': 'beacon',
	     'hitCallback': function(){document.location = url;}
	   });
	}
	</script>
</body>
</html>
