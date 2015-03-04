<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


$routes = array(	

	/*
	Request: POST /api/upload
		params: ~~type=1&ext=png~~

	Response:
	{
		"width": 100,
		"height": 100,
		"ext": "png",
		"type": 1,     // 1 - main, 2 - watermark
		"image": <img-inline-base64>
		or "url": ...
	}
	*/
	'/api\/upload/' => array(
		'controller' => 'ApiCtrl',
		'action' => 'upload'),

	/*
	Request: GET /api/download?[id=unique_id]
		params: type=one&x=100&y=200
		params: type=grid&x=10&y=20

	Response: 
	{
		"url": ...
	}
	or image/png
	
	*/
	'/api\/download/' => array(
		'controller' => 'ApiCtrl',
		'action' => 'download'),

	// '/api\/(\w+)/' => array(
	// 	'controller' => 'ApiCtrl',
	// 	'action' => 'default')
);

// $im = imagecreatefrompng($icon["url"]);
// if ($im && negate($im)) {
// ob_start();
// imagepng($im);
// $imgData=ob_get_clean();
// imagedestroy($im);
// echo '<img src="data:image/png;base64,'.base64_encode($imgData).' />';
// }