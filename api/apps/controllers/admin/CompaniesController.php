<?
require_once(__DIR__ . "/../_AuthorizedController.php");

use EInvoice\MySQL\Dal\Companies as DbCompanies;

class CompaniesController extends AuthorizedController
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

        $sql = "SELECT * FROM company";
        $db = DbCompanies::getInstance();
        return $this->successResponse($db->getCompanies($sql, $params));
    }

    public function indexPost() {
        $data = $this->getJson();
        $id = $data["id"];
        unset($data["id"]);
        $data["updated"] = time();
        $data["updated_by"] = $this->getUser()->id;
        $db = DbCompanies::getInstance();
        if ($id > 0) {
            $db->updateCompany($id, $data);
        } else {
            $data["status"] = 1;
            $data["created"] = time();
            $data["created_by"] = $this->getUser()->id;
            $id = $db->addCompany($data);
            $data["id"] = $id;
        }
        return $this->successResponse($data);
    }

    public function indexDelete() {

    }
}