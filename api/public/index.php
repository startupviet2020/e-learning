<?
require_once($_SERVER["CONF_PATH"] . "config.php");
require_once(dirname(dirname(dirname(__FILE__))) . "/vendor/autoload.php");
require_once(APP_PATH . 'api.php');
HVApi::run();