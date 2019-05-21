<?
require_once(__DIR__ . "/../_AuthorizedController.php");

use EInvoice\MySQL\Dal\Users as DbUsers;

class UsersController extends AuthorizedController
{
    public function beforeExecute($controller, $method, $func) {
      if (!$this->isInRole("administrator")) {
        return $this->errorResponse("Access denined", 403);
      }
      return true;
    }
  
    public function indexGet() {
        $page = (int)$this->getQuery("page", 0);
        $size = (int)$this->getQuery("size", 20);
        $status = (int)$this->getQuery("status", -1);
        $keyword = $this->getQuery("s", "");

        $params = array();
        $cond = "";
        if ($status > -1) {
            $cond = "status = ?";
            $params[] = $status;
        }
        if ($keyword != "") {
            if ($cond != "") {
                $cond = $cond . " AND ";
            }
            $cond = $cond . "(name LIKE ? OR address LIKE ?)";
            $params[] = "%" . $keyword . "%";
            $params[] = "%" . $keyword . "%";
        }

        $sql = "SELECT * FROM user";
        $db = DbUsers::getInstance();
        return $this->successResponse($db->getUsers($sql, $params));
    }

    public function indexPost() {
        $uid = $this->getJson("uid", 0);
        $field = $this->getJson("field", "");
        $value = $this->getJson("value", null);
        if ($uid <= 0) {
            return $this->errorResponse("Invalid user id");
        }
        if ($field == "") {
            return $this->errorResponse("Invalid updating field");
        }
        $data = array(
            $field => $value,
            "updated" => time(),
            "updated_by" => $this->getUser()->id
        );
        $db = DbUsers::getInstance();
        $db->updateUser($uid, $data);
        return $this->successResponse($data);
    }

    public function indexDelete() {

    }
}