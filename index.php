<?
define('ROOT', dirname(__FILE__));
define('ENV', !empty($_SERVER['argv']) ? 'Cli' : 'Web');

function dump($arg) {
	echo '<pre>';
	print_r($arg);
	echo '</pre>';
}

include ROOT.'/conf/storage.php';
include ROOT.'/controllers/'.ENV.'Controller.php';