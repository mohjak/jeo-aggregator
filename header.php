<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<title><?php
	global $page, $paged;

	wp_title( '|', true, 'right' );

	bloginfo( 'name' );

	$site_description = get_bloginfo('description', 'display');
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . __('Page', 'ekuatorial') . max($paged, $page);

	?></title>
<link rel="profile" href="https://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/img/favicon.ico" type="image/x-icon" />
<script src="https://hammerjs.github.io/dist/hammer.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(get_bloginfo('language')); ?>>
    <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=1005100742956475";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
	<header id="masthead">
		<div class="container">
			<div class="twelve columns">
				<?php
				$lang = '';
				if(function_exists('qtranxf_getLanguage'))
					$lang = qtranxf_getLanguage();
				?>
				<a href="<?php echo home_url('/' . $lang); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo-mekong.png" class="logo" /></a>
				<h1><a href="<?php echo home_url('/' . $lang); ?>" title="<?php echo bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
				<p class="slogan"><?php bloginfo('description'); ?></p>
				<?php get_search_form(); ?>
			</div>
		</div>
		<section id="mastnav" class="clearfix">
			<div class="container">
				<div class="eight columns">
					<nav id="nav" role="navigation">
<a href="#nav" title="Show navigation"></a>
<a href="#" title="Hide navigation"></a>
						<ul>
							<?php wp_nav_menu(array('theme_location' => 'header_menu',
								'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s<li><a href="#submit" class="submit-story">' . __('Submit a story', 'ekuatorial') . '</a></li></ul>'
							)); ?>
						</ul>
					</nav>
				</div>
<form role="search" method="get" id="search" action="<?php echo home_url(); ?>/">
    <div>
        <input type="text" name="s" id="s" placeholder="<?php _e('Search here...', 'jeo'); ?>" value="<?php if(isset($_GET['s'])) echo $_GET['s']; ?>" />
    </div>
</form>
				<div class="four columns">
					<?php if(function_exists('qtranxf_getLanguage')) : ?>
						<nav id="langnav">
							<ul>
								<?php
								global $q_config;
								if(is_404()) $url = get_option('home'); else $url = '';
								$current = qtranxf_getLanguage();
								foreach($q_config['enabled_languages'] as $language) {
									$attrs = '';
									if($language == $current)
										$attrs = 'class="active"';
									echo '<li><a href="' . qtranxf_convertURL($url, $language) . '" ' . $attrs . '>' . $language . '</a></li>';
								}
								?>
							</ul>
						</nav>
					<?php endif; ?>
					<nav id="social">
						<ul>
							<li class="twitter">
								<a href="https://twitter.com/MekongEye" rel="external" target="_blank" title="Twitter"></a>
							</li>
							<li class="fb">
								<a href="https://facebook.com/MekongEye" rel="external" target="_blank" title="Facebook"></a>
							</li>
						</ul>
					</nav>
					<nav id="feedback">
						<ul>
							<li>
								<a href="<?php echo ekuatorial_home_url('/mail-us/'); ?>" title="<?php _e('Feedback', 'ekuatorial'); ?>"><?php _e('Contact', 'ekuatorial'); ?></a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</section>
	</header>
	<section id="main-content">
