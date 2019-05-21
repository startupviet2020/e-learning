<?php                        
use HV\Core\Rest\HVRestApp;
use HV\Core\Rest\HVRestSession;
use EInvoice\MySQL\Dal\Users as DbUsers;
use EInvoice\MySQL\Dal\Sessions as DbSessions;
use HV\Core\Utils\HVLog;

class InvoiceApp extends HVRestApp{
    private $lib_session;
    private $_user = null;

    public function __construct($config){
        parent::__construct($config);
        $this->lib_session = HVRestSession::getInstance();
        $this->lib_session->setStorage(DbSessions::getInstance());
    }

    public function getUser(){
        if ($this->_user == null){
            $this->_user = $this->getAnonymousUser();
        }
        return $this->_user;
    }

    public function getAuthKey(){
        if ($this->getQuery("auth", "") !== ""){
            return $this->getQuery("auth");
        }
        if (function_exists('apache_request_headers')){
            $headers = apache_request_headers();
            if (isset($headers['Authorization'])){
                return $headers['Authorization'];
            }
        }
        return $this->getServer("HTTP_AUTHORIZATION");
    }

    /*
    Authenticate user who make the request
    */
    public function authenticate(){
        $auth_key = $this->getAuthKey();
        if ($auth_key == ""){
            return false;
        }

        $sess = $this->lib_session;
        $u = $sess->loadSession($auth_key);

        if ($u === false){
            return false;
        }

        $db = DbUsers::getInstance();
        $profile = $db->getUserById($u->uid);

        if ($profile !== false){
            $user = $this->getAnonymousUser();
            $user->id = $u->uid;
            $user->auth = $auth_key;
            $user->name = $profile->name;
            $user->email = $profile->email;
            //$user->signup_type = $profile->signup_type;
            $user->created = $profile->created;
            $user->status = $profile->status;
            $user->company = $profile->company;
            $this->_user = $user;
        }
        else{
            return false;
        }
        
        return true;
    }

    public function newSession($uid){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }
        $sid = HVRestSession::makeKey();
        $data = array(
            'sid' => $sid,
            'uid' => $uid,
            'hostname' => $ip, 
            'timestamp' => time()
        );
                
        $this->lib_session->addSession($data);

        $db = DbUsers::getInstance();
        $profile = $db->getUserById($uid);

        if ($profile !== false){
            $user = $this->getAnonymousUser();
            $user->auth = $sid;
            $user->id = $uid;
            $user->name = $profile->name;
            $user->email = $profile->email;
            //$user->signup_type = $profile->signup_type;
            $user->created = $profile->created;
            $user->status = $profile->status;
            $user->company = $profile->company;
            $this->_user = $user;
        }

        return $sid;
    }

    /*
    Remove session (after logout)
    */
    public function removeSession(){
        $sid = $this->getAuthKey();
        
        if ($sid !== ""){
            $this->lib_session->deleteSession($sid);
        }
        $this->_user = $this->getAnonymousUser();
    }

    public function getAnonymousUser(){
        $user = new stdClass();
        $user->auth = "";
        $user->id = 0;
        $user->avatar = '';
        $user->name = '';
        $user->email = '';     
        $user->created = 0;
        $user->status = -1;
        $user->company = 0;
        return $user;
    }
}
