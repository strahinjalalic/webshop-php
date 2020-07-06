<?php 
ob_start();
session_start();
error_reporting(E_ALL & ~E_NOTICE);

if($_SERVER['REQUEST_URI'] != '/e-com-master/public/admin/' AND $_SERVER['REQUEST_URI'] != '/e-com-master/public/admin/index.php' AND $_SERVER['REQUEST_URI'] != '/e-com-master/public/admin/index.php?products' AND $_SERVER['REQUEST_URI'] != '/e-com-master/public/admin/index.php?products&page='.$_GET['page'] AND $_SERVER['REQUEST_URI'] != '/e-com-master/public/admin/index.php?orders' AND $_SERVER['REQUEST_URI'] != '/e-com-master/public/admin/index.php?add_product' AND $_SERVER['REQUEST_URI'] != '/e-com-master/public/admin/index.php?add_user' AND $_SERVER['REQUEST_URI'] != '/e-com-master/public/admin/index.php?users' AND $_SERVER['REQUEST_URI'] != '/e-com-master/public/admin/index.php?categories' AND $_SERVER['REQUEST_URI'] != '/e-com-master/public/admin/index.php?brands' AND $_SERVER['REQUEST_URI'] != '/e-com-master/public/admin/index.php?reports' AND $_SERVER['REQUEST_URI'] != '/e-com-master/public/admin/index.php?slides' AND $_SERVER['REQUEST_URI'] != '/e-com-master/public/admin/index.php?edit_product&id=' . $_GET['id'] AND $_SERVER['REQUEST_URI'] != '/e-com-master/public/admin/index.php?edit_user&id='. $_GET['id'] AND $_SERVER['REQUEST_URI'] != '/e-com-master/public/admin/index.php?delete_product&id='.$_GET['id'] AND $_SERVER['REQUEST_URI'] != '/e-com-master/public/admin/index.php?delete_category&id='.$_GET['id'] AND $_SERVER['REQUEST_URI'] != '/e-com-master/public/admin/index.php?delete_brand&id='.$_GET['id'] AND $_SERVER['REQUEST_URI'] != '/e-com-master/public/admin/index.php?delete_order&id='.$_GET['id'] AND $_SERVER['REQUEST_URI'] != '/e-com-master/public/admin/index.php?delete_report&id='.$_GET['id'] AND $_SERVER['REQUEST_URI'] != '/e-com-master/public/admin/index.php?delete_slide&id='.$_GET['id']) {
    require_once('../vendor/autoload.php');
    require_once('../vendor/stripe/stripe-php/init.php');
} else {
    require_once('../../vendor/autoload.php');
}

$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'] . '/e-com-master/');
$dotenv->load();

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('TEMPLATE_FRONT') ? null : define('TEMPLATE_FRONT', __DIR__ . DS . "templates" . DS . "front");
defined('TEMPLATE_BACK') ? null : define('TEMPLATE_BACK', __DIR__ . DS . "templates" . DS . "back");
defined('UPLOAD_DIRECTORY') ? null : define('UPLOAD_DIRECTORY', __DIR__ . DS . "uploads");
defined("ENV") ? null : define('ENV', "C:\\xampp\\htdocs\\e-com-master\\");

defined('DB_HOST') ? null : define('DB_HOST', getenv('LOCALHOST'));
defined('DB_NAME') ? null : define('DB_NAME', getenv('DB_NAME'));
defined('DB_USER') ? null : define('DB_USER', getenv('DB_USER'));
defined('DB_PASSWORD') ? null : define('DB_PASSWORD', getenv('DB_PASSWORD'));

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
require_once('functions.php');
require_once('cart.php');
?>