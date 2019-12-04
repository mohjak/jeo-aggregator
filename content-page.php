<?php
/*
Template Name: Content Page
*/
get_header();
$map_id = get_post_meta( $post->ID, 'map_id', true);
?>

<div class="container">
    <div class="twelve columns">
        <h1 class="title"><?php the_title(); ?></h1>
        <div id="main-map" <?php post_class('stage-map'); ?>>
            <?php jeo_map($map_id); ?>
        </div>
    </div>
</div>

<section id="content">
    <?php
wp_reset_query();
$topic = wp_get_post_terms($id, 'topic', array('fields' => 'all'));
$topic_name = isset($topic[0]) ? $topic[0]->name : '';
$topic_desc = isset($topic[0]) ? $topic[0]->description : '';
$region = wp_get_post_terms($id, 'region', array('fields' => 'all'));
$region_name = isset($region[0]) ? $region[0]->name : '';
$region_desc = isset($region[0]) ? $region[0]->description : '';
$pub_type = wp_get_post_terms($id, 'pub_type', array('fields' => 'all'));
$pub_type_name = isset($pub_type[0]) ? $pub_type[0]->name : '';
$pub_type_desc = isset($pub_type[0]) ? $pub_type[0]->description : '';
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'posts_per_page'   => 12,
    'paged'            => $paged,
    'orderby'          => 'post_date',
    'order'            => 'DESC',
    'post_type'        => array('post', 'link', 'sequence'),
    'post_status'      => 'publish',
    'suppress_filters' => true,
    'region'           => $region_name,
    'topic'            => $topic_name,
    'pub_type'         => $pub_type_name
);

    $query = query_posts($args);
    get_template_part('section', 'submit-call');
    if(have_posts()) : ?>

        <section id="last-stories" class="loop-section">
            <div class="section-title">
                <div class="container">
                    <div class="twelve columns">
                        <h3><?php _e('Stories on', 'ekuatorial'); ?> &ldquo;<?php the_title(); ?>&ldquo;</h3>
                        <div class="query-actions">
                            <?php
                            global $wp_query;
                            $args = $wp_query->query;
                            $args = array_merge($args, $_GET);
                            $geojson = jeo_get_api_url($args);
                            $download = jeo_get_api_download_url($args);
                            $rss = add_query_arg(array('feed' => 'rss'));
                            ?>
                            <a class="rss" href="<?php echo $rss; ?>"><?php _e('RSS Feed', 'ekuatorial'); ?></a>
                            <a class="geojson" href="<?php echo $geojson; ?>"><?php _e('Get GeoJSON', 'ekuatorial'); ?></a>
                            <a class="download" href="<?php echo $download; ?>"><?php _e('Download', 'ekuatorial'); ?></a>
                            <a class="share-button" href="<?php echo jeo_get_share_url(array('map_id' => $post->ID)); ?>"><?php _e('Embed this map', 'ekuatorial'); ?></a>
                            <div class="fb-like" data-href="<?php the_permalink(); ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-font="verdana" data-action="recommend"></div>
                            <a href="https://twitter.com/share" class="twitter-share-button" data-via="MekongEye" data-lang="<?php if(function_exists('qtranxf_getLanguage')) echo qtranxf_getLanguage(); ?>">Tweet</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <?php get_template_part('loop'); ?>
            </div>
        </section>
    <?php
    endif;
    $query = new WP_Query($args);
        echo paginate_links( array(
            'base' => '%_%',
            'format' => '?paged=%#%',
            'current' => max( 1, get_query_var('paged') ),
            'total' => $query->max_num_pages
    ) );
    wp_reset_query(); ?>

</section>

<?php get_template_part('section', 'main-widget'); ?>

<?php get_footer(); ?>
