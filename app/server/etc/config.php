<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if ( file_exists(__DIR__ . '/config.local.php') ) {
    include __DIR__ . '/config.local.php';
} elseif ( file_exists(__DIR__ . '/config.prod.php') ) {
	include(__DIR__ . '/config.prod.php');
} else { // throw Exceprion

	$config = array(
		# режим работы: prod, dev
		'ENV' => 'dev',
		# базовый url
		'BASE_URL' => '/_loftschool/dz-2.3',
		# путь к папке с кешом
		// 'file_cache' => WEBPATH . '../tmp/',
		# путь к папке, доступной для загрузки картинок
		'upload_dir' => WEBPATH . 'upload/',  // images/upload/

		'upload_url' => 'upload/'
	);

}
