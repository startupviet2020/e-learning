<?
namespace HV\Core\Db;
use PDO;
use Exception;
abstract class Entity{
	const _DB_TYPE_STRING='string';
	const _DB_TYPE_INT='int';
	const _DB_TYPE_FLOAT='float';
	const _DB_TYPE_TIMESTAMP='int';
	const _DB_TYPE_BOOL='bool';
	
	protected $fields;
	protected $id="";
	protected $table_name="";
	protected $db_profile="";
	
	protected function __construct(){
		$this->fields=array();
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

	/**
	 * Add field description to $fields metadata variable
	 * @param string $name field name
	 * @param string $type field data type
	 * @param bool $id true if field is primary key and false if otherwise
	 * @param bool $auto_inc true if field is auto increment
	**/	
	protected function addField($name, $type, $id, $auto_inc){
		$this->fields[$name]=array("type" => $type, "id" => $id, "auto" => $auto_inc);
		if ($id){
			$this->id=$name;
		}
	}
	
	public function setDbProfile($profile){
		$this->db_profile = $profile;
	}

	public function getDbProfile(){
		return $this->db_profile;
	}
    
    public function getFields(){
        return $this->fields;
    }
		
	/**
	 * Quote input data to prevent SQL Injection
	 * @param object $conn MySQL_PDO connection to server
	 * @param mixed $val data to be quoted
	 * @param string $type the data type of $val
	 * @return If $type is string, return new string after escape special characters. If $type is numeric, call convertType to cast $val to correct $type
	**/
	protected function quote($conn, $val, $type){
		if ($type==self::_DB_TYPE_STRING){
			return $conn->quote($val);
		}
		else{
			return $this->convertType($val, $type);
		}
	}
	
	protected function convertType($val, $type){
		if ($type==self::_DB_TYPE_INT || $type==self::_DB_TYPE_TIMESTAMP){
			return (int)$val;
		}
		if ($type==self::_DB_TYPE_FLOAT){
			return (float)$val;
		}
		if ($type==self::_DB_TYPE_BOOL){
			return (bool)$val;
		}
		throw new Exception("Invalid " . $type . " to be converted");
	}
	
	/**
	 * Generate INSERT INTO query from input parameter and execute that query
	 * @param object $conn MySQL_PDO connection to server
	 * @param array $data Associated array where key is field name
	 * @return If query execute sucessfully and id field is an auto_increment, return the insert_id, otherwise return true on sucessfully or false if not
	**/
	public function insert($conn, $data){
		$sql_fields="";
		$sql_vals="";
		$id_included=false;
		
		foreach($data as $field => $val){
			if (!array_key_exists($field, $this->fields)){
				throw new Exception("Field " . $field . " does not exists");
			}
			
			$field_info=$this->fields[$field];
			if (!$id_included && $field_info["id"]){
				$id_included=true;
			}
			
			if ($sql_fields!=""){
				$sql_fields = $sql_fields . ", ";
			}
			$sql_fields = $sql_fields . "`" . $field . "`";
			
			if ($sql_vals!=""){
				$sql_vals = $sql_vals . ", ";
			}
			
			$sql_vals = $sql_vals . $this->quote($conn, $val, $field_info["type"]);
		}
		
		$sql="INSERT INTO `" . $this->table_name . "` (" . $sql_fields . ") VALUES (" . $sql_vals . ")";
		
		$res=$conn->exec($sql);
		if ($res===FALSE){            
			return $res;
		}
		if (!$id_included && $this->id!=""){
			if ($this->fields[$this->id]["auto"]){
				return $conn->lastInsertId();
			}
		}
		return TRUE;
	}
	
	/**
	 * Generate WHERE clause from an associated array with key is field name. Only support for = operator
	 * @param object $conn MySQL_PDO connection, using to quote string value
	 * @param array $where Associated array where key is field name
	 * @return string return a where clause in format field1=value1 AND field2=value2 AND ...
	**/
	private function getWhereClause($conn, $where){
		if (!is_array($where)){
			return "";
		}
		$sql_where="";
		foreach($where as $field => $val){
			if (!array_key_exists($field, $this->fields)){
				throw new Exception("Field " . $field . " does not exists");
			}
			$field_info=$this->fields[$field];
			if ($sql_where!=""){
				$sql_where = $sql_where . " AND ";
			}
			if (is_array($val) ) {
				if (count($val) > 0) {
					$sql_where = $sql_where . "`" . $field . "` IN (";
					$quoted_values = array();
					foreach ($val as $v) {
						$quoted_values[] = $this->quote($conn, $v, $field_info["type"]);
					}
					$sql_where = $sql_where . implode(', ', $quoted_values).')';
				}
			}
			else {
				$sql_where = $sql_where . "`" . $field . "` = ";
				$sql_where = $sql_where . $this->quote($conn, $val, $field_info["type"]);
			}
		}
		return $sql_where;
	}
	
	/**
	 * Generate UPDATE query then execute
	 * @param object $conn MySQL_PDO connection to server
	 * @param array $data associated array where key is field name
 	 * @param array $where associated array where key is field name using for filter
	 * @return Return FALSE if execution failure, on sucessfull case, return number of updated row
	**/
	public function update($conn, $data, $where){
		$sql_update="";
		foreach($data as $field => $val){
			if (!array_key_exists($field, $this->fields)){
				throw new Exception("Field " . $field . " does not exists");
			}
			$field_info=$this->fields[$field];
			if ($sql_update!=""){
				$sql_update = $sql_update . ", ";
			}
			$sql_update = $sql_update . "`" . $field . "` = ";
            $sql_update = $sql_update . $this->quote($conn, $val, $field_info["type"]);			
		}
		
		$sql_where=$this->getWhereClause($conn, $where);
		$sql="UPDATE `" . $this->table_name . "` SET " . $sql_update;
		if ($sql_where!=""){
			$sql = $sql . " WHERE " . $sql_where;
		}        
		return $conn->exec($sql);
	}
	
	/**
	* Update a record base on primary key
	* @param object $conn MySQL PDO connection
	* @param array $data associated field name => value that use when update record
	* @param mixed $id the primary key of updating record
	**/
	public function updateById($conn, $data, $id){
		if ($this->id==""){
			throw new Exception("No id field is defined");
		}
		return $this->update($conn, $data, array($this->id => $id));
	}
	
	/**
	 * Generate DELETE query then execute
	 * @param object $conn MySQL_PDO connection to server
 	 * @param array $where associated array where key is field name using for filter
	 * @return Return FALSE if execution failure, on sucessfull case, return number of deleted row
	**/
	public function delete($conn, $where){
		$sql_where = $this->getWhereClause($conn, $where);
		$sql="DELETE FROM `" . $this->table_name . "`";
		if ($sql_where!=""){
			$sql = $sql . " WHERE " . $sql_where;
		}
		return $conn->exec($sql);
	}
	
	/**
	* Delete a record base on primary key
	* @param object $conn MySQL PDO connection
	* @param mixed $id the primary key of updating record
	**/
	public function deleteById($conn, $id){
		if ($this->id==""){
			throw new Exception("No id field is defined");
		}
		return $this->delete($conn, array($this->id => $id));
	}
	
	/**
	* Build SELECT and execute query base on field list, condition, order and limit phrase
	* @param object $conn PDO connection object
	* @param array $what array of list of selected fields. If null or empty, select all fields
	* @param array $where associated array of where phrase which key is field name. Only = operator is supported
	* @param string @order order phrase appended after ORDER BY clause
	* @param string $limit limit phrase appended after LIMIT clause
	* @return array return array of result objects
	**/
	public function load($conn, $what=null, $where=null, $order="", $limit=""){
		$sql_where = $this->getWhereClause($conn, $where);
		if (is_array($what) && count($what)>0){
			$field_list="";
			for ($i=0; $i<count($what); $i++){
				if ($i>0){
					$field_list = $field_list . ", ";
				}
				$field_list = $field_list . "`" . $what[$i] . "`";
			}
			$sql="SELECT " . $field_list . " FROM `" . $this->table_name . "`";
		}
		else{
			$sql="SELECT * FROM `" . $this->table_name . "`";
		}
		if ($sql_where!=""){
			$sql = $sql . " WHERE " . $sql_where;
		}
		if ($order!=""){
			$sql = $sql . " ORDER BY " . $order;
		}
		if ($limit!=""){
			$sql = $sql . " LIMIT " . $limit;
		}

		return $this->exec_query($conn, $sql, null);
	}
	
	public function loadById($conn, $id, $what=null){
		if ($this->id==""){
			throw new Exception("No id field is defined");
		}
		$data=$this->load($conn, $what, array($this->id => $id));
		if (!is_array($data)){
			return FALSE;
		}
		if (count($data)==0){
			return FALSE;
		}
		return $data[0];
	}
	
	/**
	* Generic query execution function
	* @param object $conn PDO object connection
	* @param string $sql SQL to be executed
	* @param array $params parameters pass to execute or query method
	* @return object PDO statement of execution result 
	**/
	private function query($conn, $sql, $params=null){
		$stm=null;
		$status=FALSE;
		if (is_array($params) && count($params)>0){
			$stm=$conn->prepare($sql);                           
			$status=$stm->execute($params);
		}
		else{
			$status=$stm=$conn->query($sql);
		}
		if ($status===FALSE){            
			return FALSE;
		}
		else{
			return $stm;
		}
	}
	
	/**
	* Execute a query and do not need any returning data from database. Always return TRUE
	**/
	public function exec_no_query($conn, $sql, $params=null){
		if (is_array($params) && count($params)>0){
			$stm=$this->query($conn, $sql, $params);
		}
		else{
			$conn->exec($sql);
		}
		return TRUE;
	}
	
	/**
	* Execute a query and return a scalar result. Return FALSE if no data returned or execution failure
	**/
	public function exec_scalar($conn, $sql, $params=null){
		if (is_array($params) && count($params)>0){
			$stm=$this->query($conn, $sql, $params);
		}
		else{
			$stm=$conn->query($sql);
		}
		if ($stm!==FALSE){
			return $stm->fetchColumn();
		}
		else{
			return FALSE;
		}
	}
	
	/**
	 * Execute the query and return the list of result objects.
	 * @param string $sql Query string to execute
	 * @param array $param Parameter pass to query when prepare
	 * @param string $array_indexed Optional, if not empty, the return will be an associated array with key is array_indexed 
	 * @return array list of query result objects
	**/
	public function exec_query($conn, $sql, $params=null, $array_indexed="", $raw_return = false){
		if (is_array($params) && count($params)>0){
			$stm=$this->query($conn, $sql, $params);
		}
		else{
			$stm=$conn->query($sql);
		}
		if ($stm!==FALSE){
			if ($raw_return){
				return $stm;
			}
			if ($array_indexed===""){
				return $stm->fetchAll(PDO::FETCH_OBJ);
			}
			else{
				$data=array();
				while ($obj=$stm->fetch(PDO::FETCH_OBJ)){
					$data[$obj->$array_indexed]=$obj;
				}
				return $data;
			}
		}
		else{
			return FALSE;
		}
	}
	
	/**
	 * Execute the query and return the result object if sucessfully or null if not.
	 * @param string $sql Query string to execute
	 * @param array $param Parameter pass to query when prepare
	 * @return Object or null
	**/
	public function exec_object($conn, $sql, $params=null){
		$data=$this->exec_query($conn, $sql, $params);
		if (is_array($data) && count($data)>0){
			return $data[0];
		}
		return FALSE;
	}
}