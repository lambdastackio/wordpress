<?php
/* 
 * Changes by Ted:
 * - added carousel type, with animated content
 * - removed link on image
 * - added css_class for custom css class for shortcode
 */

function slickc_shortcode($attributes, $content = null) {
	// Set default shortcode attributes
	$options = get_option('slickc_settings');
	if (!$options) {
		slickc_set_options();
		$options = get_option('slickc_settings');
	}
	$options['id'] = '';

	// add new attribute by Ted
	$options['type'] = ''; // determine if it should be animated or not, default: not
	$options['css_class'] = ''; // custom css class

	// Parse incomming $attributes into an array and merge it with $defaults
	$attributes = shortcode_atts($options, $attributes);
	if($attributes['type'] == 'animated') {
		add_action( 'wp_enqueue_scripts', 'tdv_embed_css' );
		do_action('wp_enqueue_scripts');
	}

	return slickc_frontend($attributes);
}

function tdv_embed_css() {
	$terms = get_terms('carousel_category');
	$img_bg = '';

	foreach ($terms as $t) {
		if($t->description != '') {
			$img_bg = $t->description;
		}
	}

	$hero_animated_css = "<style type='text/css'>.hero-unit-animated { background-image: url('" . $img_bg . "'); }</style>";
	echo $hero_animated_css;
}

function slickc_write_options($attributes) {
	$options = array();
	foreach ($attributes as $key => $value) {
		switch ($key) {
			/* int */
			case 'autoplaySpeed':
			case 'initialSlide':
			case 'slidesToShow':
			case 'slidesToScroll':
			case 'speed':
			case 'touchThreshold':
			case 'asNavFor':
			case 'centerPadding':
			case 'cssEase':
			case 'easing':
			case 'lazyLoad':
			case 'respondTo':
			case 'appendArrows':
			case 'prevArrow':
			case 'nextArrow':
			case 'id':
			case 'orderby':
			case 'order':
				if (!empty($value)) {
					$options[$key] = $value;
				}
				break;
			/* boolean */
			default:
				$options[$key] = ('true' === $value) ? true : false;
				break;
		}
	}
	return json_encode($options);
}

function slickc_load_slick_dependencies() {
	wp_enqueue_script(
	   'slick-carousel-script',
		plugins_url('deps/slick/slick/slick.min.js', __FILE__)
	);
}

function slickc_load_images($attributes) {
	$args = array(
		'post_type' => 'slickc',
		'posts_per_page' => '-1',
		'orderby' => $attributes['orderby'],
		'order' => $attributes['order']
	);
	if (!empty($attributes['category'])) {
		$args['carousel_category'] = $attributes['category'];
	}

	if (!empty($attributes['id'])) {
		$args['p'] = $attributes['id'];
	}

	$loop = new WP_Query($args);
	$images = array();
	$output = '';
	while ($loop->have_posts()) {
		$loop->the_post();
		$image = get_the_post_thumbnail(get_the_ID(), 'full');
		if (!empty($image)) {
			$post_id = get_the_ID();
			$title = get_the_title();
			$content = get_the_excerpt();;
			$image_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
			$image_src = $image_src[0];
			$url = get_post_meta(get_the_ID(), 'slickc_image_url');
			$url_openblank = get_post_meta(get_the_ID(), 'slickc_image_url_openblank');
			$images[] = array(
				'post_id' => $post_id,
				'title' => $title,
				'content' => $content,
				'image' => $image,
				'img_src' => $image_src,
				'url' => esc_url($url[0]),
				'open_blank' => $url_openblank[0],
			);
		}
	}
	return $images;
}

// Display carousel
function slickc_frontend($attributes) {
	$images = slickc_load_images($attributes);
	if (0 === count($images)) {
		return '';
	}
	$id = rand(0, 99999);
	slickc_load_slick_dependencies();
	ob_start();
	?>

	<?php
		$type = $attributes['type']; 
		$css_class = $attributes['css_class']; 
	?>
	<?php if( $type == '' ) : ?>
	<section class="hero-unit-slider <?php echo $css_class; ?>">
		<div class="slick-carousel">
			<div id="slickc_<?php echo $id ?>">
				<?php foreach ($images as $key => $image) : ?>
					<div class="item">
						<img src="<?php echo $image['img_src'] ?>" title="<?php echo esc_html($image['title']) ?>" />
						<div class="carousel-caption">
							<h2 class="hero-heading"><?php echo $image['title']; ?></h2>
							<p class="lead"><?php echo $image['content']; ?></p>
							<a href="<?php echo $image['url']; ?>" class="btn btn-lg hero-button">LEARN MORE</a>
						</div>
					</div>
				<?php endforeach ?>
			</div>
		</div>
	</section>
	<script type="text/javascript">
		var slickc_<?php echo $id ?>_options = JSON.parse(
			'<?php echo slickc_write_options($attributes) ?>'
		);
		jQuery(document).ready(function() {
			jQuery('#slickc_<?php echo $id ?>').slick(slickc_<?php echo $id ?>_options)
		})
	</script>
	<?php elseif( $type == 'animated' ) : ?>
		<section class="hero-unit-animated <?php echo $css_class; ?>">
			<div id="carousel_<?php echo $id ?>" class="carousel">
				<!-- slide wrapper -->
				<div class="carousel-inner" role="listbox">
					<?php 
						$active = 'active';
						$pagination_li_html = '';
						$count = 0;
						foreach ($images as $key => $image) : 
					?>
					<div class="item <?php echo $active; ?>">
						<div class="container">
							<div class="hero-left pull-left">
								<div class="hero-text">
									<h2 class="hero-heading animation-delay-5" data-animation="animated fadeIn"><?php echo $image['title']; ?></h2>
									<p class="lead animation-delay-7" data-animation="animated fadeIn"><?php echo $image['content']; ?></p>
								</div>
								<a href="<?php echo $image['url']; ?>" class="btn btn-info btn-lg hero-button animation-delay-12" data-animation="animated fadeIn">LEARN MORE</a>
							</div>
							<div class="hero-right pull-right">
								<img src="<?php echo $image['img_src'] ?>" class="animation-delay-9" data-animation="animated fadeInRight" alt="<?php echo esc_html($image['title']) ?>">
							</div>
						</div>
					</div>
					<?php
						$pagination_li_html .= '<li data-target="#carousel_' . $id . '" data-slide-to="' . $count . '" class="' . $active . '"></li>';
						$count++;
						$active = '';

						endforeach 
					?>
				</div>
				<!-- end slide wrapper -->

				<!-- controls -->
				<a class="left carousel-control" href="#carousel_<?php echo $id ?>" role="button" data-slide="prev">
					<span class="fa fa-angle-left" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				</a>
				<a class="right carousel-control" href="#carousel_<?php echo $id ?>" role="button" data-slide="next">
					<span class="fa fa-angle-right" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				</a>
				<!-- end controls -->

				<!-- Pagination -->
				<ol class="carousel-indicators">
					<?php echo $pagination_li_html; ?>
				</ol>
				<!-- End Pagination -->

			</div>
		</section>
		<script type="text/javascript">
			<?php 
				$options = get_option('slickc_settings'); 

				// use some of slick settings to be applied to Bootstrap carousel settings
				if( $options ) {
					$is_autoplay = $options['autoplay'];
					$autoplay_speed = $options['autoplaySpeed'];
					$pause_on_hover = $options['pauseOnHover'];
				}
			?>
			jQuery(document).ready(function() {
				var animatedCarousel = jQuery('#carousel_<?php echo $id ?>');
				animatedCarousel.carousel({
					interval: <?php echo ($is_autoplay) ? $autoplay_speed : "false"; ?>,
					pause: <?php echo ($pause_on_hover) ? '"hover"' : ""; ?>
				});

				function doAnimations(elems) {
					var animEndEv = 'webkitAnimationEnd animationend';

					elems.each( function() {
						var thisElem = jQuery(this),
							animationType = thisElem.data('animation');

						thisElem.addClass(animationType).one(animEndEv, function() {
							thisElem.removeClass(animationType);
						});
					});
				}

				var firstAnimatingElems = animatedCarousel.find('.item:first')
											.find('[data-animation ^= "animated"]');
				
				doAnimations(firstAnimatingElems);

				animatedCarousel.on( 'slide.bs.carousel', function(e) {
					var animatingElems = jQuery(e.relatedTarget).find('[data-animation ^= "animated"]');
					doAnimations(animatingElems);
				});

				// for skipped slide, before animation has ended
				jQuery('.carousel-control, .carousel-indicators li').on('click', function() {
					var animatedItems = animatedCarousel.find('.item')
										.find('[data-animation ^= "animated"]');
					animatedItems.each( function() {
						var animationType = jQuery(this).data('animation');
						jQuery(this).removeClass(animationType);
					})
				});

				// adjust slide min-height
				var items = animatedCarousel.find('.item');
				var itemMaxHeight = 0;

				items.each( function() {
					if( itemMaxHeight < jQuery(this).height() )
						itemMaxHeight = jQuery(this).height();
				})

				items.css( 'min-height', itemMaxHeight );
			})
		</script>
	<?php endif; ?>

<?php
	$output = ob_get_contents();
	ob_end_clean();
	wp_reset_postdata();  
	return $output;
}

add_shortcode('slick-carousel', 'slickc_shortcode');


?>