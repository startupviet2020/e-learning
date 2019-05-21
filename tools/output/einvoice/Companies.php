<?
/**
 * Do not use this class directly. Extend it and implement your functions.
 * This class intends for describing table structure only. It must be regenerated if table schema changed
 * Filename _Companies.php
 * Generated at 2019/Jan/10 06:10:48 
 * @package dal
**/

/*
CREATE TABLE `company` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `tax_code` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `managing_unit` varchar(100) DEFAULT NULL,
  `currency_unit` varchar(4) DEFAULT NULL,
  `bank_account` varchar(50) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_branch` varchar(100) DEFAULT NULL,
  `chief_accountant` varchar(100) DEFAULT NULL,
  `director` varchar(100) DEFAULT NULL,
  `invoice_type` int(11) DEFAULT NULL,
  `logo` varchar(200) DEFAULT NULL,
  `agency` int(11) DEFAULT NULL,
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
class Companies extends Db\Entity{
	function __construct() {
		parent::__construct();
		$this->table_name="company";
		$this->db_profile="";
		$this->addField("id", parent::_DB_TYPE_INT, true, true);
		$this->addField("name", parent::_DB_TYPE_STRING, false, false);
		$this->addField("address", parent::_DB_TYPE_STRING, false, false);
		$this->addField("tax_code", parent::_DB_TYPE_STRING, false, false);
		$this->addField("phone", parent::_DB_TYPE_STRING, false, false);
		$this->addField("fax", parent::_DB_TYPE_STRING, false, false);
		$this->addField("email", parent::_DB_TYPE_STRING, false, false);
		$this->addField("managing_unit", parent::_DB_TYPE_STRING, false, false);
		$this->addField("currency_unit", parent::_DB_TYPE_STRING, false, false);
		$this->addField("bank_account", parent::_DB_TYPE_STRING, false, false);
		$this->addField("bank_name", parent::_DB_TYPE_STRING, false, false);
		$this->addField("bank_branch", parent::_DB_TYPE_STRING, false, false);
		$this->addField("chief_accountant", parent::_DB_TYPE_STRING, false, false);
		$this->addField("director", parent::_DB_TYPE_STRING, false, false);
		$this->addField("invoice_type", parent::_DB_TYPE_INT, false, false);
		$this->addField("logo", parent::_DB_TYPE_STRING, false, false);
		$this->addField("agency", parent::_DB_TYPE_INT, false, false);
		$this->addField("status", parent::_DB_TYPE_INT, false, false);
		$this->addField("created", parent::_DB_TYPE_INT, false, false);
		$this->addField("created_by", parent::_DB_TYPE_INT, false, false);
		$this->addField("updated", parent::_DB_TYPE_INT, false, false);
		$this->addField("updated_by", parent::_DB_TYPE_INT, false, false);
		
	}
}