<?php
// Shortcode Amount Producted Sold
function shortcode_product_sold_auto($atts)
{
    global $product;
    if (!$product) return 'Không xác định sản phẩm';
    $total_sales = $product->get_total_sales();
    $html = '';
    if ($total_sales !== 0) {
        $html = '<p class="pt-5"><i class="fa-solid fa-chart-simple"></i> ' . esc_html($total_sales) . ' items sold' . '</p>';
    }
    return $html;
}
add_shortcode('product_sold_current', 'shortcode_product_sold_auto');

// Shortcode Mã Giảm Giá
add_shortcode('voucher_woo', function () {
    ob_start(); ?>
    <div class="voucher-list">
        <?php foreach (get_posts(['post_type' => 'shop_coupon', 'posts_per_page' => 3, 'orderby' => 'date', 'order' => 'DESC']) as $c) { ?>
            <div class="coupon_item">
                <div class="coupon_icon position-relative d-block overflow-hidden">
                    <img class="img-fluid" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/voucher.svg" width="50" height="40">
                </div>
                <div class="coupon_body">
                    <div class="coupon_head">
                        <div class="coupon_title">NHẬP MÃ: <?php echo $c->post_title; ?></div>
                        <div class="coupon_desc">
                            <?php echo get_post_field('post_excerpt', $c->ID) ?: 'Số lượng mã có hạn.'; ?>
                        </div>
                    </div>
                    <button class="btn btn-main btn-sm coupon_copy" data-ega-coupon="<?php echo $c->post_title; ?>">Sao chép</button>
                </div>
            </div>
        <?php } ?>
    </div>
<?php return ob_get_clean();
});

// Payment
add_filter('woocommerce_checkout_fields', 'custom_remove_checkout_fields');

function custom_remove_checkout_fields($fields)
{
    // Xóa các trường không cần thiết ở billing
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_country']);

    // Xóa các trường shipping nếu cần
    unset($fields['shipping']['shipping_company']);
    unset($fields['shipping']['shipping_address_2']);

    return $fields;
}


