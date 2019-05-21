<?
require_once("_BaseController.php");
use EInvoice\MySQL\Dal\Users as DbUsers;

class AuthorizedController extends BaseController
{
	private $_roles = null;
	public function beforeExecute($controller, $method, $func){
		$user = $this->getUser();
		if ($user->id == 0){
			return $this->errorResponse("Access denined", 403);
		}
		return true;
	}

	public function isInRole($role) {
        $user = $this->getUser();
        if ($user->id == 0) {
            return false;
        }
		if ($this->$_roles === null) {
			$db = DbUsers::getInstance();
            $roles = $db->getUserRoles($user->id);
			$this->$_roles = array();
			for ($i = 0; $i < count($roles); $i++){
				$this->$_roles[$roles[$i]->name] = $roles[$i]->id;
			}
        }
		return isset($this->$_roles[$role]);
	}
}