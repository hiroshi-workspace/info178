<?php

// Flatsome Products
function ux_products($atts, $content = null, $tag = '')
{
	$sliderrandomid = rand();

	if (! is_array($atts)) {
		$atts = array();
	}
	$defined_atts = $atts;

	extract($atts = shortcode_atts(array(
		'_id' => 'product-grid-' . rand(),
		'title' => '',
		'ids' => '',
		'style' => 'default',
		'class' => '',
		'visibility' => '',

		// Ooptions
		'back_image' => true,

		// Layout
		'columns' => '4',
		'columns__sm' => '',
		'columns__md' => '',
		'col_spacing' => 'small',
		'type' => 'slider', // slider, row, masonery, grid
		'width' => '',
		'grid' => '1',
		'grid_height' => '600px',
		'grid_height__md' => '500px',
		'grid_height__sm' => '400px',
		'slider_nav_style' => 'reveal',
		'slider_nav_position' => '',
		'slider_nav_color' => '',
		'slider_bullets' => 'false',
		'slider_arrows' => 'true',
		'auto_slide' => '',
		'infinitive' => 'true',
		'depth' => '',
		'depth_hover' => '',
		'equalize_box' => 'false',
		// Relay
		'relay' => '',
		'relay_control_result_count' => 'true',
		'relay_control_position' => 'bottom',
		'relay_control_align' => 'center',
		'relay_id' => '',
		'relay_class' => '',
		// posts
		'products' => '8',
		'cat' => '',
		'excerpt' => 'visible',
		'offset' => '',
		'filter' => '',
		// Posts Woo
		'orderby' => '', // normal, sales, rand, date
		'order' => '',
		'tags' => '',
		'show' => '', //featured, onsale
		'out_of_stock' => '', // exclude.
		'page_number' => '1',
		// Box styles
		'animate' => '',
		'text_pos' => 'bottom',
		'text_padding' => '',
		'text_bg' => '',
		'text_color' => '',
		'text_hover' => '',
		'text_align' => 'center',
		'text_size' => '',
		'image_size' => '',
		'image_radius' => '',
		'image_width' => '',
		'image_height' => '',
		'image_hover' => '',
		'image_hover_alt' => '',
		'image_overlay' => '',
		'show_cat' => 'true',
		'show_title' => 'true',
		'show_rating' => 'true',
		'show_price' => 'true',
		'show_add_to_cart' => 'true',
		'show_quick_view' => 'true',

	), $atts));

	// Stop if visibility is hidden
	if ($visibility == 'hidden') return;

	$items                             = flatsome_ux_product_box_items();
	$items['cat']['show']              = $show_cat;
	$items['title']['show']            = $show_title;
	$items['rating']['show']           = $show_rating;
	$items['price']['show']            = $show_price;
	$items['add_to_cart']['show']      = $show_add_to_cart;
	$items['add_to_cart_icon']['show'] = $show_add_to_cart;
	$items['quick_view']['show']       = $show_quick_view;
	$items                             = flatsome_box_item_toggle_start($items);

	ob_start();

	// if no style is set
	if (!$style) $style = 'default';

	$classes_box = array('box');
	$classes_image = array();
	$classes_text = array();
	$classes_repeater = array($class);

	if ($equalize_box === 'true') {
		$classes_repeater[] = 'equalize-box';
	}

	// Fix product on small screens
	if ($style == 'overlay' || $style == 'shade') {
		if (!$columns__sm) $columns__sm = 1;
	}

	if ($tag == 'ux_bestseller_products') {
		if (!$orderby) $atts['orderby'] = 'sales';
	} else if ($tag == 'ux_featured_products') {
		$atts['show'] = 'featured';
	} else if ($tag == 'ux_sale_products') {
		$atts['show'] = 'onsale';
	} else if ($tag == 'products_pinterest_style') {
		$type = 'masonry';
		$style = 'overlay';
		$text_align = 'center';
		$image_size = 'medium';
		$text_pos = 'middle';
		$text_hover = 'hover-slide';
		$image_hover = 'overlay-add';
		$class = 'featured-product';
		$back_image = false;
		$image_hover_alt = 'image-zoom-long';
	} else if ($tag == 'product_lookbook') {
		$type = 'slider';
		$style = 'overlay';
		$col_spacing = 'collapse';
		$text_align = 'center';
		$image_size = 'medium';
		$slider_nav_style = 'circle';
		$text_pos = 'middle';
		$text_hover = 'hover-slide';
		$image_hover = 'overlay-add';
		$image_hover_alt = 'zoom-long';
		$class = 'featured-product';
		$back_image = false;
	}

	// Fix grids
	if ($type == 'grid') {
		if (!$text_pos) $text_pos = 'center';
		if (!$text_color) $text_color = 'dark';
		if ($style !== 'shade') $style = 'overlay';
		$columns = 0;
		$current_grid = 0;
		$grid = flatsome_get_grid($grid);
		$grid_total = count($grid);
	}

	// Fix image size
	if (!$image_size) $image_size = 'woocommerce_thumbnail';

	// Add Animations
	if ($animate) {
		$animate = 'data-animate="' . esc_attr($animate) . '"';
	}


	// Set box style
	if ($class) $classes_box[] = $class;
	$classes_box[] = 'has-hover';
	if ($style) $classes_box[] = 'box-' . $style;
	if ($style == 'overlay') $classes_box[] = 'dark';
	if ($style == 'shade') $classes_box[] = 'dark';
	if ($style == 'badge') $classes_box[] = 'hover-dark';
	if ($text_pos) $classes_box[] = 'box-text-' . $text_pos;
	if ($style == 'overlay' && !$image_overlay) $image_overlay = true;

	if ($image_hover) $classes_image[] = 'image-' . $image_hover;
	if ($image_hover_alt)  $classes_image[] = 'image-' . $image_hover_alt;
	if ($image_height)  $classes_image[] = 'image-cover';

	// Text classes
	if ($text_hover) $classes_text[] = 'show-on-hover hover-' . $text_hover;
	if ($text_align) $classes_text[] = 'text-' . $text_align;
	if ($text_size) $classes_text[] = 'is-' . $text_size;
	if ($text_color == 'dark') $classes_text[] = 'dark';

	$css_args_img = array(
		array('attribute' => 'border-radius', 'value' => $image_radius, 'unit' => '%'),
		array('attribute' => 'width', 'value' => $image_width, 'unit' => '%'),
	);

	$css_image_height = array(
		array('attribute' => 'padding-top', 'value' => $image_height),
	);

	$css_args = array(
		array('attribute' => 'background-color', 'value' => $text_bg),
		array('attribute' => 'padding', 'value' => $text_padding),
	);

	// If default style
	if ($style == 'default') {
		$depth = get_theme_mod('category_shadow');
		$depth_hover = get_theme_mod('category_shadow_hover');
	}

	// Repeater styles
	$repeater['id'] = $_id;
	$repeater['title'] = $title;
	$repeater['tag'] = $tag;
	$repeater['class'] = implode(' ', $classes_repeater);
	$repeater['visibility'] = $visibility;
	$repeater['type'] = $type;
	$repeater['style'] = $style;
	$repeater['slider_style'] = $slider_nav_style;
	$repeater['slider_nav_color'] = $slider_nav_color;
	$repeater['slider_nav_position'] = $slider_nav_position;
	$repeater['slider_bullets'] = $slider_bullets;
	$repeater['auto_slide'] = $auto_slide;
	$repeater['infinitive'] = $infinitive;
	$repeater['row_spacing'] = $col_spacing;
	$repeater['row_width'] = $width;
	$repeater['columns'] = $columns;
	$repeater['columns__md'] = $columns__md;
	$repeater['columns__sm'] = $columns__sm;
	$repeater['filter'] = $filter;
	$repeater['depth'] = $depth;
	$repeater['depth_hover'] = $depth_hover;

?>
	<?php

	if (empty($ids)) {

		// Get products
		$atts['products'] = $products;
		$atts['offset'] = $offset;
		$atts['page_number'] = $page_number;
		$atts['cat'] = $cat;

		$products = ux_list_products($atts);
	} else {
		// Get custom ids
		$ids = explode(',', $ids);
		$ids = array_map('trim', $ids);

		$args = array(
			'post__in' => $ids,
			'post_type' => 'product',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'orderby' => 'post__in',
			'ignore_sticky_posts' => true,
		);

		$products = new WP_Query($args);
	}

	Flatsome_Relay::render_container_open($products, $tag, $defined_atts, $atts);

	if ($type == 'grid') {
		flatsome_get_grid_height($grid_height, $_id);
	}

	get_flatsome_repeater_start($repeater);

	if ($products->have_posts()) : ?>

		<?php while ($products->have_posts()) : $products->the_post();
			global $product;

			if ($style == 'default') {
				wc_get_template_part('content', 'product');
			} else {

				// Check if the product is a valid WooCommerce product and ensure its visibility before proceeding.
				if (! is_a($product, WC_Product::class) || ! $product->is_visible()) {
					continue;
				}

				$classes_col = array('col');

				$out_of_stock = ! $product->is_in_stock();
				if ($out_of_stock) $classes[] = 'out-of-stock';

				if ($type == 'grid') {
					if ($grid_total > $current_grid) $current_grid++;
					$current = $current_grid - 1;
					$classes_col[] = 'grid-col';
					if ($grid[$current]['height']) $classes_col[] = 'grid-col-' . $grid[$current]['height'];

					if ($grid[$current]['span']) $classes_col[] = 'large-' . $grid[$current]['span'];
					if ($grid[$current]['md']) $classes_col[] = 'medium-' . $grid[$current]['md'];
					// Set image size
					if ($grid[$current]['size']) $image_size = $grid[$current]['size'];
				}
		?><div class="<?php echo esc_attr(implode(' ', $classes_col)); ?>" <?php echo $animate; ?>>
					<div class="col-inner">
						<?php woocommerce_show_product_loop_sale_flash(); ?>
						<div class="product-small <?php echo esc_attr(implode(' ', $classes_box)); ?>">
							<div class="box-image" <?php echo get_shortcode_inline_css($css_args_img); ?>>
								<div class="<?php echo esc_attr(implode(' ', $classes_image)); ?>" <?php echo get_shortcode_inline_css($css_image_height); ?>>
									<a href="<?php echo esc_url(get_the_permalink()); ?>" aria-label="<?php echo esc_attr($product->get_title()); ?>">
										<?php
										if ($back_image) flatsome_woocommerce_get_alt_product_thumbnail($image_size);
										echo woocommerce_get_product_thumbnail($image_size);
										?>
									</a>
									<?php if ($image_overlay) { ?><div class="overlay fill" style="background-color: <?php echo esc_attr($image_overlay); ?>"></div><?php } ?>
									<?php if ($style == 'shade') { ?><div class="shade"></div><?php } ?>
								</div>
								<div class="image-tools top right show-on-hover">
									<?php do_action('flatsome_product_box_tools_top'); ?>
								</div>
								<?php if ($style !== 'shade' && $style !== 'overlay') { ?>
									<div class="image-tools not_yet <?php echo flatsome_product_box_actions_class(); ?>">
										<?php do_action('flatsome_product_box_actions'); ?>
									</div>
								<?php } ?>
								<?php if ($out_of_stock) { ?><div class="out-of-stock-label"><?php _e('Out of stock', 'woocommerce'); ?></div><?php } ?>
							</div>

							<div class="box-text <?php echo esc_attr(implode(' ', $classes_text)); ?>" <?php echo get_shortcode_inline_css($css_args); ?>>
								<?php
								do_action('woocommerce_before_shop_loop_item_title');

								echo '<div class="title-wrapper">';
								do_action('woocommerce_shop_loop_item_title');
								echo '</div>';

								echo '<div class="price-wrapper">';
								do_action('woocommerce_after_shop_loop_item_title');
								echo '</div>';
								echo do_shortcode('[product_sold_current]');
								if ($style == 'shade' || $style == 'overlay') {
									echo '<div class="overlay-tools">';
									do_action('flatsome_product_box_actions');
									echo '</div>';
								}

								do_action('flatsome_product_box_after');

								?>
							</div>
						</div>
					</div>
				</div><?php }
				endwhile; // end of the loop.
			endif;
			wp_reset_query();

			get_flatsome_repeater_end($repeater);

			Flatsome_Relay::render_container_close();

			flatsome_box_item_toggle_end($items);

			$content = ob_get_contents();
			ob_end_clean();

			return $content;
		}
		add_shortcode("ux_bestseller_products", "ux_products");
		add_shortcode("ux_featured_products", "ux_products");
		add_shortcode("ux_sale_products", "ux_products");
		add_shortcode("ux_latest_products", "ux_products");
		add_shortcode("ux_custom_products", "ux_products");
		add_shortcode("product_lookbook", "ux_products");
		add_shortcode("products_pinterest_style", "ux_products");
		add_shortcode("ux_products", "ux_products");
