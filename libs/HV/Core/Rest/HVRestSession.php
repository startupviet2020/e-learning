<?
namespace HV\Core\Rest;
use Exception;
class HVRestSession{
	private $_storage = null;

	protected function __construct(){

	}

	final public static function getInstance()
	{
			static $instances = array();

			$calledClass = get_called_class();

			if (!isset($instances[$calledClass]))
			{
					$instances[$calledClass] = new $calledClass();
			}

			return $instances[$calledClass];
	}

	final private function __clone()
	{

	}

	static public function makeKey($len = 40){
		$bytes = openssl_random_pseudo_bytes($len * 2, $strong);

        if ($bytes === false || $strong === false) {
            throw new Exception('Error Generating Key');
        }

        return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $len);
	}

	public function setStorage($storage) {
		$this->_storage = $storage;
	}

	public function loadSession($sid){
		if ($this->_storage){
			return $this->_storage->loadSession($sid);	
		}
		else{
			return false;
		}
	}

	public function addSession($data){
		if ($this->_storage){
			return $this->_storage->addSession($data);	
		}
		else{
			return false;
		}
	}

	public function deleteSession($sid){
		if ($this->_storage){
			$this->_storage->deleteSession($sid);	
		}
	}
}