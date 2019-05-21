<?
echo "<?\n";
?>
/**
 * Do not use this class directly. Extend it and implement your functions.
 * This class intends for describing table structure only. It must be regenerated if table schema changed
 * Filename _<?echo $class_name?>.php
 * Generated at <?echo date("Y/M/d H:i:s");?> 
 * @package dal
**/

/*
<?echo $sql_create?> 
*/

namespace <?echo $namespace?>;
use \HV\Core\Db;
class <?echo $class_name?> extends Db\Entity{
	function __construct() {
		parent::__construct();
		$this->table_name="<?echo $table_name?>";
		$this->db_profile="";
<?
for ($i=0; $i<count($fields); $i++){
	$f=$fields[$i];
	$field_name=$f->Field;
	$field_pk="false";
	if ($f->Key=="PRI"){
		$field_pk="true";
	}
	$field_auto="false";
	if ($f->Extra=="auto_increment"){
		$field_auto="true";
	}
	$field_type="parent::_DB_TYPE_INT";
	if ($f->Type=="string"){
		$field_type="parent::_DB_TYPE_STRING";
	}
	else if ($f->Type=="float"){
		$field_type="parent::_DB_TYPE_FLOAT";
	}
	else if ($f->Type=="bool"){
		$field_type="parent::_DB_TYPE_BOOL";
	}
?>
		$this->addField("<?echo $field_name?>", <?echo $field_type?>, <?echo $field_pk?>, <?echo $field_auto?>);
<?
}
?>		
	}
}