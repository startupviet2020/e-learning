<?
/**
 * Do not use this class directly. Extend it and implement your functions.
 * This class intends for describing table structure only. It must be regenerated if table schema changed
 * Filename _UserRoles.php
 * Generated at 2018/Oct/15 15:18:02 
 * @package dal
**/

/*
CREATE TABLE `user_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `rid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 
*/

namespace EInvoice\MySQL\Db;
use \HV\Core\Db;
class UserRoles extends Db\Entity{
	function __construct() {
		parent::__construct();
		$this->table_name="user_role";
		$this->db_profile="";
		$this->addField("id", parent::_DB_TYPE_INT, true, true);
		$this->addField("uid", parent::_DB_TYPE_INT, false, false);
		$this->addField("rid", parent::_DB_TYPE_INT, false, false);
		
	}
}