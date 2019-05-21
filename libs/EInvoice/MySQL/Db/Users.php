<?
/**
 * Do not use this class directly. Extend it and implement your functions.
 * This class intends for describing table structure only. It must be regenerated if table schema changed
 * Filename _Users.php
 * Generated at 2018/Oct/14 15:23:16 
 * @package dal
**/

/*
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `avatar` varchar(200) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `company` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 
*/

namespace EInvoice\MySQL\Db;
use \HV\Core\Db;
class Users extends Db\Entity{
	function __construct() {
		parent::__construct();
		$this->table_name="user";
		$this->db_profile="";
		$this->addField("id", parent::_DB_TYPE_INT, true, true);
		$this->addField("name", parent::_DB_TYPE_STRING, false, false);
		$this->addField("phone", parent::_DB_TYPE_STRING, false, false);
		$this->addField("email", parent::_DB_TYPE_STRING, false, false);
		$this->addField("avatar", parent::_DB_TYPE_STRING, false, false);
		$this->addField("address", parent::_DB_TYPE_STRING, false, false);
        $this->addField("status", parent::_DB_TYPE_INT, false, false);
        $this->addField("company", parent::_DB_TYPE_INT, false, false);
		$this->addField("created", parent::_DB_TYPE_INT, false, false);
		$this->addField("created_by", parent::_DB_TYPE_INT, false, false);
		$this->addField("updated", parent::_DB_TYPE_INT, false, false);
		$this->addField("updated_by", parent::_DB_TYPE_INT, false, false);
		
	}
}