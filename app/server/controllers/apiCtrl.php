<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . 'vendor/autoload.php';

// use Gregwar\Captcha\CaptchaBuilder;
use \WideImage\WideImage;
use \PHPImageWorkshop\ImageWorkshop;
// global $APP;


class ApiCtrl
{
	private $mode = '';
	public $base_url = '';
	private $builder;


	public function __construct($mode = 'dev')
	{
		

	}


	public function upload()
	{
		$APP = & get_instance();

		// if (session_id())
		// $data = $_SESSION['data'];

		// $_type = filter_var($_POST['type'], constant('FILTER_SANITIZE_NUMBER_INT'));
		// $_ext = null;

		if (!empty($_FILES['image']['name'])) {
			$filetype = 'image';
			// $type = 1;
			$file = $this->saveFile($filetype);
		} else if (!empty($_FILES['watermark']['name'])) {
			$filetype = 'watermark';
			// $type = 2;
			$file = $this->saveFile($filetype);
		}
		
		if (isset($_SESSION[$filetype])) {
			// remove old image
		}
		$_SESSION[$filetype] = $file;
		return json_encode($file);
	}

	private function saveFile($filename)
	{
		$APP = & get_instance();

		$uploaddir = $APP->config['upload_dir'];
		$_filename = basename($_FILES[$filename]['name']);
		$uploadfile = $uploaddir . $_filename;
		// $uploadfile = $uploaddir . basename($_FILES[$filename]['name']);
		// $image = $APP->config['upload_url'] . $_FILES[$filename]['name'];

		if (!move_uploaded_file($_FILES[$filename]['tmp_name'], $uploadfile))
			throw new Exception("Ошибка загрузки картинки!", 1);

		$image_url = $APP->config['upload_url'] . $_filename;
		list($width, $height, $type, $attr) = getimagesize($uploadfile);
		$res = array(
			'width' => $width,
			'height' => $height,
			'type' => $type,
			'attr' => $attr,

			'filename' => $_filename,
			'url' => $image_url
		);

		return $res;
	}

	public function download()
	{
		// $APP = & get_instance();

		if (isset($_GET['file'])) {
			// header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename=".basename($_GET['file'])."");
			header("Content-Transfer-Encoding: binary");
			header("Content-Type: binary/octet-stream");
			readfile(WEBPATH . $_GET['file']);

			return;	// exit();
		}

		// test
		/*
		$_POST['type'] = 'grid';		
		$_POST['x'] = 20;  // 300
		$_POST['y'] = 10;  // -20
		$_POST['opacity'] = 50;
		*/
		// end test
		$_SESSION = array_merge(
			array(
				'image' => array('filename' => 'image.jpg', 'width' => 651, 'height' => 534),
				'watermark' => array('filename' => 'watermark.png', 'width' => 216, 'height' => 223)
			), $_SESSION);


		if ( !isset($_SESSION['image']) || !isset($_SESSION['watermark']) )
			throw new Exception("Загрузите сначала изображения!", 1);

		$_type = filter_var( $_POST['type'], constant('FILTER_VALIDATE_REGEXP'), 
			array("options"=>array("regexp" => '/(one|grid)/')) );
		$_x = filter_var($_POST['x'], constant('FILTER_VALIDATE_INT'));
		$_y = filter_var($_POST['y'], constant('FILTER_VALIDATE_INT'));
		$_op = filter_var($_POST['opacity'], constant('FILTER_VALIDATE_INT'));

		if ( !isset($_type) || !isset($_x) || !isset($_y) || !isset($_op) )
			throw new Exception("Неверные параметры!", 1);

		$file = $this->addWatermark(
			$_SESSION['image'], 
			$_SESSION['watermark'], 
			$_type, $_x, $_y, $_op
		);
		$res = array(
			"url" => $file['url']
		);
		// tmp, clear vars
		unset($_SESSION['image']);
		unset($_SESSION['watermark']);
		// session_destroy();

		return json_encode($res);
	}

	private function addWatermark($img_file, $wm_file, $type, $x, $y, $op)
	{
		$APP = & get_instance();

		$file = array();
		$upload_dir = $APP->config['upload_dir'];
		// $img = WideImage::loadFromFile($upload_dir . $img_file['filename']);
		// $wm = WideImage::loadFromFile($upload_dir . $wm_file['filename']);
		$img = ImageWorkshop::initFromPath($upload_dir . $img_file['filename']);
		$wm = ImageWorkshop::initFromPath($upload_dir . $wm_file['filename']);
		$wm->opacity($op);
		
		// http://stackoverflow.com/questions/11965709/wideimage-transparent-areas-of-converted-png-should-be-white/13427679
		// wideimage merge with opacity
		// $wm->fill(0, 0, $wm->getTransparentColor());
		//
		// $bg = $wm->allocateColor(255,255,255); $bg = $wm->allocateColorAlpha(0,0,0,127);
		// $wm->fill(0,0,$bg);


		if ($type === 'one') {
			// use uuid
			// $filename = substr(session_id(), 2, 5) . '__' . $img_file['filename'] . $wm_file['filename'];
			$filename = substr(session_id(), 2, 10) . '.png';
			// $img->merge($wm, $x, $y, $op)->saveToFile($upload_dir . $filename);
			$img->addLayerOnTop($wm, $x, $y, 'LT');

			$file['filename'] = $filename;
			$file['url'] = $APP->config['upload_url'] . $filename;
		} else if ($type === 'grid') {
			// в цикле мерджим вотермарки от центра с нитервалом х, у

			// https://php.net/manual/ru/function.round.php
			$N_x = round( $img_file['width']/($x + $wm_file['width']), 0, PHP_ROUND_HALF_EVEN );
			$N_y = round( $img_file['height']/($y + $wm_file['height']), 0, PHP_ROUND_HALF_EVEN );

			$X_0 = $img_file['width']/2 - $N_x/2*($x + $wm_file['width']);
			$Y_0 = $img_file['height']/2 - $N_y/2*($y + $wm_file['height']);
			
			$_x = $X_0;
			$_y = $Y_0;
			// echo $N_x, ' ', $N_y;
			for ($i=0; $i < $N_x; $i++) {
				
				// $img = $img->merge($wm, $_x, $_y, $op);
				$img->addLayerOnTop($wm, $_x, $_y, 'LT');
				
				for ($j=0; $j < $N_y-1; $j++) {
					$_y += $y + $wm_file['height'];
					// $_x += $x + $wm_file['width'];
					// $img = $img->merge($wm, $_x, $_y, $op);
					$img->addLayerOnTop($wm, $_x, $_y, 'LT');
				}
				$_y = $Y_0;
				// $_y += $y + $wm_file['height'];
				$_x += $x + $wm_file['width'];
			}
			// $filename = substr(session_id(), 2, 7) . '__' . $img_file['filename'] . $wm_file['filename'];
			$filename = substr(session_id(), 2, 10) . '.png';
			// $img->saveToFile($upload_dir . $filename);
			
			$file['filename'] = $filename;
			$file['url'] = $APP->config['upload_url'] . $filename;
		}
		// Saving the result
		$dirPath = $upload_dir;
		// $filename = "pingu_edited.png";
		$createFolders = true;
		$backgroundColor = 'transparent'; // null || transparent, only for PNG (otherwise it will be white if set null)
		$imageQuality = 70; // useless for GIF, usefull for PNG and JPEG (0 to 100%)
		$img->save($dirPath, $filename, $createFolders, $backgroundColor, $imageQuality);

		return $file;
	}

	public function delFiles()
	{
		// удалять по крону и запросом с клиента после загрузки
	}

	// @delete
	public function addProject()
	{
		// $json = json_decode(file_get_contents('php://input'), true);
		// print_r($json);

		// print_r($_POST); print_r($_FILES);
		/*
		Array( 
			[name] => asdas 
			- [image] => buy_love.JPG 
			[url] => da 
			[descr] => aa)
		Array( [file] => Array ( 
			[name] => buy_love.JPG 
			[type] => image/jpeg 
			[tmp_name] => C:\Windows\TEMP\phpC36F.tmp 
			[error] => 0 [size] => 17336 ))
		*/
		// global $APP;

		// validate _POST
		$APP = & get_instance();

		try {
			$name = filter_var( $_POST['name'], constant('FILTER_SANITIZE_STRING') );
			$url = filter_var( isset($_POST['url']) ? $_POST['url'] : '', constant('FILTER_SANITIZE_URL') );
			$descr = filter_var( isset($_POST['descr']) ? $_POST['descr'] : '', constant('FILTER_SANITIZE_STRING') );
			$image = 'default.jpg';
			
			if (!empty($_FILES['image']['name'])) {
				$uploaddir = $APP->config['uploaddir'];
				$uploadfile = $uploaddir . basename($_FILES['image']['name']);
				$image = 'upload/' . $_FILES['image']['name'];

				if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile))
					throw new Exception("Ошибка загрузки картинки!", 1);
 			}

 			if (!$name)
 				throw new Exception("Project name required!", 1);


 			$query = $APP->db->prepare("INSERT INTO p_portfolio 
 				(title, img, url, descr) 
 				VALUES (:title, :img, :url, :descr)");
 			$result = $query->execute(array(
 				':title' => $name,
 				':img' => $image,
 				':url' => $url,
 				':descr' => $descr
 			));
 			// ---$q = $result->fetch(PDO::FETCH_ASSOC);
 			// print_r($APP->db->lastInsertId());

 			if ($result) {
	 			header("HTTP/1.1 200 OK");
				return '{"success":true, "text":"model"}';
			} else
				throw new Exception("Inset error!", 1);				

		} catch (Exception $e) {
			header("HTTP/1.1 400 BAD REQUEST");
			return '{"success":false, "text":"'.$e->getMessage().'"}';
		}

	}


}