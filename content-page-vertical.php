<?php
/*
Template Name: Content Page Vertical
*/
get_header(); ?>

<?php if(have_posts()) : the_post(); ?>
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
// by mohjak 2019-11-24 tag excel line 4 issue#120
$pub_type_desc = isset($pub_type[0]) ? $pub_type[0]->description : '';
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'posts_per_page'   => 10,
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
global $wp_query;
$wp_query = new WP_Query( $args );
$map_id = get_post_meta( $post->ID, 'map_id', true);
$pub_name   = get_post_meta( $id, 'pub_name' , true );
$source_link   = get_post_meta( $id, 'source_link', true );
if ($pub_name != '' and $source_link != '') {
    $pub_name = '<a href="' . $source_link . '">' . $pub_name . '</a>';
} else {
    $pub_name = '';
}?>
<div class="main">
    <a name="content"></a>
    <div class="section-list">
    <header class="section-header">
        <h1><?php echo $topic_name, $region_name, $pub_type_name; ?></h1>
        <h2 class="subhead"><?php echo $topic_desc, $region_desc, $pub_type_desc; ?></h2>
    </header>
    <div id="main-map" <?php post_class('stage-map'); ?>>
        <?php jeo_map($map_id); ?>
    <section id="last-stories" class="loop-section">
        <div class="section-title">
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
                <?php
                // by mohjak 2020-07-27 issue#283: Show "Embed this story" button only if the option of Sare Widget is disabled
		        if (JEO_Share_Widget::is_enabled()) {
                ?>
                  <a class="share-button" href="<?php echo jeo_get_share_url(array('map_id' => $post->ID)); ?>"><?php _e('Embed this map', 'ekuatorial'); ?></a>
                <?php } ?>
                <?php // by mohjak fix issue#277 https://tech.openinfo.cc/earth/openearth/-/issues/277 ?>
                <!-- <div class="fb-like" data-href="<?php /*the_permalink();*/ ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-font="verdana" data-action="recommend"></div> -->
                <div class="fb-share-button" data-href="<?php the_permalink(); ?>" data-layout="button"></div>
                <a href="https://twitter.com/share" class="twitter-share-button" data-via="MekongEye" data-lang="<?php if(function_exists('qtranxf_getLanguage')) echo qtranxf_getLanguage(); ?>">Tweet</a>
            </div>
        </div>
    </section>
    </div>
        <div class="sv-slice">
            <?php foreach ( $wp_query->posts as $post ) { ?>
            <article class="sv-story">
                <?php
                if (has_post_thumbnail($post->ID)) {
                ?>
                    <div class="sv-story__hd">
                        <?php
                        $link = get_post_meta($post->ID, 'link_target', true);
                        if ($link != '') {
                            echo '<a href="' . $link .'" target="_blank">';
                        } else {
                            // by mohjak 2019-10-01
                            echo '<a href="' . get_permalink($post->ID) .'" target="_blank">';
                        }
                        $thumbnail = get_the_post_thumbnail( $post->ID );
                        echo $thumbnail;
                        ?>
                        </a>
                    </div>
                <?php
                }
                ?>

                <div class="sv-story__bd">
                    <?php
                    $kicker = wp_get_post_terms($post->ID, 'pub_type', array('fields' => 'names'));
                    if (isset($kicker[0]) && $kicker[0] != '') {
                    ?>
                        <p class="kicker"><?php echo $kicker[0]; ?></p>
                    <?php
                    }
                    ?>

                    <h2>
                        <?php
                        $link = get_post_meta($post->ID, 'link_target', true);
                        if ($link != '') {
                            echo '<a href="' . $link .'" target="_blank">';
                        } else {
                            // by mohjak 2019-10-01
                            echo '<a href="' . get_permalink($post->ID) .'" target="_blank">';
                        }
                        ?><?php echo $post->post_title ?></a>
                    </h2>
                    <?php
                        $date = get_post_meta( $post->ID, 'date', true);
                        if ($date == '') {
                            $date = get_the_date( 'j M Y', $post->ID );
                        }
                    ?>
                    <p class="dateline"><?php echo $date ?></p>
                    <?php
                    $pub_name   = get_post_meta( $post->ID, 'pub_name' , true );
                    $source_link   = get_post_meta( $post->ID, 'source_link', true );
                    if ($pub_name != '' and $source_link != '') {
                        $pub_name = '<a href="' . $source_link . '" target="_blank">' . $pub_name . '</a>';
                    } else {
                        $pub_name = '';
                    }
                    ?>
                    <p class="source"><?php echo $pub_name ?></p>
                </div>
                <div class="sv-story__ft">
                    <?php
                        echo $post->post_excerpt;
                        $custom_link_text = get_post_meta( $post->ID, 'custom_link_text', true);
                        if ($custom_link_text == '') {
                            $custom_link_text = 'read more';
                        }
                    ?>
                    <p class="more">
                        <?php

                        $link = get_post_meta($post->ID, 'link_target', true);
                        if ($link != '') {
                            echo '<a href="' . $link .'" target="_blank">';
                        } else {
                            // by mohjak 2019-10-01
                            echo '<a href="' . get_permalink($post->ID) .'">';
                        }
                        echo $custom_link_text;
                        ?>
                         &raquo</a>
                    </p>
                </div>
            </article>
            <?php } ?>
        </div>
        <?php
            $query = new WP_Query($args);
            echo paginate_links( array(
                'base' => '%_%',
                'format' => '?paged=%#%',
                'current' => max( 1, get_query_var('paged') ),
                'total' => $query->max_num_pages
        ) );
        ?>

    </div>
    <div id="stories-right">
        <section class="sc-container" id="recent">
                <h2 class="alt">News Stream</h2>
                <div class="sc-slice size-xs">
                <?php
                $args = array(
                    'posts_per_page'   => 10,
                    'orderby'          => 'post_date',
                    'order'            => 'DESC',
                    'post_type'        => array('post', 'link', 'sequence'),
                    'post_status'      => 'publish',
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
                            // by mohjak 2019-11-24 tag excel line 6 issue#120
                            $pub_name = get_post_meta($post->ID, 'pub_name', true);
                            ?>
                            <h4><?php
                                if (count($kicker) > 0 && isset($kicker[0])) {
                                    // by mohjak 2019-11-24 Fix excel line 5 issue#120
                                    echo ($kicker[0] == '') ? '' : '<b class="kicker">' . $kicker[0] . '</b> ';
                                } ?> <?php echo $post->post_title; ?> <em><?php echo $pub_name;?></em></h4>
                            </div>
                        </a>
                    </article>
                <?php
                }
                ?>
                </div>
            </section>
            <section class="sc-container" id="editor">
                <h2 class="alt">Editor’s Picks</h2>
                <div class="sc-slice size-xs">
                <?php
                $args = array(
                    'posts_per_page'   => 10,
                    'orderby'          => 'post_date',
                    'order'            => 'DESC',
                    'post_type'        => array('post', 'link', 'sequence'),
                    'post_status'      => 'publish',
                    'meta_key'         => 'editor_pick',
                    'meta_value'       => "true",
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
                            $pub_name = get_post_meta( $post->ID, 'pub_name', true);
                            ?>
                            <h4><?php
                            if (count($kicker) > 0 && isset($kicker[0])) {
                                echo ($kicker[0] == '' ? '' : '<b class="kicker">' . $kicker[0] . '</b> ');
                            } ?> <?php echo $post->post_title; ?> <em><?php echo $pub_name;?></em></h4>
                            </div>
                        </a>
                    </article>
                <?php
                }
                ?>
                </div>
            </section>
    </div>

</div>

<?php endif; ?>

<?php get_footer(); ?>

