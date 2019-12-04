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
$GLOBALS['excluded_post'] = array();
?>

<div class="main">
    <div id="stories-left">
    <?php while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
    <?php endwhile; ?>
    </div>
    <div id="stories-right">
        <section class="sc-container" id="new-digest">
            <img width="200" src="<?php echo get_stylesheet_directory_uri(); ?>/img/ME-NewsDigest-banner.png" class="logo" style="width: 100%" />
            <div class="new-digest-widget">
                <?php
                $args = array(
                    'post_type' => 'post',
                    'pub_type'  => 'new-digest',
                    'orderby' => 'date',
                    'order'   => 'DESC',
                    'posts_per_page' => 1
                );
                $query = new WP_Query( $args );
                $post = $query->posts[0];
                ?>
                <h5 class="title"><?php echo $post->post_title ?></h5>
                <h5 class="date"><?php echo get_the_date('', $post->ID); ?></h5>
                <hr>
                <a href="<?php echo get_post_meta($post->ID, 'url', true); ?>"><img width="200" src="<?php echo wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>" class="logo" style="width: 100%" /></a>
                <div class="new-digest-links">
                    <a href="http://earthjournalism.us9.list-manage2.com/subscribe?u=fd0ac74ee9e05f99d956c80e7&id=5d4083d243" class="subscribe">Subscribe</a>
                </div>
            </div>
        </section>

        <section class="sc-container" id="eye-original">
            <img width="200" src="<?php echo get_stylesheet_directory_uri(); ?>/img/ME-EyeOriginal-banner.png" class="eye-original" style="width: 100%" />
            <div class="sc-slice size-xs">
            <?php
            $args = array(
                'posts_per_page'   => 4,
                'orderby'          => 'post_date',
                'order'            => 'DESC',
                'post_type'        => array('post', 'link', 'sequence', 'map'),
                'post_status'      => 'publish',
                'pub_type'         => 'eye-original',
                'suppress_filters' => true
            );
            $posts = get_posts( $args );
            foreach ( $posts as $post ) {
                $author_name = get_post_meta( $post->ID, 'author_name', true );
            ?>
                <article class="sc-story">
                    <?php
                    $link = get_post_meta($post->ID, 'link_target', true);
                    if ($link != '') {
                        echo '<a href="' . $link .'" target="_blank">';
                    }
                    else {
                        // by mohjak 2019-10-01
                        echo '<a href="' . get_permalink($post->ID) .'">';
                    }
                    ?>
                        <div class="sc-story__bd">
                            <?php
                            $kicker = wp_get_post_terms($post->ID, 'pub_type', array('fields' => 'names'));
                            ?>
                            <h4><?php echo $post->post_title; ?>
                            <i class="dateline"> <?php echo $author_name; ?></i></h4>
                            <?php if (get_post_meta(get_the_ID(), 'is_label', true) == "1"): ?>
                            <a href="#"><span class="label">Belt and Road</span></a>
                            <?php endif; ?>
                        </div>
                    </a>
                </article>
            <?php
            }
            ?>
            </div>
        </section>

        <section class="sc-container" id="twitter">
            <img width="200" src="<?php echo get_stylesheet_directory_uri(); ?>/img/ME-Twitter-banner.png" class="tweets" style="width: 100%" />
            <?php dynamic_sidebar( 'main-sidebar' ); ?>
        </section>

    </div>
</div>

<?php get_footer(); ?>
