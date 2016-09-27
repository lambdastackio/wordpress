<?php global $repute_options; ?>
<!DOCTYPE HTML>
<html>
<head>
	<title><?php wp_title(''); ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<!-- FAVICON -->
	<link rel="shortcut icon" href="<?php echo $repute_options['rpt-favicon']['url']; ?>">

	<?php wp_head(); ?>

	<?php 
		// theme options: typography
		$body_font = $repute_options['rpt-font-body'];
		$heading_font = $repute_options['rpt-font-heading'];

		// theme options: colors
		$header_color = $repute_options['rpt-header-color'];
		$header_font_color = $repute_options['rpt-header-font-color'];
		$nav_bg_color_hover = $repute_options['rpt-nav-bg-active-color'];
		$footer_color = $repute_options['rpt-footer-color'];
		$footer_font_color = $repute_options['rpt-footer-font-color'];
		$copyright_color = $repute_options['rpt-copyright-color'];
		$copyright_font_color = $repute_options['rpt-copyright-font-color'];

	?>
	
	<style type="text/css">
		body {
			font-family: <?php echo $body_font['font-family']; ?>;
			font-size: <?php echo $body_font['font-size']; ?>;
			font-weight: <?php echo $body_font['font-weight']; ?>;
			font-style: <?php echo $body_font['font-style']; ?>;;
			color: <?php echo $body_font['color']; ?>;
			
		}
		h1, h2, h3, h4, h5, h6 {
			font-family: <?php echo $heading_font['font-family']; ?>;
			color: <?php echo $heading_font['color']; ?>;
		}
		.navbar {
			background-color: <?php echo $header_color; ?>;
		}
		.navbar-default .navbar-nav > li.current-menu-ancestor > a {
			background-color: <?php echo $nav_bg_color_hover; ?>;
		}
		.navbar-default .navbar-nav > li > a,
		.navbar-default .navbar-nav .dropdown-toggle i,
		.topbar a, .topbar a:hover, .topbar a:focus  {
			color: <?php echo $header_font_color; ?>;
		}
		@media screen and (min-width: 993px) {
			.navbar-default .navbar-nav > a:focus, .navbar-default .navbar-nav > a:hover,
			.navbar-default .navbar-nav > li > a:hover, .navbar-default .navbar-nav > li > a:focus,
			.navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:focus, .navbar-default .navbar-nav > .active > a:hover {
				background-color: <?php echo $nav_bg_color_hover; ?>;
				color: <?php echo $header_font_color; ?>;
			}
		}
		footer {
			background-color: <?php echo $footer_color; ?>;
			color: <?php echo $footer_font_color; ?>;
		}
		.footer-heading, footer a, footer a:hover, footer a:focus {
			color: <?php echo $footer_font_color; ?>;
		}
		.copyright {
			background-color: <?php echo $copyright_color; ?>;
			color: <?php echo $copyright_font_color; ?>;
		}
	</style>

	<?php if( !empty($repute_options['rpt-custom-css']) ) : ?>
		<style type="text/css">
			<?php echo $repute_options['rpt-custom-css']; ?>
		</style>
	<?php endif; ?>

</head>
<body id="rpt-top" <?php ($repute_options['rpt-layout'] == 'layout-boxed') ? body_class('layout-boxed') : body_class(); ?>>
	<!-- WRAPPER -->
	<div class="wrapper">
		<?php
			$navbar_class = '';
			if( $repute_options['rpt-navigation'] == 'nav-fixed' ) { 
				$navbar_class = 'navbar-fixed-top';
			} else if ( $repute_options['rpt-navigation'] == 'nav-fixed-shrink' ) {
				$navbar_class = 'navbar-fixed-top shrinkable';
			}
		?>
		<!-- NAVBAR -->
		<nav class="navbar navbar-default <?php echo $navbar_class; ?>" role="navigation">
			<div class="container">
				<!-- NAVBAR HEADER -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav">
						<span class="sr-only">Toggle Navigation</span>
						<i class="fa fa-bars"></i>
					</button>
					<a href="<?php echo home_url(); ?>" class="navbar-brand navbar-logo">
						<?php if( is_home() || is_front_page() ) : ?>
							<h1 class="sr-only"><?php bloginfo('name'); ?></h1>
						<?php endif; ?>
						<img src="<?php echo $repute_options['rpt-logo']['url']; ?>" alt="<?php bloginfo( 'name' ); ?>">
					</a>
				</div>
				<!-- END NAVBAR HEADER -->

				<?php
					wp_nav_menu( array(
						'theme_location' => 'topnav',
						'depth' => 3,
						'container' => 'div',
						'container_id' => 'main-nav',
						'container_class' => 'collapse navbar-collapse',
						'menu_class' => 'nav navbar-nav navbar-right',
						'walker' => new wp_bootstrap_navwalker())
					);
				?>
			</div>
		</nav>
		<!-- END NAVBAR -->

		<!-- SEARCHBOX -->
		<div class="top-searchbox">
			<div class="container">
				<!-- search box -->
				<div class="searchbox">
					<form class="form-horizontal" role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
						<?php if( $searchresults != ('') ) : ?>
							<input type="text" class="form-control input-lg" placeholder="type keyword and hit enter ..." value="<?php echo $searchresults; ?>" name="s" id="s">
						<?php else: ?>
							<input type="text" class="form-control input-lg" placeholder="type keyword and hit enter ..." name="s" id="s" disabled="disabled">
						<?php endif; ?>
					</form>
				</div>
				<!-- end searchbox -->
			</div>
		</div>
		<!-- END SEARCHBOX -->

		<?php 
			$header_image = get_header_image(); 
			if ( ! empty( $header_image ) ) : 
		?>
			<div class="hero-header-image"><img src="<?php echo esc_url( $header_image ); ?>" alt=""></div>
		<?php endif; ?>



