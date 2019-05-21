<?
namespace EInvoice\MySQL\Dal;
use HV\Core\Db as Db;
use EInvoice\MySQL\Db\Users as DbUsers;
use EInvoice\MySQL\Db\UserRoles as DbUserRoles;

class Users extends Db\Dal{
	public function addUser($data){
		$entity = DbUsers::getInstance();
		$conn = $this->getDb($entity);
		return $entity->insert($conn, $data);
	}

	public function getUserById($uid){
		$entity = DbUsers::getInstance();
		$conn = $this->getDb($entity);
		return $entity->loadById($conn, $uid);
    }
    
    public function updateUser($uid, $data){
		$entity = DbUsers::getInstance();
		$conn = $this->getDb($entity);
		return $entity->updateById($conn, $data, $uid);	
    }

    public function getUserByPhone($phone){
        $entity = DbUsers::getInstance();
		$conn = $this->getDb($entity);
        $sql = "SELECT * FROM user WHERE phone = ?";
		return $entity->exec_object($conn, $sql, array($phone));	
	}
	
	public function getUserByEmail($email){
        $entity = DbUsers::getInstance();
		$conn = $this->getDb($entity);
        $sql = "SELECT * FROM user WHERE email = ?";
		return $entity->exec_object($conn, $sql, array($email));
	}
	
	public function getUserRoles($uid) {
		$entity = DbUsers::getInstance();
		$conn = $this->getDb($entity);
        $sql = "SELECT r.id, r.name FROM `user_role` u INNER JOIN `role` r ON u.rid = r.id WHERE u.uid = ?";
		return $entity->exec_query($conn, $sql, array($uid));
    }
	
	public function getUserCompanies($uid) {
		$entity = DbUsers::getInstance();
		$conn = $this->getDb($entity);
        $sql = "SELECT c.id, c.name FROM `company` c INNER JOIN `user_company` uc ON uc.cid = c.id WHERE uc.uid = ?";
		return $entity->exec_query($conn, $sql, array($uid));
	}
	
    public function getUsers($sql, $params = array()){
        $entity = DbUsers::getInstance();
        $conn = $this->getDb($entity);
        return $entity->exec_query($conn, $sql, $params);
    }
}