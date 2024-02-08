<?php
/**
 * Start init theme
 */
require get_stylesheet_directory() . '/inc/class-init.php';
require get_stylesheet_directory() . '/inc/template-functions.php';
require get_stylesheet_directory() . '/inc/form-real-estate.php';


function add_child_theme_understrap() {
    load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_understrap' );


// Подключение стилей и скриптов
function enqueue_child_theme_styles() {
    // Подключаем стиль дочерней темы
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array(), wp_get_theme()->get('Version'));
}
add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_styles' );

