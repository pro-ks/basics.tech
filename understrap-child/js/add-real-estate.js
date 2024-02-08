jQuery(document).ready(function($) {
    // Заполнение опций выбора городов
    $.ajax({
        type: 'GET',
        url: ajax_object.ajax_url, // Путь к обработчику AJAX на сервере
        data: {
            action: 'get_cities'
        },
        success: function(response) {
            $('#associated_city_id').html(response);
        }
    });

    // Заполнение опций выбора категорий таксономии
    $.ajax({
        type: 'GET',
        url: ajax_object.ajax_url, // Путь к обработчику AJAX на сервере
        data: {
            action: 'get_taxonomy_terms',
            taxonomy: 'property_type' // Название вашей таксономии
        },
        success: function(response) {
            $('#property_type').html(response);
        }
    });


	// Обработка отправки формы через AJAX
	$('#add-real-estate-form').submit(function(event) {
	    event.preventDefault();

	    // Показать спиннер
	    $('#spinner').show();
	    // Сделать форму неактивной и добавить класс disabled
    	$('#add-real-estate-form').addClass('disabled');

	    var formData = new FormData($(this)[0]);
	    console.log(formData);
	    // Добавляем параметр "action"
	    formData.append('action', 'add_real_estate');
	    console.log(formData);
	    

	    $.ajax({
	        type: 'POST',
	        url: ajax_object.ajax_url, // Путь к обработчику AJAX на сервере
	        data: formData,
	        processData: false,
	        contentType: false,
	        success: function(response) {
	            if (response.success) {
	                // Очистка всех полей формы
	                $('#add-real-estate-form')[0].reset();
	                
	                // Отображение сообщения об успешной отправке
	                $('#add-real-estate-message').html('<div class="alert alert-success" role="alert">' + response.data + '</div>');
	            } else {
	                $('#add-real-estate-message').html('<div class="alert alert-danger">' + response.data + '</div>');
	            }
	        },
	        error: function(xhr, status, error) {
	        	console.log('error' + error);
	            var errorMessage = xhr.responseText; // Получаем текст ошибки
	            $('#add-real-estate-message').html('<div class="alert alert-danger">' + errorMessage + '</div>');
	        },
	        complete: function() {
	            // Скрыть спиннер после получения ответа
	            $('#spinner').hide();

	            // Сделать форму активной снова и удалить класс disabled
	            $('#add-real-estate-form').removeClass('disabled');
	        }
	    });
	});

});
