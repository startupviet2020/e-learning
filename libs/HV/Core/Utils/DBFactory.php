<?
namespace HV\Core\Utils;
use Exception;
class DBFactory
{
	private static $_instance;
	private function __construct(){
		
	}

	public static function getInstance(){
		if (isset(self::$_instance)){
			return self::$_instance;
		}
		else
		{
			$className = __CLASS__;
			self::$_instance = new $className;
			return self::$_instance;;
		}
	}
	
	public function getDb($key){
		$className=$key;
		$inst = $className::getInstance();
		return $inst;
	}
}