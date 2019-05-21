<?
require_once("_AuthorizedController.php");
use EInvoice\MySQL\Dal\Users as DbUsers;
use EInvoice\MySQL\Dal\Companies as DbCompanies;
class CompaniesController extends AuthorizedController
{
    public function indexGet() {

    }

    public function indexPost() {
        $user = $this->getUser();
        $cid = $user->company;
        $data = $this->getJson();
        $id = $data["id"];
        if ($id != $cid) {
            return $this->errorResponse("You have no permission to update this company");
        }
        unset($data["id"]);
        $data["updated"] = time();
        $data["updated_by"] = $this->getUser()->id;
        $db = DbCompanies::getInstance();
        if ($id > 0) {
            $db->updateCompany($id, $data);
        } else {
            $data["created"] = time();
            $data["created_by"] = $this->getUser()->id;
            $id = $db->addCompany($data);
        }
        return $this->successResponse($id);
    }

    public function activeGet() {
        $user = $this->getUser();
        $cid = $user->company;
        if ($cid <= 0) {
            return $this->errorResponse("No active company");
        }
        $db = DbCompanies::getInstance();
        $company = $db->getCompanyById($cid);
        if (!$company) {
            return $this->errorResponse("Company not found");
        }
        return $this->successResponse($company);
    }

    public function activePost() {
        $cid = (int)$this->getJson("cid", 0);
        if ($cid <= 0) {
            return $this->errorResponse("Invalid company Id");
        }
        $user = $this->getUser();
        $db = DbUsers::getInstance();
        $companies = $db->getUserCompanies($user->id);
        for ($i = 0; $i < count($companies); $i++) {
            if ($companies[$i]->id == $cid) {
                $db->updateUser($user->id, array("company" => $cid));
                return $this->successResponse($cid);
            }
        }
        return $this->errorResponse("You have no permission to use this company");
    }
}