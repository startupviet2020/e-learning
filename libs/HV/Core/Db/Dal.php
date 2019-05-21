<?
namespace HV\Core\Db;
use Exception;

abstract class Dal {
    private $ent_factory=null;
    private $db_factory=null;
    
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

    public function setEntityFactory($fac) {
        $this->ent_factory=$fac;
    }
    
    public function setDbFactory($fac) {
        $this->db_factory=$fac;
    }

	/**
	* Init and return an instance of $entity class
	* @param string $entity entity key which is declared in entity.conf.php
	* @return object an instance of class pointed by $entity key in entity.conf.php
	**/
	public function getEntity($entity) {
	    if ($this->ent_factory===null) {
	        $this->ent_factory = Factory::getInstance();
	    }
		return $this->ent_factory->getEntity($entity);
	}

	/**
	* Init and return a PDO connection object. Connection parameter is obtained from a db $profile name
	* @param string $profile db profile which is declared in db.conf.php
	* @param string type of database connection to, master or slave
	* @return object PDO connection object
	**/	
	public function getDbFromProfile($profile, $type=Connection::_DB_CONN_MASTER, $reconnect = FALSE) {
	    if ($this->db_factory===null) {
	        $this->db_factory = Connection::getInstance();
	    }
		return $this->db_factory->getDb($profile, $type, $reconnect);
	}
	
	/**
	* Init and return a PDO connection object. Connection parameter is obtained from entity's db profile
	* @param object $entity
	* @param string type of database connection to, master or slave
	* @return object PDO connection object
	**/
	public function getDb($entity, $type=Connection::_DB_CONN_MASTER, $reconnect = FALSE) {
		$dbprofile = $entity->getDbProfile();
		if ($dbprofile == ""){
			$dbprofile = Factory::getInstance()->getDbProfile($entity);
		}
		return $this->getDbFromProfile($dbprofile, $type, $reconnect);
	}

	public function exec_scalar($entity, $sql, $params = null){
		$db = $this->getEntity($entity);
		$conn = $this->getDb($db);
		return $db->exec_scalar($conn, $sql, $params);
	}

	public function exec_object($entity, $sql, $params = null){
		$db = $this->getEntity($entity);
		$conn = $this->getDb($db);
		return $db->exec_object($conn, $sql, $params);
	}

	public function exec_no_query($entity, $sql, $params = null){
		$db = $this->getEntity($entity);
		$conn = $this->getDb($db);
		return $db->exec_no_query($conn, $sql, $params);
	}

	public function exec_query($entity, $sql, $params = null){
		$db = $this->getEntity($entity);
		$conn = $this->getDb($db);
		return $db->exec_query($conn, $sql, $params);
	}	
} 