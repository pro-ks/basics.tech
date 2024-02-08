<?php

// Публичные функции для шаблона

// Функция для отображения пользовательских полей записи
function display_custom_fields($fields) {
    foreach ($fields as $meta_key => $label) {
        $value = get_post_meta(get_the_ID(), $meta_key, true);
        if (!empty($value)) {
            echo '<li><b>' . esc_html($label) . '</b> ' . esc_html($value) . '</li>';
        }
    }
}

// Функция для отображения таксономий записи
function display_taxonomy_terms($post) {
    $taxonomies = get_object_taxonomies($post, 'objects');
    foreach ($taxonomies as $taxonomy) {
        $terms = get_the_terms($post->ID, $taxonomy->name);
        if ($terms && !is_wp_error($terms)) {
            echo '<div class="post-taxonomy">';
            echo '<b>' . esc_html($taxonomy->label) . ':</b> ';

            $term_links = array();
            foreach ($terms as $term) {
                $term_links[] = '<a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
            }

            echo implode(', ', $term_links);
            echo '</div>';
        }
    }
}

// Функция для отображения связанного города записи
function display_associated_city($post) {
    $associated_city_id = (int)get_post_meta($post->ID, '_associated_city_id', true);
    if ($associated_city_id) {
        $city_post = get_post($associated_city_id);
        if ($city_post) {
            echo '<div class="associated-city">';
            echo '<b>' . __('City:', 'understrap-child') . '</b> <a href="' . esc_url(get_permalink($city_post->ID)) . '">' . esc_html($city_post->post_title) . '</a>';
            echo '</div>';
        }
    }
}

// Добавление пользовательских размеров изображений
if ( function_exists( 'add_image_size' ) ) {
    // Кадрирование изображения для главной страницы
    add_image_size( 'homepage-understrap-child', 388, 291, true );
    // Кадрирование изображения для главной страницы города
    add_image_size( 'homepage-city-understrap-child', 172, 245, true );
}

// Функция для установки длины выдержки
add_filter( 'excerpt_length', function(){
    return 15; // Установка длины выдержки в 15 слов
} );
