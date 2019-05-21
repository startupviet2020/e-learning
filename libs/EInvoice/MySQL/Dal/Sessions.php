<?
namespace EInvoice\MySQL\Dal;
use HV\Core\Db as Db;
use EInvoice\MySQL\Db\Sessions as DbSessions;

class Sessions extends Db\Dal{
	public function addSession($data){
		$entity = DbSessions::getInstance();
		$conn = $this->getDb($entity);
		return $entity->insert($conn, $data);
	}

	public function loadSession($sid){
		$entity = DbSessions::getInstance();
		$conn = $this->getDb($entity);
		return $entity->loadById($conn, $sid);
    }
    
  public function updateSession($sid, $data){
		$entity = DbSessions::getInstance();
		$conn = $this->getDb($entity);
		return $entity->updateById($conn, $data, $sid);	
  }

  public function  deleteSession($sid){
    $entity = DbSessions::getInstance();
		$conn = $this->getDb($entity);
		return $entity->deleteById($conn, $sid);
  }
}