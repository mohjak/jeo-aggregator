<ul class="list-posts row">
	<?php while(have_posts()) : the_post(); ?>
		<li id="post-<?php the_ID(); ?>" <?php post_class('post-item four columns'); ?>>
			<article>
				<header class="post-header">
					<?php
					$data_set_post = get_post_meta( get_the_ID(), 'dataset_content', true);
					if ($data_set_post == '1') {
						$tracking = 'onclick="trackOutboundLink(\''. get_the_permalink() .'\');"';
					}else {
						$tracking = '';
					}
					?>
					<a href="<?php the_permalink(); ?>" <?php echo $tracking; ?> title="<?php the_title(); ?>">
						<?php
						if(has_post_thumbnail())
							the_post_thumbnail(array(800, 650));
						else
							echo '<img width="800" hight="650" src="' . get_post_meta($post->ID, 'picture', true) . '" />';
						?>
					</a>
					<h2><a href="<?php the_permalink(); ?>" <?php echo $tracking; ?>><?php the_title(); ?></a></h2>
					<p class="meta clearfix">
						<span class="date">
							<span class="lsf">&#xE15e;</span>
							<span class="date-content"><?php echo get_the_date(_x('m/d/Y', 'reduced date format', 'ekuatorial')); ?></span>
						</span>
						<?php
						if(get_the_terms($post->ID, 'publisher')) :
							?>
							<span class="publisher">
								<span class="lsf">clip</span>
								<span class="publisher-content">
									<?php
									echo array_shift(get_the_terms($post->ID, 'publisher'))->name;
									?>
								</span>
							</span>
							<?php
						endif;
						?>
					</p>
				</header>
				<section class="post-content">
					<?php the_excerpt(); ?>
				</section>
				<footer class="post-actions">
					<div class="buttons">
						<a class="button" href="<?php the_permalink(); ?>"><?php _e('Read more', 'ekuatorial'); ?></a>
						<?php
						// by mohjak 2020-07-27 issue#283: Show "Embed this story" button only if the option of Sare Widget is disabled
      			if (JEO_Share_Widget::is_enabled()) {
      			?>
							<a class="button" href="<?php echo jeo_get_share_url(array('p' => $post->ID)); ?>"><?php _e('Share', 'ekuatorial'); ?></a>
						<?php } ?>
					</div>
				</footer>
			</article>
		</li>
	<?php endwhile; ?>
</ul>
<div class="twelve columns">
  <?php // by mohjak 2020-07-24: issue#280: https://tech.openinfo.cc/earth/openearth/-/issues/280 ?>
	<?php // if(function_exists('wp_paginate')) wp_paginate(); ?>
</div>
<script type="text/javascript">
	(function($) {
		$(document).ready(function() {
			$('.list-posts').imagesLoaded(function() {

				var $media = $('.list-posts .media-limit img');

				$media.each(function() {

					var containerHeight = $(this).parents('.media-limit').height();
					var imageHeight = $(this).height();

					var topOffset = (containerHeight - imageHeight) / 2;

					if(topOffset < 0) {
						$(this).css({
							'margin-top': topOffset
						});
					}

				});

			});
		});

		jeo.mapReady(function(map) {
			$('.list-posts li').click(function() {
				var markerID = $(this).attr('id');

				<?php // by mohjak 2020-07-25 issue#281 https://tech.openinfo.cc/earth/openearth/-/issues/281
				// [Mekong Eye] TypeError: $(...).offset() is undefined ?>
				if ($('#stage').length) {
					$('html,body').animate({
						scrollTop: $('#stage').offset().top
					}, 400);
				}

				map.markers.openMarker(markerID, false);
				return false;
			});
			$('.list-posts li .button').click(function() {
				window.location = $(this).attr('href');
			});
		});
	})(jQuery);
</script>
