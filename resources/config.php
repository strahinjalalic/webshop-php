<?php 
ob_start();
session_start();

require_once('../vendor/autoload.php');
require_once('../vendor/stripe/stripe-php/init.php');
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