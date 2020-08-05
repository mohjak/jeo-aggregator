<?php get_header(); ?>

<?php if(have_posts()) : the_post(); ?>

<?php
$author   = get_post_meta( $id, 'author_name', true );
$location = get_post_meta( $id, 'geocode_address', true );
$pub_name = get_post_meta( $id, 'pub_name' , true );
$url      = get_post_meta( $id, 'url', true );
if ($pub_name != '' and $url != '') {
    $pub_name = '<a href="' . $url . '">' . $pub_name . '</a>';
} else {
    $pub_name = '';
}
?>

	<article class="single-post">
		<section id="stage" class="row">
			<div class="container">
				<div class="twelve columns">
					<header class="post-header">
						<?php echo get_the_term_list($post->ID, 'publisher', '', ', ', ''); ?>
						<h1 class="title"><?php the_title(); ?></h1>
						<?php if (get_post_meta(get_the_ID(), 'is_label', true) == "1"): ?>
			            <a href="#"><span class="label">Belt and Road</span></a>
			            <?php endif; ?>
						<h2 class="subhead"><?php the_excerpt(); ?></h2>
					</header>
					<?php if(has_post_thumbnail()):?>
						<div id="main-map" class="stage-map">
							<?php newsroom_featured_media(); ?>
						</div>
					<?php endif ?>
				</div>
			</div>
		</section>

		<section id="content">
			<div class="container row">
				<div class="post-content">
					<div class="seven columns">
						<div class="post-description">
							<p class="by">By <strong><?php echo $author ?></strong></p>
							<p class="date"><i><?php echo ($location != '' ? $location .',' : '');?></i> <?php echo get_the_date(); ?> </p>
							<p class="source"><?php echo $pub_name ?></p>
							<?php the_content(); ?>
						</div>
					</div>
					<div class="five columns">
						<?php $thumbnail = ekuatorial_get_thumbnail(); ?>
						<div class="thumbnail">
							<?php $type = get_post_meta($post->ID, 'newsroom_featured_media_type', true); ?>
							<?php if(($thumbnail) and $type != 'image') : ?>
								<img src="<?php echo $thumbnail; ?>" />
							<?php endif; ?>
							<a class="button" href="<?php echo get_post_meta($post->ID, 'url', true); ?>" target="_blank"><?php _e('Go to the original article', 'ekuatorial'); ?></a>
							<p class="buttons">
								<?php
								// by mohjak 2020-07-27 issue#283: Show "Embed this story" button only if the option of Sare Widget is disabled
								if (JEO_Share_Widget::is_enabled()) {
								?>
									<a class="button embed-button" href="<?php echo jeo_get_share_url(array('p' => $post->ID)); ?>" target="_blank"><?php _e('Embed this story', 'ekuatorial'); ?></a>
								<?php } ?>
								<?php // by mohjak fix issue#284 https://tech.openinfo.cc/earth/openearth/-/issues/284 ?>
								<!-- <a class="button print-button" href="<?php /*echo jeo_get_embed_url(array('p' => $post->ID));*/ ?>" target="_blank"><?php /*_e('Print', 'ekuatorial');*/ ?></a> -->
							</p>
							<?php // by mohjak fix issue#277 https://tech.openinfo.cc/earth/openearth/-/issues/277 ?>
							<!-- <div class="fb-like" data-href="<?php /* the_permalink(); */ ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-font="verdana" data-action="recommend"></div> -->
							<div class="fb-share-button" data-href="<?php the_permalink(); ?>" data-layout="button"></div>
							<div class="twitter-button">
								<a href="https://twitter.com/share" class="twitter-share-button" data-via="MekongEye" <?php if(function_exists('qtranxf_getLanguage')) : ?>data-lang="<?php echo qtranxf_getLanguage(); ?>"<?php endif; ?>>Tweet</a>
							</div>
						</div>
					</div>
				</div>

				<script type="text/javascript">
					var embedUrl = jQuery('.embed-button').attr('href');
					//var printUrl = jQuery('.print-button').attr('href');
					jeo.mapReady(function(map) {
						if(map.conf.postID) {
							//jQuery('.print-button').attr('href', printUrl + '&map_id=' + map.conf.postID + '#print');
							jQuery('.embed-button').attr('href', embedUrl + '&map_id=' + map.conf.postID);
						}
					});
					jeo.groupReady(function(group) {
						//jQuery('.print-button').attr('href', printUrl + '&map_id=' + group.currentMapID + '#print');
						jeo.groupChanged(function(group, prevMap) {
							//jQuery('.print-button').attr('href', printUrl + '&map_id=' + group.currentMapID + '#print');
						});
					});
				</script>

			</div>
		</section>
	</article>
<?php endif; ?>

<?php get_footer(); ?>
