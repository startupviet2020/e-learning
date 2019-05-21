<?
namespace HV\Core\Db;
use Exception;
class Factory {
	private static $_instance;
	private $_maps;
	private function __construct(){
		
	}
	
	public function setEntityConf($conf){
		$this->_maps=$conf;
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
	
	public function getEntity($key){
		if (!isset($this->_maps[$key])){
			$dbprofile = $this->_maps["default"];
		}
		else{
			$dbprofile = $this->_maps[$key];
		}
		$inst = $key::getInstance();
		$inst->setDbProfile($dbprofile); 
		return $inst;
	}

	public function getDbProfile($entity){
		$key = get_class($entity);
		if (!isset($this->_maps[$key])){
			$dbprofile = $this->_maps["default"];
		}
		else{
			$dbprofile = $this->_maps[$key];
		}
		return $dbprofile;
	}
}