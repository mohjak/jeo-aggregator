<?php

// metaboxes
include(STYLESHEETPATH . '/inc/metaboxes/metaboxes.php');

include(STYLESHEETPATH . '/inc/category-feeds-widget.php');

include(STYLESHEETPATH . '/inc/advanced-navigation.php');

include(STYLESHEETPATH . '/inc/jeo-post-zoom.php');

include(STYLESHEETPATH . '/inc/featured-media/featured-media.php');

// ekuatorial setup

// register taxonomies
include(STYLESHEETPATH . '/inc/taxonomies.php');
// taxonomy meta
include(STYLESHEETPATH . '/inc/taxonomies-meta.php');

/*
 * Advanced Custom Fields
 */

function ekuatorial_acf_dir() {
	return get_stylesheet_directory_uri() . '/inc/acf/';
}
add_filter('acf/helpers/get_dir', 'ekuatorial_acf_dir');

function ekuatorial_acf_date_time_picker_dir() {
	return ekuatorial_acf_dir() . '/add-ons/acf-field-date-time-picker/';
}
add_filter('acf/add-ons/date-time-picker/get_dir', 'ekuatorial_acf_date_time_picker_dir');

function ekuatorial_acf_repeater_dir() {
	return ekuatorial_acf_dir() . '/add-ons/acf-repeater/';
}
add_filter('acf/add-ons/repeater/get_dir', 'ekuatorial_acf_repeater_dir');

/*
 * Newsroom widgets
 */

if(class_exists('SiteOrigin_Widget')) {
	include_once(STYLESHEETPATH . '/inc/siteorigin-widgets/highlight-carousel/highlight-carousel.php');
	include_once(STYLESHEETPATH . '/inc/siteorigin-widgets/square-posts/square-posts.php');
	include_once(STYLESHEETPATH . '/inc/siteorigin-widgets/list-posts/list-posts.php');
	include_once(STYLESHEETPATH . '/inc/siteorigin-widgets/list-images/list-images.php');
	include_once(STYLESHEETPATH . '/inc/siteorigin-widgets/highlight-posts/highlight-posts.php');
}

function newsroom_pb_parse_query($pb_query) {
	$query = wp_parse_args($pb_query);
    // by mohjak: 2019-11-21 issue#113
	if(isset($query['tax_query']) && $query['tax_query']) {
		$tax_args = explode(',', $query['tax_query']);
		$query['tax_query'] = array();
		foreach($tax_args as $tax_arg) {
			$tax_arg = explode(':', $tax_arg);
			if ( '-' == substr($tax_arg[1], 0, 1) ) {
				$query['tax_query'][] = array(
					'taxonomy' => $tax_arg[0],
					'field' => 'slug',
					'terms' => substr($tax_arg[1], 1),
					'operator' => 'NOT IN',
				);
			} else {
				$query['tax_query'][] = array(
					'taxonomy' => $tax_arg[0],
					'field' => 'slug',
					'terms' => $tax_arg[1]
				);
			}
		}
	}
	return $query;
}


/*
 * Datasets
 */
include(STYLESHEETPATH . '/inc/datasets.php');

function ekuatorial_setup() {

	add_theme_support('post-thumbnails');
	add_image_size('post-thumb', 360, 121, true);
	add_image_size('map-thumb', 200, 200, true);

	// text domain
	load_child_theme_textdomain('ekuatorial', get_stylesheet_directory() . '/languages');

	//sidebars
	register_sidebar(array(
		'name' => __('Main widgets', 'ekuatorial'),
		'id' => 'main-sidebar',
		'description' => __('Widgets used on front and inside pages.', 'ekuatorial'),
		'before_widget' => '<div class="four columns row">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	));

}
add_action('after_setup_theme', 'ekuatorial_setup');

// set OSM geocode
function ekuatorial_geocode_service() {
	return 'osm';
}
add_filter('jeo_geocode_service', 'ekuatorial_geocode_service');

function ekuatorial_scripts() {
	/*
	 * Register scripts & styles
	 */

	// deregister jeo styles
	wp_deregister_style('jeo-main');

	/* Shadowbox */
	wp_register_script('shadowbox', get_stylesheet_directory_uri() . '/lib/shadowbox/shadowbox.js', array('jquery'), '3.0.3');
	wp_register_style('shadowbox', get_stylesheet_directory_uri() . '/lib/shadowbox/shadowbox.css', array(), '3.0.3');

	/* Chosen */
	wp_register_script('chosen', get_stylesheet_directory_uri() . '/lib/chosen.jquery.min.js', array('jquery'), '1.0.0');

	// scripts
	wp_register_script('html5', get_stylesheet_directory_uri() . '/js/html5shiv.js', array(), '3.6.2');
	wp_register_script('submit-story', get_stylesheet_directory_uri() . '/js/submit-story.js', array('jquery'), '0.1.1');

	wp_register_script('twttr', '//platform.twitter.com/widgets.js');

	$lang = '';
	if(function_exists('qtranxf_getLanguage')) {
		$lang = qtranxf_getLanguage();
	}

	// custom marker system
	global $jeo_markers;
	wp_deregister_script('jeo.markers');
	wp_register_script('jeo.markers', get_stylesheet_directory_uri() . '/js/ekuatorial.markers.js', array('jeo', 'underscore', 'shadowbox', 'twttr'), '0.3.16', true);
	wp_localize_script('jeo.markers', 'ekuatorial_markers', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
		'query' => $jeo_markers->query(),
		'stories_label' => __('stories', 'ekuatorial'),
		'home' => (is_front_page() && !is_paged()),
		'copy_embed_label' => __('Copy the embed code', 'ekuatorial'),
		'share_label' => __('Share', 'ekuatorial'),
		'print_label' => __('Print', 'ekuatorial'),
		'embed_base_url' => home_url('/' . $lang . '/embed/'),
		'share_base_url' => home_url('/' . $lang . '/share/'),
		'marker_active' => array(
			'iconUrl' => get_stylesheet_directory_uri() . '/img/marker_active.png',
			'iconSize' => array(26, 30),
			'iconAnchor' => array(13, 30),
			'popupAnchor' => array(0, -40),
			'markerId' => 'none'
		),
		'language' => $lang,
		'site_url' => home_url('/'),
		'read_more_label' => __('Read more', 'ekuatorial'),
		'lightbox_label' => array(
			'slideshow' => __('Open slideshow', 'ekuatorial'),
			'videos' => __('Watch video gallery', 'ekuatorial'),
			'video' => __('Watch video', 'ekuatorial'),
			'images' => __('View image gallery', 'ekuatorial'),
			'image' => __('View fullscreen image', 'ekuatorial'),
			'infographic' => __('View infographic', 'ekuatorial'),
			'infographics' => __('View infographics', 'ekuatorial')
		),
		'enable_clustering' => jeo_use_clustering() ? true : false,
		'default_icon' => jeo_formatted_default_marker()
	));

	wp_enqueue_script('ekuatorial-sticky', get_stylesheet_directory_uri() . '/js/sticky-posts.js', array('jeo.markers', 'jquery'), '0.1.2');

	// styles
	wp_register_style('site', get_stylesheet_directory_uri() . '/css/site.css', array(), '1.2'); // old styles
	wp_register_style('reset', get_stylesheet_directory_uri() . '/css/reset.css', array(), '2.0');
	wp_register_style('main', get_stylesheet_directory_uri() . '/css/main.css', array('jeo-skeleton', 'jeo-base', 'jeo-lsf'), '1.2.5');

	/*
	 * Enqueue scripts & styles
	 */
	// scripts
	wp_enqueue_script('html5');
	wp_enqueue_script('submit-story');
	// styles
	wp_enqueue_style('site');
	//wp_enqueue_style('reset');
	wp_enqueue_style('webfont-lato', '//fonts.googleapis.com/css?family=Lato:900');
	wp_enqueue_style('main');
	wp_enqueue_style('shadowbox');

	wp_localize_script('submit-story', 'ekuatorial_submit', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
		'success_label' => __('Success! Thank you, your story will be reviewed by one of our editors and soon will be online.', 'ekuatorial'),
		'redirect_label' => __('You\'re being redirect to the home page in 4 seconds.', 'ekuatorial'),
		'home' => home_url('/'),
		'error_label' => __('Oops, please try again in a few minutes.', 'ekuatorial')
	));

	wp_enqueue_script('ekuatorial-print', get_stylesheet_directory_uri() . '/js/ekuatorial.print.js', array('jquery', 'imagesloaded'));


	wp_register_script('sly', get_stylesheet_directory_uri() . '/lib/sly.min.js', array('jquery'));
	wp_enqueue_script('ekuatorial-site', get_stylesheet_directory_uri() . '/js/site.js', array('jquery','sly'));


}
add_action('wp_enqueue_scripts', 'ekuatorial_scripts', 100);

function ekuatorial_enqueue_marker_script() {
	wp_enqueue_script('ekuatorial.markers');
}
add_action('wp_footer', 'ekuatorial_enqueue_marker_script');

// ajax calendar
include(STYLESHEETPATH . '/inc/ajax-calendar.php');

// story fragment title
add_filter('wp_title', 'ekuatorial_story_fragment_title', 10, 2);
function ekuatorial_story_fragment_title($title, $sep) {
	if(isset($_GET['_escaped_fragment_'])) {
		$args = substr($_GET['_escaped_fragment_'], 1);
		parse_str($args, $query);
		if(isset($query['story'])) {
			$title = get_the_title(substr($query['story'], 9));
			return $title . ' ' . $sep . ' ';
		}
	}
	return $title;
}

// add qtranxf filter to get_permalink
if(function_exists('qtranxf_convertURL'))
	add_filter('post_type_link', 'qtranxf_convertURL');

// custom marker data
function ekuatorial_marker_data($data) {
	global $post;

	$permalink = $data['url'];
	if(function_exists('qtranxf_getLanguage'))
		$permalink = qtranxf_convertURL($data['url'], qtranxf_getLanguage());

	$data['permalink'] = $permalink;
	$data['url'] = get_post_meta($post->ID, 'url', true) ? get_post_meta($post->ID, 'url', true) : $data['permalink'];
    $data['content'] = get_the_excerpt();
    // by mohjak: 2019-11-21 excel line 20 issue#120
    // Correct typo issue
    if (function_exists('ekuatorial_get_content_media')) {
        $data['slideshow'] = ekuatorial_get_content_media();
    }
	if(get_post_meta($post->ID, 'geocode_zoom', true))
		$data['zoom'] = get_post_meta($post->ID, 'geocode_zoom', true);
	// source
	$publishers = get_the_terms($post->ID, 'publisher');
	if($publishers) {
		$publisher = array_shift($publishers);
		$data['source'] = apply_filters('single_cat_title', $publisher->name);
	}
	// thumbnail
	$data['thumbnail'] = ekuatorial_get_thumbnail();
	return $data;
}
add_filter('jeo_marker_data', 'ekuatorial_marker_data');

function ekuatorial_get_thumbnail($post_id = false) {
	global $post;
	$post_id = $post_id ? $post_id : $post->ID;
	$thumb_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'post-thumb');
	if($thumb_src)
		return $thumb_src[0];
	else
		return get_post_meta($post->ID, 'picture', true);
}

// geocode box
include(STYLESHEETPATH . '/inc/geocode-box.php');

// submit story
include(STYLESHEETPATH . '/inc/submit-story.php');

// import geojson
//include(STYLESHEETPATH . '/inc/import-geojson.php');

// remove page from search result

function ekuatorial_remove_page_from_search($query) {
	if($query->is_search) {
		$query->set('post_type', 'post');
	}
	return $query;
}
add_filter('pre_get_posts', 'ekuatorial_remove_page_from_search');

function ekuatorial_all_markers_if_none($posts, $query) {
	if(empty($posts))
		$posts = get_posts(array('post_type' => 'post', 'posts_per_page' => 100));
	return $posts;
}
//add_filter('jeo_the_markers', 'ekuatorial_all_markers_if_none', 10, 2);

// multilanguage publishers
add_action('publisher_add_form', 'qtranxf_modifyTermFormFor');
add_action('publisher_edit_form', 'qtranxf_modifyTermFormFor');

// limit markers per page
function ekuatorial_markers_limit() {
	return 100;
}
add_filter('jeo_markers_limit', 'ekuatorial_markers_limit');

// flush w3tc on save_post
function ekuatorial_flush_w3tc() {
	if(function_exists('flush_pgcache')) {
		flush_pgcache();
	}
}
add_action('save_post', 'ekuatorial_flush_w3tc');

// disable sidebar on single map
function ekuatorial_story_sidebar($conf) {
	if(is_singular('post')) {
		$conf['disableSidebar'] = true;
	}
	return $conf;
}
add_filter('jeo_map_conf', 'ekuatorial_story_sidebar');
add_filter('jeo_mapgroup_conf', 'ekuatorial_story_sidebar');

// search placeholder
function ekuatorial_search_placeholder() {
	global $wp_the_query;
	$placeholder = __('Search for stories', 'ekuatorial');
	if($wp_the_query->is_singular(array('map', 'map-group')))
		$placeholder = __('Search for stories on this map', 'ekuatorial');
	elseif($wp_the_query->is_tax('publisher'))
		$placeholder = __('Search for stories on this publisher', 'ekuatorial');

	return $placeholder;
}

// embed custom stuff

function ekuatorial_before_embed() {
	remove_action('wp_footer', 'ekuatorial_submit');
	remove_action('wp_footer', 'ekuatorial_geocode_box');
}
add_action('jeo_before_embed', 'ekuatorial_before_embed');

function ekuatorial_embed_type($post_types) {
	if(get_query_var('embed')) {
		$post_types = 'map';
	}
	return $post_types;
}
add_filter('jeo_featured_map_type', 'ekuatorial_embed_type');



// twitter card

function ekuatorial_share_meta() {

	if(is_singular('post')) {
		if(function_exists('jeo_get_mapbox_image'))
			$image = jeo_get_mapbox_image(false, 435, 375, jeo_get_marker_latitude(), jeo_get_marker_longitude(), 7);
	} elseif(is_singular('map')) {
		if(function_exists('jeo_get_mapbox_image'))
			$image = jeo_get_mapbox_image(false, 435, 375);
	} elseif(isset($_GET['_escaped_fragment_'])) {

		$fragment = $_GET['_escaped_fragment_'];

		$vars = str_replace('/', '', $fragment);
		$vars = explode('%26', $vars);

		$query = array();
		foreach($vars as $var) {
			$keyval = explode('=', $var);
			if($keyval[0] == 'story') {
				$post_id = explode('post-', $keyval[1]);
				$query[$keyval[0]] = $post_id[1];
				continue;
			}
			if($keyval[0] == 'loc') {
				$loc = explode(',', $keyval[1]);
				$query['lat'] = $loc[0];
				$query['lng'] = $loc[1];
				$query['zoom'] = $loc[2];
				continue;
			}
			$query[$keyval[0]] = $keyval[1];
		}

		if($query['story']) {
			global $post;
			setup_postdata(get_post($query['story']));
		}

		if(isset($query['map'])) {
			$map_id = $query['map'];
		}

		if($query['lat'] && $query['lng'] && $query['zoom']) {
			$lat = $query['lat'];
			$lng = $query['lng'];
			$zoom = $query['zoom'];
		}

		if(function_exists('jeo_get_mapbox_image'))
			$image = jeo_get_mapbox_image($map_id, 435, 375, $lat, $lng, $zoom);

	}

	?>
	<meta name="twitter:card" content="summary_large_image" />
	<meta name='twitter:site' content="@ekuatorial" />
	<meta name="twitter:url" content="<?php the_permalink(); ?>" />
	<meta name="twitter:title" content="<?php the_title(); ?>" />
	<meta name="twitter:description" content="<?php the_excerpt(); ?>" />

	<meta property="og:title" content="<?php the_title(); ?>" />
	<meta property="og:description" content="<?php the_excerpt(); ?>" />
	<meta property="og:image" content="<?php echo isset($image) ? $image : ''; ?>" />

	<?php
    // by mohjak 2019-10-01
	if(isset($query) && $query['story'])
		wp_reset_postdata();

}
add_action('wp_head', 'ekuatorial_share_meta');

/*
 * Geojson keys according to language (qTranslate fix)
 */

function ekuatorial_geojson_key($key) {
	if(function_exists('qtranxf_getLanguage'))
		$key = '_ia_geojson_' . qtranxf_getLanguage();

	return $key;
}
add_filter('jeo_markers_geojson_key', 'ekuatorial_geojson_key');

function ekuatorial_geojson_keys($keys) {
	if(function_exists('qtranxf_getLanguage')) {
		global $q_config;
		$keys = array();
		foreach($q_config['enabled_languages'] as $lang) {
			$keys[] = '_ia_geojson_' . $lang;
		}
	}
	return $keys;
}
add_filter('jeo_markers_geojson_keys', 'ekuatorial_geojson_keys');

function ekuatorial_flush_rewrite() {
    global $pagenow;
    // by mohjak 2019-10-03
	if(is_admin() && isset($_REQUEST['activated']) && $_REQUEST['activated'] && $pagenow == 'themes.php') {
		global $wp_rewrite;
		$wp_rewrite->init();
		$wp_rewrite->flush_rules();
	}
}
add_action('jeo_init', 'ekuatorial_flush_rewrite');

function ekuatorial_convert_url($url) {
	if(function_exists('qtranxf_convertURL'))
		$url = qtranxf_convertURL($url);

	$pos = strpos($url, '?');
	if($pos === false)
		$url .= '?';
	return $url;
}
add_filter('jeo_embed_url', 'ekuatorial_convert_url');
add_filter('jeo_share_url', 'ekuatorial_convert_url');

function ekuatorial_embed_query($query) {
	if(get_query_var('jeo_map_embed')) {
		if($query->get('p') || $query->get('tax_query')) {
			error_log($query->get('p'));
			$query->set('without_map_query', 1);
		}
	}
}
add_action('pre_get_posts', 'ekuatorial_embed_query');

function ekuatorial_ignore_sticky($query) {
	if($query->is_main_query()) {
		$query->set('ignore_sticky_posts', true);
	}
}
add_action('pre_get_posts', 'ekuatorial_ignore_sticky');

/*
 * CUSTOM IMPLEMENTATION OF WP_DATE_QUERY
 */

if(!class_exists('WP_Date_Query')) {

	require(STYLESHEETPATH . '/inc/date.php');
	add_filter('query_vars', 'ekuatorial_date_query_var');
	add_filter('posts_clauses', 'ekuatorial_date_query_clauses', 10, 2);

}

function ekuatorial_date_query_var($vars) {
	$vars[] = 'date_query';
	return $vars;
}

function ekuatorial_date_query_clauses($clauses, $query) {

	if($query->get('date_query')) {
		$date_query = new WP_Date_Query($query->get('date_query'));
		$clauses['where'] .= $date_query->get_sql();
	}
	return $clauses;
}

function ekuatorial_home_url($path = '') {
	if(function_exists('qtranxf_convertURL'))
		return qtranxf_convertURL(home_url($path));
	else
		return home_url($path);
}

// do not use map query on front page

function ekuatorial_home_query($query) {
	if($query->is_main_query() && $query->is_home) {
		$query->set('without_map_query', 1);
	}
}
add_action('pre_get_posts', 'ekuatorial_home_query');

function create_taxonomies() {
	$topic_labels = array(
		'name'              => _x( 'Topics', 'taxonomy general name' ),
		'singular_name'     => _x( 'Topic', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Topics' ),
		'all_items'         => __( 'All Topics' ),
		'parent_item'       => __( 'Parent Topic' ),
		'parent_item_colon' => __( 'Parent Topic:' ),
		'edit_item'         => __( 'Edit Topic' ),
		'update_item'       => __( 'Update Topic' ),
		'add_new_item'      => __( 'Add New Topic' ),
		'new_item_name'     => __( 'New Topic Name' ),
		'menu_name'         => __( 'Topic' ),
	);
	$topic_args = array(
		'hierarchical'          => true,
		'labels'                => $topic_labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'topic' ),
	);
	register_taxonomy( 'topic', array('page', 'post', 'link', 'sequence', 'map'), $topic_args );

	$region_labels = array(
		'name'              => _x( 'Regions', 'taxonomy general name' ),
		'singular_name'     => _x( 'Region', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Regions' ),
		'all_items'         => __( 'All Regions' ),
		'parent_item'       => __( 'Parent Region' ),
		'parent_item_colon' => __( 'Parent Region:' ),
		'edit_item'         => __( 'Edit Region' ),
		'update_item'       => __( 'Update Region' ),
		'add_new_item'      => __( 'Add New Region' ),
		'new_item_name'     => __( 'New Region Name' ),
		'menu_name'         => __( 'Region' ),
	);
	$region_args = array(
		'hierarchical'          => true,
		'labels'                => $region_labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'region' ),
	);
	register_taxonomy( 'region', array('page', 'post', 'link', 'sequence', 'map'), $region_args );

	$publication_type = array(
		'name'              => _x( 'Publication Types', 'taxonomy general name' ),
		'singular_name'     => _x( 'Publication Type', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Publication Types' ),
		'all_items'         => __( 'All Publication Types' ),
		'parent_item'       => __( 'Parent Publication Type' ),
		'parent_item_colon' => __( 'Parent Publication Type:' ),
		'edit_item'         => __( 'Edit Publication Type' ),
		'update_item'       => __( 'Update Publication Type' ),
		'add_new_item'      => __( 'Add New Publication Type' ),
		'new_item_name'     => __( 'New Publication Type Name' ),
		'menu_name'         => __( 'Publication Type' ),
	);

	$region_args = array(
		'hierarchical'          => true,
		'labels'                => $publication_type,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'pub_type' ),
	);
	register_taxonomy( 'pub_type', array('page', 'post', 'link', 'sequence', 'map'), $region_args );
}
add_action( 'init', 'create_taxonomies', 0 );

function page_map_setting_box() {
    global $post;
    $map_id = get_post_meta( $post->ID, 'map_id', true);
    $args = array(
	    'post_type'=> 'map',
	    'order'    => 'ASC',
            'posts_per_page' => -1
    );
    $maps = new WP_Query( $args );
?>
    <input type="hidden" name="page_map_meta_box_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ) ?>">
    <div id="page_map_setting_box">
        <div class="metabox-tabs-div">
            <div id="genetal-tab" class="genetal-tab">
                <div class="type-title">
                    <h4>Map ID</h4>
                </div>
                <div class="settings">
                <select name="map_id" id="map_id">
                <?php
                    foreach($maps->posts as $map){
                        if ($map->ID == $map_id)
                            echo '<option value="' . $map->ID . '" selected="selected">' . $map->post_title . '</option>';
                        else
                            echo '<option value="' . $map->ID . '">' . $map->post_title . '</option>';
                        }
		?>
                </select>
                </div>
            </div>
        </div>
    </div>
<?php
}
function page_map_setting() {
    add_meta_box (
        'page_map_setting_box',
        'Map Settings Box',
        'page_map_setting_box',
        'page',
        'normal'
    );
}
/* Save data for per story setting */
function save_page_map_settings ( $post_id ) {
    if ( ! array_key_exists( 'page_map_meta_box_nonce', $_POST ) ) {
        $_POST['page_map_meta_box_nonce'] = '';
    }
    // verify nonce
    if ( ! wp_verify_nonce( $_POST['page_map_meta_box_nonce'], basename( __FILE__ ) ) ) {
        return $post_id;
    }
    // check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }
    $map_id = $_POST['map_id'];
    update_post_meta($post_id, 'map_id', $map_id);
    $count_key = 'post_views_count';
    $count = get_post_meta($post_id, $count_key, true);
    if($count == ''){
        $count = 0;
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, 0);
    }
}
add_action( 'admin_init', 'page_map_setting' );
add_action( 'save_post', 'save_page_map_settings' );

function map_marker_filter($query) {
    // by mohjak: 2019-11-21 issue#117
    if (isset($query) && isset($query->posts) && isset($query->posts[0])) {
        $map_id = get_post_meta( $query->posts[0]->ID, 'map_id', true);
        $query = new WP_Query();
        $query->query_vars['map_id'] = $map_id;
        return $query;
    }
}
add_filter( 'jeo_marker_base_query', 'map_marker_filter', 10, 1 );

function subscriber_widgets() {

	register_sidebar( array(
		'name'          => 'Subscriber Widgets',
		'id'            => 'subscriber_widgets',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
	) );

}
add_action( 'widgets_init', 'subscriber_widgets' );

function me_publishing_date( $the_date, $d, $post ) {
	$value = get_post_meta( $post->ID, 'publish_date', true);
	if ( $value == '' ) {
		return $the_date;
	} else {
		$date = DateTime::createFromFormat( 'Y-m-d', $value );
		if ($date == false) {
			return $the_date;
		}
		$ts = $date->format('U');
	}
	if ($d != '') {
		$value = $date->format($d);
	} else {
		$value = strftime("%B %d, %Y", $ts);
	}
	return $value;
}
add_action( 'get_the_date', 'me_publishing_date', 99, 3 );

function external_link( $url, $post, $leavename=false ) {
	$is_external = get_post_meta( $post->ID, 'is_external', true);
    if ($is_external == '1') {
    	return get_post_meta( $post->ID, 'url', true);
    } else {
    	return $url;
    }
}
add_filter( 'post_link', 'external_link', 10, 3 );

function custom_toolbar_link($wp_admin_bar) {
    $args = array(
        'id' => 'dataset-report',
        'title' => 'Dataset Report',
        'href' => '/report-dataset'
    );
    $wp_admin_bar->add_node($args);
}
add_action('admin_bar_menu', 'custom_toolbar_link', 999);

function dataset_report(){
	$content = '<div class="accordion" id="accordionExample">';

	$start = $month = strtotime(date('Y-m-d'));
	$end = strtotime('2018-01-01');
	while($month > $end)
	{
	    $posts = get_posts(array(
			'posts_per_page'	=> -1,
			'post_type'			=> 'post',
			'meta_key'		=> 'dataset_content',
			'meta_value'	=> '1',
			'date_query' => array(
		        array(
		            'after'     => date('Y-m-1', $month),
		            'before'    => date('Y-m-t', $month),
		            'inclusive' => true,
		        ),
		    ),
		));
		$content.='<div class="card"><div class="card-header" id="head-'.date('F-Y', $month).'">';
		$content.='<h2 class="mb-0"><button class="btn btn-link" type="button" data-toggle="collapse" data-target="#col-'.date('F-Y', $month).'" aria-expanded="true" aria-controls="col-">'.date('F Y', $month).'  ('.count($posts).')</button></h2></div>';
		$content.='<div id="col-'.date('F-Y', $month).'" class="collapse" aria-labelledby="head-'.date('F-Y', $month).'" data-parent="#accordionExample"><div class="card-body">';
		if( $posts ) {
			$content.='<ul>';
			foreach( $posts as $post ) {
				$post_link = '<li><a href="'.get_permalink($post).'">'.$post->post_title.'</a></li>';
				$content.=$post_link;
			}
		}
		$content.='</ul></div></div></div>';
		$month = strtotime("-1 month", $month);
	}
	$content .= '</div>';
	return $content;
}
add_shortcode( 'dataset', 'dataset_report' );

function wpb_custom_new_menu() {
  register_nav_menus(
    array(
      'footer-section-1' => __( 'Footer section 1' ),
      'footer-section-2' => __( 'Footer section 2' ),
      'footer-section-3' => __( 'Footer section 3' ),
    )
  );
}
add_action( 'init', 'wpb_custom_new_menu' );
