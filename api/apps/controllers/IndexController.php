<?
require_once("_BaseController.php");
use EInvoice\MySQL\Dal\Users;

class IndexController extends BaseController
{
    public function indexGet(){
        $db = Users::getInstance();
        return $this->successResponse($db->getUserByPhone("1234567890"));
    }
}