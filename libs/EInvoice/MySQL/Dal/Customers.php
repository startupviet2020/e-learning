<?
namespace EInvoice\MySQL\Dal;
use HV\Core\Db as Db;
use EInvoice\MySQL\Db\Customers as DbCustomers;

class Customers extends Db\Dal{
	public function addCustomer($data){
		$entity = DbCustomers::getInstance();
		$conn = $this->getDb($entity);
		return $entity->insert($conn, $data);
  }

  public function updateCustomer($cid, $data){
		$entity = DbCustomers::getInstance();
		$conn = $this->getDb($entity);
		return $entity->updateById($conn, $data, $cid);
  }

  public function getCustomerById($cid){
		$entity = DbCustomers::getInstance();
		$conn = $this->getDb($entity);
		return $entity->loadById($conn, $cid);
  }

  public function getCustomers($sql, $params = array()){
    $entity = DbCustomers::getInstance();
    $conn = $this->getDb($entity);
    return $entity->exec_query($conn, $sql, $params);
  }

  public function deleteCustomer($cid){
    $entity = DbCustomers::getInstance();
    $conn = $this->getDb($entity);
    return $entity->deleteById($conn, $cid);
  }
}