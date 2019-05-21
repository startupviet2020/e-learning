<?
require_once("_AuthorizedController.php");

use EInvoice\MySQL\Dal\Users as DbUsers;

class UsersController extends AuthorizedController
{
    public function meGet(){
        $uid = $this->getUser()->id;
        $db = DbUsers::getInstance();
        $user = $db->getUserById($uid);
        return $this->successResponse(array(
            "uid" => $user->id,
            "phone" => $user->phone,
            "name" => $user->name,
            "avatar" => $user->avatar,
            "status" => $user->status,
            "email" => $user->email,
            "address" => $user->address
        ));
    }

    public function mePost(){
        $uid = $this->getUser()->id;
        $db = DbUsers::getInstance();
        $data = $this->getJson();

        if ($data["phone"] == "") {
            return $this->errorResponse("Invalid phone number");
        }
        if ($data["name"] == "") {
            return $this->errorResponse("Invalid name");
        }
        if ($data["email"] == "") {
            return $this->errorResponse("Invalid email");
        }
        $data["updated"] = time();
        $data["updated_by"] = $uid;
        $db->updateUser($uid, $data);
        return $this->successResponse($data);
    }
}