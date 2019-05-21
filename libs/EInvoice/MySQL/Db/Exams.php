<?
/**
 * Do not use this class directly. Extend it and implement your functions.
 * This class intends for describing table structure only. It must be regenerated if table schema changed
 * Filename _Exams.php
 * Generated at 2015/Dec/11 04:31:52 
 * @package dal
**/

/*
CREATE TABLE `exams` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `exam_date` int(11) DEFAULT '0',
  `type` varchar(11) DEFAULT '',
  `notes` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=314 DEFAULT CHARSET=utf8 
*/

namespace EInvoice\MySQL\Db;
use \HV\Core\Db;
class Exams extends Db\Entity{
	function __construct() {
		parent::__construct();
		$this->table_name="exams";
		$this->db_profile="";
		$this->addField("id", parent::_DB_TYPE_INT, true, true);
		$this->addField("name", parent::_DB_TYPE_STRING, false, false);
		$this->addField("exam_date", parent::_DB_TYPE_INT, false, false);
		$this->addField("type", parent::_DB_TYPE_STRING, false, false);
		$this->addField("notes", parent::_DB_TYPE_STRING, false, false);
		$this->addField("data", parent::_DB_TYPE_STRING, false, false);
		$this->addField("status", parent::_DB_TYPE_INT, false, false);
		$this->addField("created", parent::_DB_TYPE_INT, false, false);
		$this->addField("updated", parent::_DB_TYPE_INT, false, false);
	}
}