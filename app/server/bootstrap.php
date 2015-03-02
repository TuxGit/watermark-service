<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

# include settings
require_once BASEPATH . 'etc/config.php';
require_once BASEPATH . 'etc/routes.php';

# include core
require_once BASEPATH . 'modules/core.php';
require_once BASEPATH . 'controllers/apiCtrl.php';


function &get_instance()
{
	return App::get_instance();
}

session_start();
// global $APP;
$APP = new App($config, $routes);
$APP->init();
