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
                <?php 
                $args = array(
                    'post_type' => 'post',
                    'tag' => 'news-digest',
                    'orderby' => 'date',
                    'order'   => 'DESC',
                    'posts_per_page' => 1
                );
                $query = new WP_Query( $args );
                $post = $query->posts[0];
                var_dump($post);
                ?>
                <h5 class="title"><?php echo $post->post_title ?></h5>
                <h5 class="date"><?php echo get_the_date('', $post->ID); ?></h5>
                <hr>
                <img width="200" src="<?php echo wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>" class="logo" style="width: 100%" />
                <div class="new-digest-links">
                    <a href="<?php echo get_post_meta($post->ID, 'story_url', true); ?>" class="view">View Last Edition</a>
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
