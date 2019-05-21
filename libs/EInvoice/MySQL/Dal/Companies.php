<?
namespace EInvoice\MySQL\Dal;
use HV\Core\Db as Db;
use EInvoice\MySQL\Db\Companies as DbCompanies;

class Companies extends Db\Dal{
	public function addCompany($data){
		$entity = DbCompanies::getInstance();
		$conn = $this->getDb($entity);
		return $entity->insert($conn, $data);
	}

	public function getCompanyById($uid){
		$entity = DbCompanies::getInstance();
		$conn = $this->getDb($entity);
		return $entity->loadById($conn, $uid);
    }
    
    public function updateCompany($cid, $data){
		$entity = DbCompanies::getInstance();
		$conn = $this->getDb($entity);
		return $entity->updateById($conn, $data, $cid);	
    }
    
    public function getCompanies($sql, $params = array()){
        $entity = DbCompanies::getInstance();
        $conn = $this->getDb($entity);
        return $entity->exec_query($conn, $sql, $params);
    }
}