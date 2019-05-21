<?
namespace HV\Core\Db;
use PDO;
use Exception;
class Connection{
	const _DB_CONN_MASTER	=	"master";
	const _DB_CONN_SLAVE	=	"slave";
	
	private static $inst=null;
	private $_dbsettings;
	private $master_conn;
	private $slave_conn;
	private function __construct(){
		$this->master_conn=array();
		$this->slave_conn=array();
	}
	
	public function setDbConf($conf){
		$this->_dbsettings=$conf;
	}
	
	public static function getInstance(){
		if (self::$inst===null){
			$className = __CLASS__;
			self::$inst = new $className;
		}
		return self::$inst;
	}
        
    public static function setInstance(Connection $inst){		
        self::$inst = $inst;
	}
	
	private function getMasterDb($profile, $reconnect = FALSE){
		if (!array_key_exists($profile, $this->master_conn)){
			if (!array_key_exists($profile, $this->_dbsettings)){
				throw new Exception("DB settings profile " . $profile . " is not found");
			}
			$conf=$this->_dbsettings[$profile]["master"];
			$host=$conf["server"];
			$dbname=$conf["dbname"];
			$dbuser=$conf["user"];
			$dbpass=$conf["password"];
			$pdo=new PDO("mysql:host=$host;dbname=$dbname", "$dbuser", "$dbpass", array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
			if (array_key_exists("mode", $this->_dbsettings[$profile]) && $this->_dbsettings[$profile]["mode"]=="DEBUG"){
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			$this->master_conn[$profile]=$pdo;
		}
        
        //if required to reconnect when
        //this connection is timeout
        if ($reconnect){
            //get the pdo connection
            $pdo = $this->master_conn[$profile];
            //whether or not the connection is still alive
            
            if ( ! $this->ping($pdo)){
                //if the connection is timeout
                //reconnect and store the new connection
                //in the connections array                
                unset($this->master_conn[$profile]);
                $pdo = $this->getMasterDb($profile);
                $this->master_conn[$profile]=$pdo;
            }            
        }
		return $this->master_conn[$profile];
	}
    
    
    /**
    * check if a connection is still alive
    * 
    * @param PDO $connection PDO connection
    * @return boolean TRUE if alive
    * @author Tommy   
    */
    public function ping($connection)
    {           
        $info = $connection->getAttribute(PDO::ATTR_SERVER_INFO);
        if (strpos($info, 'gone away') !== FALSE){
            return FALSE;
        }
        return TRUE;                   
    }
	
	private function getSlaveDb($profile){
		if (!array_key_exists($profile, $this->_dbsettings)){
			throw new Exception("DB settings profile " . $profile . " is not found");
		}
		$conf=$this->_dbsettings[$profile];
		if (!array_key_exists("slaves", $conf)){
			$slave_conf=array($conf["master"]);
		}
		else{
			$slave_conf=$conf["slaves"];
		}
		$selected=rand(count($slave_conf)-1);
		if (!array_key_exists($profile, $this->slave_conn)){
			$this->slave_conn[$profile]=array();
		}
		if (!array_key_exists($selected, $this->slave_conn[$profile])){
			$host=$slave_conf[$selected]["server"];
			$dbname=$slave_conf[$selected]["dbname"];
			$dbuser=$slave_conf[$selected]["user"];
			$dbpass=$slave_conf[$selected]["password"];
			$pdo=new PDO("mysql:host=$host;dbname=$dbname", "$dbuser", "$dbpass");
			if (array_key_exists("mode", $this->_dbsettings[$profile]) && $this->_dbsettings[$profile]["mode"]=="DEBUG"){
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			$this->slave_conn[$profile][$selected]=$pdo;
		}
		return $this->slave_conn[$profile][$selected];
	}
	
	public function getDb($profile, $type=self::_DB_CONN_MASTER, $reconnect = FALSE){
		if ($type===self::_DB_CONN_MASTER){
			return $this->getMasterDb($profile, $reconnect);
		}
		else{
			return $this->getSlaveDb($profile);
		}
	}
}