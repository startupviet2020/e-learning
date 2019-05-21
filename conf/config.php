<?
namespace Config;
require_once("const.php");
use Redis;
class AppConfig{
	public static function getDbSettings(){
		require_once("db.conf.php");
		return $_dbsettings;
	}

	public static function getEntitySettings(){
		require_once("entity.conf.php");
		return $_dbmappings;
	}
}