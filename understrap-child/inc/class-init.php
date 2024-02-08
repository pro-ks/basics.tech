<?php
// Класс для метаполей объектов недвижимости
class RealEstateCustomFields {
    public function __construct() {
        // Добавляем обработчик для регистрации типов постов и таксономий
        add_action('init', array($this, 'register_post_types'));
        // Добавляем обработчик для добавления блока мета-полей
        add_action('add_meta_boxes', array($this, 'add_custom_fields_to_real_estate'));
        // Добавляем обработчик для сохранения мета-полей
        add_action('save_post', array($this, 'save_real_estate_custom_fields'));
    }

    // Регистрация типов постов и таксономий
    public function register_post_types() {
        register_post_type('real_estate', array(
            'label' => __('Real Estate', 'understrap-child'),
            'public' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
            'taxonomies' => array('property_type'),
        ));

        register_taxonomy('property_type', 'real_estate', array(
            'label' => __('Property Type', 'understrap-child'),
            'hierarchical' => true,
        ));

        register_post_type('city', array(
            'label' => __('City', 'understrap-child'),
            'public' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
        ));
    }

    // Добавление блока мета-полей
    public function add_custom_fields_to_real_estate() {
        $screens = array('real_estate');
        add_meta_box('real_estate_custom_fields', __('Additional Fields', 'understrap-child'), array($this, 'display_real_estate_custom_fields'), $screens);
    }

    // Отображение блока мета-полей
    public function display_real_estate_custom_fields($post) {
        // Выводим nonce-поле для безопасности
        wp_nonce_field('real-estate-nonce', 'real_estate_nonce');
        
        $fields = array(
            '_area' => __('Area:', 'understrap-child'),
            '_price' => __('Price:', 'understrap-child'),
            '_address' => __('Address:', 'understrap-child'),
            '_living_area' => __('Living Area:', 'understrap-child'),
            '_floor' => __('Floor:', 'understrap-child')
        );

        echo '<table class="form-table"><tbody>';
        foreach ($fields as $meta_key => $label) {
            $value = get_post_meta($post->ID, $meta_key, true);
            echo '<tr><th><label for="' . esc_attr($meta_key) . '">' . esc_html($label) . '</label></th>';
            echo '<td><input type="text" id="' . esc_attr($meta_key) . '" name="' . esc_attr($meta_key) . '" value="' . esc_attr($value) . '" size="25" /></td></tr>';
        }


        // Поле для выбора связанного города 
        echo '<tr><th><label for="_associated_city_id">' . __('Associated City:', 'understrap-child') . '</label></th>';
        echo '<td>';

        $args = array(
            'post_type' => 'city',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'post_status' => 'publish',
        );

        $associated_city_id = get_post_meta($post->ID, '_associated_city_id', true);
        $city_posts = get_posts($args);

        echo '<select name="_associated_city_id" id="_associated_city_id">';
        echo '<option value="">' . __('Select a city', 'understrap-child') . '</option>';

        foreach ($city_posts as $city) {
            $selected = ($associated_city_id == $city->ID) ? 'selected="selected"' : '';
            echo '<option value="' . esc_attr($city->ID) . '" ' . $selected . '>' . esc_html($city->post_title) . '</option>';
        }

        echo '</select>';
        echo '</td></tr>';


        echo '</tbody></table>';


    }

    // Сохранение мета-полей
    public function save_real_estate_custom_fields($post_id) {
        // Проверяем nonce при обработке данных формы
        if (!isset($_POST['real_estate_nonce']) || !wp_verify_nonce($_POST['real_estate_nonce'], 'real-estate-nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }


        $fields = array('_area', '_price', '_address', '_living_area', '_floor', '_associated_city_id');
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
            }
        }
    }
}

// Инициализация класса при загрузке WordPress
new RealEstateCustomFields();