
$(document).ready(function () {

//	КАСТОМИЗАЦИЯ INPUT FILE

	// повесим события клика на input text и img, чтобы срабатывал элемент file  

	$(".upload-block__button, .upload-block__input-text").click(function(){
	    $(this).parent().children("input[type='file']").trigger("click");
	});	

	// получаем имя загружаемого файла и помещаем в input text

	$(".upload-block__input-file").change(function(){
    	$(this).parent().children("input[type='text']").val($(this).val().split('\\').pop());
	});

});