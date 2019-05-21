<?
require_once(__DIR__ . "/../_BaseController.php");
use EInvoice\MySQL\Dal\Users;

class ReportsController extends BaseController
{
    public function indexGet(){
        return $this->successResponse("Hello World");
    }
}