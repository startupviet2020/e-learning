<?
$_SERVER['CONF_PATH'] = dirname(dirname(dirname(__FILE__))) . "/conf/";

if (file_exists(__DIR__ . $_SERVER['SCRIPT_NAME'])){
    return false;
} else{
    include_once 'index.php';
}