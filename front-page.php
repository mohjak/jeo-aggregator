<?php 
/*
Template Name: Front Page
*/
get_header(); 
$arg_extra_large = array(
    'width'              => 1080,
    'height'             => 460,
    'crop'               => true,
    'crop_from_position' => 'center,center',
    'resize'             => true,
    'cache'              => true,
    'default'            => null,
    'jpeg_quality'       => 70,
    'resize_animations'  => false,
    'return'             => 'url',
    'background_fill'    => null
);
$arg_large = array(
    'width'              => 166,
    'height'             => 166,
    'crop'               => true,
    'crop_from_position' => 'center,center',
    'resize'             => true,
    'cache'              => true,
    'default'            => null,
    'jpeg_quality'       => 70,
    'resize_animations'  => false,
    'return'             => 'url',
    'background_fill'    => null
);
$arg_medium = array(
    'width'              => 64,
    'height'             => 64,
    'crop'               => true,
    'crop_from_position' => 'center,center',
    'resize'             => true,
    'cache'              => true,
    'default'            => null,
    'jpeg_quality'       => 70,
    'resize_animations'  => false,
    'return'             => 'url',
    'background_fill'    => null
);
?>
		
<div class="main">
    <div id="stories-left">
    <?php while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
    <?php endwhile; ?>
    </div>
    <div id="stories-right">
        <section class="sc-container" id="new-digest">
            <img width="200" src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo-mekong-banner.png" class="logo" style="width: 100%" />
            <div class="new-digest-widget">
                <h5 class="title">title</h5>
                <h5 class="date">data</h5>
                <hr>
                <img width="200" src="<?php echo get_stylesheet_directory_uri(); ?>/img/new-digest.png" class="logo" style="width: 100%" />
                <div class="new-digest-links">
                    <a href="#" class="view">View Last Edition</a>
                    <a href="#" class="subscribe">Subscribe</a>
                </div>
            </div>
        </section>

        <section class="sc-container" id="twitter">
            <?php dynamic_sidebar( 'subscriber_widgets' ); ?>
        </section>

    </div>
</div>

<?php get_footer(); ?>
