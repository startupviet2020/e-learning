<?
require_once("_BaseController.php");

use HV\Core\Utils\HttpClient;
use EInvoice\MySQL\Dal\Users as DbUsers;

class AuthController extends BaseController
{
    private function buildAuthResponse($user) {
        $db = DbUsers::getInstance();
        $companies = $db->getUserCompanies($user["uid"]);
        $found = false;
        for ($i = 0; $i < count($companies); $i++) {
            if ($companies[$i]->id == $user["company"]) {
                $found = true;
                break;
            }
        }
        $user["companies"] = $companies;
        if (!$found) {
            $user["company"] = 0;
        }
        return $this->successResponse($user);
    }

    public function indexGet(){
        if (!$this->isAuthenticated()) {
            return $this->unauthorizedResponse();
        }
        $user = $this->getUser();
        return $this->buildAuthResponse(array(
            "uid" => $user->id,
            "name" => $user->name,
            "avatar" => $user->avatar,
            "status" => $user->status,
            "company" => $user->company
        ));
    }

    public function indexDelete(){
        if (!$this->isAuthenticated()) {
            return $this->unauthorizedResponse();
        }
        $this->_app->removeSession();
        return $this->successResponse(array());
    }

    public function oauthPost(){
        $user = $this->getUser();
        if ($user->id > 0) {
            return $this->errorResponse('Already login');
        }

        $accessToken = $this->getJson("access_token", "");
        if ($accessToken === "") {
            return $this->errorResponse("Invalid access token");
        }

        $fb = new \Facebook\Facebook([
            'app_id'                => FACEBOOK_APP_ID,
            'app_secret'            => FACEBOOK_APP_SECRET,
            'default_graph_version' => 'v2.8',
            'default_access_token'  => $accessToken
        ]);
        try {
            $response    = $fb->get('/me?fields=id,name,email,first_name,last_name,middle_name');
            $graphObject = $response->getGraphUser();
            $profile     = $graphObject->asArray();
        } catch (Exception $e) {
            $profile = false;
        }

        if ($profile === false) {
            return $this->errorResponse("Unable to authenticate with Facebook");
        }

        if ( !isset($profile['email']) || $profile['email'] == '') {
            return $this->errorResponse('Cannot use your facebook account to login.');
        }

        $email = $profile['email'];
        return $this->successResponse($profile);
    }

    public function accountkitPost(){
        $user = $this->getUser();
        if ($user->id > 0) {
            return $this->errorResponse("Already login");
        }

        $code = $this->getJson("code", null);
        if (!$code) {
            $accessToken = $this->getJson("access_token", null);
        } else {
            $accessToken = $this->_exchangeToAccessToken($code);
        }

        if (!$accessToken) {
            return $this->errorResponse('Invalid access token');
        }

        $acInfo = $this->_getAccountKitInfo($accessToken);
        if (!$acInfo) {
            return $this->errorResponse('Cannot fetch data from AccountKit');
        }

        if (!isset($acInfo->phone)) {
            $type = 'email';
        } else {
            $type = 'phone';
        }

        $db = DbUsers::getInstance();
        if ($type == 'phone') {
            $phone = $acInfo->phone->number;
            $phone = ltrim($phone, "+");
            $email = "";
            $user = $db->getUserByPhone($phone);
        } else {
            $phone = "";
            $email = $acInfo->email->address;
            $user = $db->getUserByEmail($email);
        }

        if (!$user) {
            $user = array("email" => $email, "phone" => $phone, "created" => time(), "updated" => time(), "created_by" => 0, "updated_by" => 0, "status" => 0);
            $uid = $db->addUser($user);
            $user = (object) $user;

        } else {
            $uid = $user->id;
        }

        $authKey = $this->_app->newSession($uid);

        return $this->buildAuthResponse(array(
            "uid" => $uid,
            "name" => $user->name,
            "avatar" => $user->avatar,
            "auth" => $authKey,
            "status" => $user->status,
            "company" => $user->company
        ));
    }

    private function _getAccountKitInfo($accessToken)
    {
        $appsecret_proof= hash_hmac('sha256', $accessToken, FACEBOOK_ACCOUNT_KIT_SECRET);
        $response = HttpClient::get(
            FACEBOOK_ACCOUNT_KIT_URL . '/me',
            array(
                'access_token' => $accessToken,
                'appsecret_proof' => $appsecret_proof
            )
        );
        if ($response->isError()) {
            return null;
        }
        return json_decode($response->body);
    }

    private function _exchangeToAccessToken($code)
    {
        $token    = 'AA|' . FACEBOOK_APP_ID . '|' . FACEBOOK_ACCOUNT_KIT_SECRET;
        $response = HttpClient::get(
            FACEBOOK_ACCOUNT_KIT_URL . '/access_token',
            array(
                'grant_type'   => 'authorization_code',
                'code'         => $code,
                'access_token' => $token,
            )
        );
        if ($response->isError()) {
            return null;
        }
        $data = json_decode($response->body);
        return $data->access_token;
    }
}