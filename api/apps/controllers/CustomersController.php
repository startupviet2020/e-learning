<?
require_once("_AuthorizedController.php");
use EInvoice\MySQL\Dal\Customers as DbCustomers;

class CustomersController extends AuthorizedController
{
    public function indexGet(){
        $user = $this->getUser();
        $page = (int)$this->getQuery("page", 0);
        $size = (int)$this->getQuery("size", 20);
        $keyword = $this->getQuery("s", "");

        $cid = $user->company;
        $params = array($cid);
        $cond = "";
        if ($keyword != "") {
            $cond = "(name LIKE ? OR description LIKE ?)";
            $params[] = "%" . $keyword . "%";
            $params[] = "%" . $keyword . "%";
        }

        $sql = "SELECT * FROM customer WHERE cid = ?";
        if ($cond != ""){
            $sql = $sql . " AND " . $cond;
        }
        $db = DbCustomers::getInstance();
        return $this->successResponse($db->getCustomers($sql, $params));
    }

    public function indexPost(){
        $user = $this->getUser();
        $cid = $user->company;
        $data = $this->getJson();
        $id = $data["id"];
        unset($data["id"]);
        $data["updated"] = time();
        $data["updated_by"] = $this->getUser()->id;
        $db = DbCustomers::getInstance();
        if ($id > 0) {
            $db->updateCustomer($id, $data);
        } else {
            $data["cid"] = $cid;
            $data["created"] = time();
            $data["created_by"] = $this->getUser()->id;
            $id = $db->addCustomer($data);
        }
        return $this->successResponse($id);
    }

    public function indexDelete(){
        $id = $this->getJson("id", 0);
        if ($id <= 0){
            return $this->errorResponse("Invalid customer id");
        }
        $db = DbCustomers::getInstance();
        $db->deleteCustomer($id);
        return $this->successResponse($id);
    }

    public function indexPut(){
        
    }
}