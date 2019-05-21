<?
/**
 * Do not use this class directly. Extend it and implement your functions.
 * This class intends for describing table structure only. It must be regenerated if table schema changed
 * Filename _Sessions.php
 * Generated at 2018/Oct/11 14:13:28 
 * @package dal
**/

/*
CREATE TABLE `session` (
  `sid` varchar(32) NOT NULL DEFAULT '',
  `uid` int(11) DEFAULT NULL,
  `hostname` varchar(32) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 
*/

namespace EInvoice\MySQL\Db;
use \HV\Core\Db;
class Sessions extends Db\Entity{
	function __construct() {
		parent::__construct();
		$this->table_name="session";
		$this->db_profile="";
		$this->addField("sid", parent::_DB_TYPE_STRING, true, false);
		$this->addField("uid", parent::_DB_TYPE_INT, false, false);
		$this->addField("hostname", parent::_DB_TYPE_STRING, false, false);
		$this->addField("timestamp", parent::_DB_TYPE_INT, false, false);
		
	}
}