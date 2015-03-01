<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . 'vendor/autoload.php';

// use Gregwar\Captcha\CaptchaBuilder;
use \WideImage\WideImage;

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
		$data = $_SESSION['data'];

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

		$image_url = $APP->config['upload_url'] . $filename;
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
		
		if ( !isset($_SESSION['image']) || !isset($_SESSION['watermark']) )
			throw new Exception("Загрузите сначала изображения!", 1);

		$_type = filter_var( $_POST['type'], constant('FILTER_VALIDATE_REGEXP'), 
			array("options"=>array("regexp" => '/(one|grid)/')) );
		$_x= filter_var($_POST['x'], constant('FILTER_VALIDATE_INT'));
		$_y = filter_var($_POST['y'], constant('FILTER_VALIDATE_INT'));
		$_op = filter_var($_POST['opacity'], constant('FILTER_VALIDATE_INT'));

		if (!$_type || !$_x || !$_y || !$_op)
			throw new Exception("Неверные параметры!", 1);

		$file = $this->addWatermark(
			$_SESSION['image'], 
			$_SESSION['watermark'], 
			$_type, $_x, $_y, $_op
		);
		$res = array(
			"url": $file['url']
		);

		return $res;
	}

	private function addWatermark($img_file, $wm_file, $type, $x, $y, $op)
	{
		$APP = & get_instance();

		$file = array();
		$upload_dir = $APP->config['upload_dir'];
		$img = WideImage::loadFromFile($upload_dir . $img_file['filename']);
		$wm = WideImage::loadFromFile($upload_dir . $wm_file['filename']);

		if ($type === 'one') {
			// use uuid
			$filename = substr(session_id(), 2, 5) . $img_file['filename'] . $wm_file['filename'];
			$img->merge($wm, $x, $y, $op)->saveToFile($upload_dir . $filename);

			$file['filename'] = $filename;
			$file['url'] = $APP->config['upload_url'] . $filename;
		} else if ($type === 'grid') {
			// в цикле мерджим вотермарки от центра с нитервалом х, у
		}
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