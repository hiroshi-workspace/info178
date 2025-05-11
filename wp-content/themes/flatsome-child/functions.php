<?php
include get_stylesheet_directory() . '/inc/shortcodes/productsFunctions.php';

function includeJs() {
    wp_enqueue_script(
        'custom-js', // Tên định danh
        get_stylesheet_directory_uri() . '/assets/js/main.js', // Đường dẫn file JS
        array('jquery'), // Phụ thuộc (nếu có)
        null, // Phiên bản (hoặc dùng filemtime() để tạo cache buster)
        true // true để load ở footer, false nếu muốn load ở header
    );
}
add_action('wp_enqueue_scripts', 'includeJs');