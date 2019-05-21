<?
/**
 * Do not use this class directly. Extend it and implement your functions.
 * This class intends for describing table structure only. It must be regenerated if table schema changed
 * Filename _Products.php
 * Generated at 2018/Oct/18 15:56:02 
 * @package dal
**/

/*
CREATE TABLE `product` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `unit` varchar(20) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 
*/

namespace EInvoice\MySQL\Db;
use \HV\Core\Db;
class Products extends Db\Entity{
	function __construct() {
		parent::__construct();
		$this->table_name="product";
		$this->db_profile="";
		$this->addField("id", parent::_DB_TYPE_INT, true, true);
		$this->addField("cid", parent::_DB_TYPE_INT, false, false);
		$this->addField("name", parent::_DB_TYPE_STRING, false, false);
		$this->addField("unit", parent::_DB_TYPE_STRING, false, false);
		$this->addField("price", parent::_DB_TYPE_FLOAT, false, false);
		$this->addField("description", parent::_DB_TYPE_STRING, false, false);
		$this->addField("status", parent::_DB_TYPE_INT, false, false);
		$this->addField("created", parent::_DB_TYPE_INT, false, false);
		$this->addField("created_by", parent::_DB_TYPE_INT, false, false);
		$this->addField("updated", parent::_DB_TYPE_INT, false, false);
		$this->addField("updated_by", parent::_DB_TYPE_INT, false, false);
		
	}
}