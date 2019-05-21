<?
namespace EInvoice\MySQL\Dal;
use HV\Core\Db as Db;
use EInvoice\MySQL\Db\Products as DbProducts;

class Products extends Db\Dal{
	public function addProduct($data){
		$entity = DbProducts::getInstance();
		$conn = $this->getDb($entity);
		return $entity->insert($conn, $data);
  }

  public function updateProduct($pid, $data){
		$entity = DbProducts::getInstance();
		$conn = $this->getDb($entity);
		return $entity->updateById($conn, $data, $pid);
  }

  public function getProductById($pid){
		$entity = DbProducts::getInstance();
		$conn = $this->getDb($entity);
		return $entity->loadById($conn, $pid);
  }

  public function getProducts($sql, $params = array()){
    $entity = DbProducts::getInstance();
    $conn = $this->getDb($entity);
    return $entity->exec_query($conn, $sql, $params);
  }

  public function deleteProduct($pid){
    $entity = DbProducts::getInstance();
    $conn = $this->getDb($entity);
    return $entity->deleteById($conn, $pid);
  }
}