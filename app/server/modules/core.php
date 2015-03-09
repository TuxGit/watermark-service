<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// namespace \modules\core

class App {

	private static $instance;

	# current user 
	public $USER;

	# Настройки url адресации
	public $routes = array();
	# Общий конфиг, где лежат ностройки хоста, базы данны, ...
	public $config = array();
	# Разбитый на части текущий url
	public $url_segments = array();
	# текущий url
	public $path;

	/**
	 * Constructor
	 */
	function __construct ($config, $routes)
	{
		self::$instance =& $this;

		$this->config = $config;
		$this->routes = $routes;

		if ( isset($_SERVER['REQUEST_URI']) and $_SERVER['REQUEST_URI'] ) {
			
			$url = $_SERVER['REQUEST_URI'];

			if ($this->config['BASE_URL'] !== '/')
				$url = str_replace($this->config['BASE_URL'], '', $url);

			if ( strpos($url, '?') !== FALSE ) {

				$url = explode('?', $url);  // разделим в массив
				$url = $url[0]; 			// сегменты - это только первая часть				
				// $params = $url[1];				
			}
			$this->path = $url;
			$url = explode('/', $url);      // разделим в массив по /
			$i = 1;
			foreach($url as $val) {
				if ($val) {
					$this->url_segments[$i] = $val;
					$i++;
				}
			}

		} else {
			throw new Exception("Server Error");
		}

	}


	public static function &get_instance()
	{
		return self::$instance;
	}


	function init()
	{
		if ( count($this->routes) >= 0 ) {

			if ( count($this->url_segments) == 0 )
				return;
				// $this->path =  '/home';		

			foreach ($this->routes as $key => $val) {
				try {
					if ( preg_match($key, $this->path, $matches) ) {
						$ctrl = $val['controller'];
						$action = $val['action'];

						if (isset($matches[1]))
							$output = (new $ctrl())->$matches[1]();
						else
							$output = (new $ctrl())->$action();

						// print_r($output); 
						print $output;
					}
				} catch (Exception $e) {
					echo $e->getMessage();
				}
			}

		} else {
			throw new Exception("Server Error");
		}
	}
}

