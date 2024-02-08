<?php
/**
 * Class RealEstateForm
 *
 * Класс для создания формы добавления недвижимости и обработки связанных с ней действий.
 */
class RealEstateForm {
    /**
     * @var array Массив информации о полях формы.
     */
	//public $form_fields_info; // Новое свойство для хранения информации о полях формы

    /**
     * Конструктор класса RealEstateForm.
     * Инициализирует массив информации о полях формы и регистрирует действия AJAX.
     */
    public function __construct() {
        // Инициализация информации о полях формы
        //$this->form_fields_info = $this->get_form_fields_info();

        // Регистрация шорткода и подключение действий AJAX
        add_shortcode('real_estate_form', array($this, 'real_estate_form_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_add_real_estate_script'));
        add_action('wp_ajax_add_real_estate', array($this, 'add_real_estate'));
        add_action('wp_ajax_nopriv_add_real_estate', array($this, 'add_real_estate'));
        add_action('wp_ajax_get_cities', array($this, 'get_cities'));
        add_action('wp_ajax_nopriv_get_cities', array($this, 'get_cities'));
        add_action('wp_ajax_get_taxonomy_terms', array($this, 'get_taxonomy_terms'));
        add_action('wp_ajax_nopriv_get_taxonomy_terms', array($this, 'get_taxonomy_terms'));
    }

    /**
     * Метод для генерации формы недвижимости через шорткод.
     *
     * @return string Сгенерированная HTML-форма.
     */
    public function real_estate_form_shortcode() {
        ob_start(); // Начинаем буферизацию вывода
        ?>
        <div id="add-real-estate-message" class="container mt-3"></div>
        <form id="add-real-estate-form" class="container mt-5" enctype="multipart/form-data">
		    <div id="spinner" class="spinner-border text-primary" role="status" style="display: none;">
		        <span class="visually-hidden"><?= __('Loading...', 'understrap-child'); ?></span>
		    </div>
            <?php $this->output_form_fields(); ?>
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary"><?= __('Add Real Estate', 'understrap-child'); ?></button>
                </div>
            </div>
        </form>
        <?php
        return ob_get_clean(); // Возвращает содержимое буфера вывода
    }

    /**
     * Метод для получения информации о полях формы.
     *
     * @return array Массив информации о полях формы.
     */
    public function get_form_fields_info() {
        return array(
	        'area' => array(
	            'meta_key' => '_area',
	            'type' => 'number',
	            'name' =>__('Area:', 'understrap-child'),
	        ),
	        'price' => array(
	            'meta_key' => '_price',
	            'type' => 'number',
	            'name' => __('Price:', 'understrap-child'),
	        ),
	        'address' => array(
	            'meta_key' => '_address',
	            'type' => 'text',
	            'name' => __('Address:', 'understrap-child'),
	        ),
	        'living_area' => array(
	            'meta_key' => '_living_area',
	            'type' => 'number',
	            'name' => __('Living Area:', 'understrap-child'),
	        ),
	        'floor' => array(
	            'meta_key' => '_floor',
	            'type' => 'number',
	            'name' => __('Floor:', 'understrap-child'),
	        ),
	        'associated_city_id' => array(
	            'meta_key' => '_associated_city_id',
	            'type' => 'number',
	            'name' => __('Associated City:', 'understrap-child'),
	        ),
	        'property_type' => array(
	            'meta_key' => '_associated_city_id',
	            'type' => 'number',
	            'name' => __('Property Type:', 'understrap-child'),
	        ),
	        'image' => array(
	            'meta_key' => '_associated_city_id',
	            'type' => 'image',
	            'name' => __('Image:', 'understrap-child'),
	        ),
	        'content' => array(
	            'meta_key' => 'content',
	            'type' => 'text',
	            'name' => __('Description:', 'understrap-child')
	        )            
	    );
    }

    /**
     * Метод для вывода полей формы.
     */
    public function output_form_fields() {
        $fields = $this->get_form_fields_info();

        echo '<div class="row mb-3">';
        foreach ($fields as $field_name => $field_info) {

            echo '<div class="col-md-6">';
            printf('<label for="%s" class="form-label">%s</label>', $field_name, $field_info['name']);

            // Вывод различных типов полей в зависимости от их названия
            if ($field_name === 'image') {
                printf('<input type="file" id="%s" name="%s" accept="image/png, image/jpeg, image/*" class="form-control" required>', $field_name, $field_name);
            } elseif ($field_name === 'property_type' || $field_name === 'associated_city_id') {
                printf('<select id="%s" name="%s" class="form-select custom-select" ></select>', $field_name, $field_name);
            } elseif ($field_name === 'content') {
                printf('<textarea rows="10" cols="45" id="%s" name="%s" class="form-control" required></textarea>', $field_name, $field_name);
            } else {
                printf('<input type="%s" id="%s" name="%s" class="form-control" required>', $field_info['type'], $field_name,  $field_name);
            }

            echo '</div>';
        }
        echo '</div>';
    }

    /**
     * Метод для подключения скриптов и стилей.
     */
    public function enqueue_add_real_estate_script() {
        wp_enqueue_script('add-real-estate-script', get_stylesheet_directory_uri() . '/js/add-real-estate.js', array('jquery'), '1.1', true);
        wp_localize_script('add-real-estate-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    }

    /**
     * Метод для получения списка городов через AJAX.
     */
    public function get_cities() {
        $args = array(
            'post_type' => 'city',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'post_status' => 'publish',
        );

        $city_posts = get_posts($args);

        $options = '<option value="">'.__('Select a city', 'understrap-child').'</option>';
        foreach ($city_posts as $city) {
            $options .= '<option value="' . $city->ID . '">' . $city->post_title . '</option>';
        }

        echo $options;

        wp_die();
    }

    /**
     * Метод для получения списка категорий через AJAX.
     */
    public function get_taxonomy_terms() {
        $taxonomy = $_GET['taxonomy'];

        $terms = get_terms(array(
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ));

        $options = '<option value="">'.__('Select a category', 'understrap-child').'</option>';
        foreach ($terms as $term) {
            $options .= '<option value="' . $term->term_id . '">' . $term->name . '</option>';
        }

        echo $options;
        wp_die();
    }

    /**
     * Метод для обработки изображений.
     *
     * @param int $real_estate_id ID недвижимости.
     */
	public function process_image($real_estate_id) {
	    if (!empty($_FILES['image']['name'])) {
	        // Проверяем размер файла
	        $max_file_size = 10 * 1024 * 1024; // 10 MB
	        if ($_FILES['image']['size'] > $max_file_size) {
	            wp_send_json_error(__('File size exceeds the maximum allowed limit.', 'understrap-child'));
	        }

	        // Проверяем тип файла
	        $file_info = wp_check_filetype($_FILES['image']['name']);
	        $allowed_mime_types = array('image/jpeg', 'image/png', 'image/gif');
	        if (!$file_info || !in_array($file_info['type'], $allowed_mime_types)) {
	            wp_send_json_error(__('Only JPEG, PNG, and GIF images are allowed.', 'understrap-child'));
	        }

	        $uploaded_image = wp_handle_upload($_FILES['image'], array('test_form' => false));

	        if (!empty($uploaded_image['url'])) {
	            // Проверяем, является ли загруженный файл изображением
	            $image_info = getimagesize($uploaded_image['file']);
	            if ($image_info === false) {
	                wp_send_json_error(__('The uploaded file is not an image.', 'understrap-child'));
	            }

	            $attachment = array(
	                'post_mime_type' => $image_info['mime'],
	                'post_title' => basename($uploaded_image['file']),
	                'post_content' => '',
	                'post_status' => 'inherit'
	            );

	            $image_id = wp_insert_attachment($attachment, $uploaded_image['file'], $real_estate_id);

	            if (!is_wp_error($image_id)) {
	                require_once(ABSPATH . 'wp-admin/includes/image.php');
	                $image_data = wp_generate_attachment_metadata($image_id, $uploaded_image['file']);
	                wp_update_attachment_metadata($image_id, $image_data);

	                set_post_thumbnail($real_estate_id, $image_id);
	            } else {
	                wp_send_json_error(__('Failed to create attachment.', 'understrap-child'));
	            }
	        } else {
	            wp_send_json_error(__('Failed to upload image.', 'understrap-child'));
	        }
	    } else {
	        wp_send_json_error(__('No image uploaded.', 'understrap-child'));
	    }
	}

    /**
     * Метод для добавления недвижимости.
     * Проверяет обязательные поля, создает новую запись о недвижимости и сохраняет данные в базе.
     */
    public function add_real_estate() {
	    $fields = $this->get_form_fields_info();

	    // Проверка наличия и валидности всех обязательных полей
	    foreach ($fields as $field_name => $field_info) {
		    if ($field_name === 'image') {
		        continue; // Пропускаем итерацию цикла для этих полей
		    }

	        $meta_key = $field_info['meta_key'];
	        $field_type = $field_info['type'];

	        // Проверка заполнения других полей
	        if (!isset($_POST[$field_name]) || empty($_POST[$field_name])) {
	            wp_send_json_error(__('All fields are required.', 'understrap-child'));
	        }

	        // Если поле должно быть числовым
	        if ($field_type === 'number') {
	            if (!is_numeric($_POST[$field_name])) {
	                wp_send_json_error(sprintf(__('Invalid data type for "%s". Numeric value expected.', 'understrap-child'), $field_info['name']));

	            }
	        }
		    
	    }

        if (!isset($_FILES['image']) || empty($_FILES['image'])) {
            wp_send_json_error(__('The image field is required.', 'understrap-child'));
        }

        $city_id = intval($_POST['associated_city_id']);
        $city_post = get_post($city_id);
        if (!$city_post || $city_post->post_type !== 'city') {
            wp_send_json_error( __('Invalid city ID.', 'understrap-child') );
        }

        $property_type_id = intval($_POST['property_type']);
        $term = get_term($property_type_id, 'property_type');
        if (!$term || is_wp_error($term)) {
            wp_send_json_error( __('Invalid property type ID.', 'understrap-child') );
        }

        // Получение названия категории "Property Type" и названия города
        $property_type = get_term($property_type_id, 'property_type');
        $property_type_name = $property_type ? $property_type->name : '';
        
        
        $city_post = get_post($city_id);
        $city_name = $city_post ? $city_post->post_title : '';

        // Формирование заголовка поста
        $post_title = "$property_type_name " . sanitize_text_field( esc_html($_POST['area']) ) . "м² - $city_name";

        $real_estate_id = wp_insert_post(array(
            'post_type' => 'real_estate',
            'post_title' => $post_title,
            'post_status' => 'publish',
		    'post_content' => wp_kses_post($_POST['content']),
		    'meta_input' => array(
		        '_area' => intval($_POST['area']),
		        '_price' => intval($_POST['price']),
		        '_address' => sanitize_text_field( esc_html($_POST['address']) ),
		        '_living_area' => intval($_POST['living_area']),
		        '_floor' => intval($_POST['floor']),
		        '_associated_city_id' => intval($_POST['associated_city_id'])
		    )
        ));

        if (!$real_estate_id) {
            wp_send_json_error( __('Failed to add real estate!', 'understrap-child') );
        }

        // Установка категории "Property Type"
        if (isset($_POST['property_type'])) {
            $property_type_id = intval($_POST['property_type']);
            wp_set_object_terms($real_estate_id, $property_type_id, 'property_type');
        }

        // Обработка изображения
        $this->process_image($real_estate_id);

        wp_send_json_success( __('The property has been added successfully refresh the page!', 'understrap-child') );
    }

}

$real_estate = new RealEstateForm();