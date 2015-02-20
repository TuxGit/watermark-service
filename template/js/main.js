jQuery(document).ready(function(){
   	
//	КАСТОМИЗАЦИЯ INPUT FILE

	// повесим события клика на input text и img, чтобы срабатывал элемент file  

	$(".upload-block__button, .upload-block__input-text").click(function(){
	    $(this).parent().children("input[type='file']").trigger("click");
	});	

	// получаем имя загружаемого файла и помещаем в input text

	$(".upload-block__input-file").change(function(){
    	$(this).parent().children("input[type='text']").val($(this).val().split('\\').pop());
	});


	// ivan
	//выбираю блок с кнопками переключения вида
	$('.switch-type').on('click','button', function(e){
			e.preventDefault();
			var $this = $(this);
			//реализовую саму подсветку кнопок переключения вида
			//при клике на кнопку, текущей задаю класс btn--active, а у соседней этот класс убираю
			$this
							.addClass('btn--active')
							.siblings()
							.removeClass('btn--active');
						//добавление класса block--hidden, в зависимости от выбранной кнопки 
						if ($this.hasClass('btn--one') ){
							$('.position').find('.block--borders').addClass('block--hidden').siblings().removeClass('block--hidden')
						} else {
							$('.position').find('.block--coordinates').addClass('block--hidden').siblings().removeClass('block--hidden')
						} 
	});
	//реализую выезжание кнопки социальных сетей
	$('.socials').find('.social__btn--like').click(function(){
		$('.socials').toggleClass( 'social--open' );
	});

	$( ".range-slider" ).slider({
	range: "min",
	value: 100,
	min: 1,
	max: 100,
	});
});

