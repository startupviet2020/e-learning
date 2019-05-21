<?
namespace HV\Core\Rest;
use Exception;
abstract class HVRestApp{
	private $_router = null;
	private $_config = null;
    private $_JSON = array();

	public function __construct($config){
        $def_config = array(
                'default_controller'    => 'index',
                'default_action'        => 'index',
                'controller_path'       => ''
            );

        if (is_array($config)){
            $this->_config = array_merge($def_config, $config);    
        }
		else{
            $this->_config = $def_config;
        }
        
		$this->_router = new HVRestRouter();
	}

	public function getConfig($key, $def = null){
		if (isset($this->_config[$key])){
			return $this->_config[$key];
		}
		return $def;
	}

    private function parseFormData(){
        parse_str(file_get_contents("php://input"), $_POST);
    }

    public function parseJsonData(){
        $this->_JSON = @json_decode(($stream = fopen('php://input', 'r')) !== false ? stream_get_contents($stream) : "{}", true);
    }

	private function sendResponse(HVRestResponse $response){
        $response->sendResponse();
	}

    abstract public function getAuthKey();
    abstract public function authenticate();
    abstract public function newSession($uid);
    abstract public function removeSession();
    abstract public function getAnonymousUser();

	public function dispatch(){
		try{
			$request_method = $this->getServer('REQUEST_METHOD');
            $x_request_method = $this->getServer('HTTP_X_HTTP_METHOD', '');
            if ($x_request_method != "" && $request_method == 'POST') {
                if ($x_request_method == 'DELETE') {
                    $request_method = 'DELETE';
                } else if ($x_request_method == 'PUT') {
                    $request_method = 'PUT';
                } else {
                    throw new Exception("Unexpected Header");
                }
            }

            if ($request_method == "OPTIONS"){
                $response = new HVRestPreflighResponse();
                $this->sendResponse($response);
                return;
            }

            if ($request_method != "GET" && $request_method != "POST" && $request_method != "PUT" && $request_method != "DELETE"){
                throw new Exception("Unexpected verb " . $request_method);
            }

            $contentType = strtolower($this->getServer("CONTENT_TYPE"));
            $jsonContentType = $contentType === "application/json";

            if (($request_method === "PUT" || $request_method === "DELETE") && !$jsonContentType){
                $this->parseFormData();
            } else {
                $this->parseJsonData();
            }

			$uri = strtolower($_SERVER['REQUEST_URI']);
			$uri = str_replace("http://127.0.0.1", "", $uri);
			$uri = str_replace("http://api.shlx.vn", "", $uri);
	        //trim leading slash
	        $uri = trim($uri, '/');
	        //remove GET params
	        $uri = explode('?', $uri);        
	        $uri = ($uri !== FALSE) ? $uri[0] : '';       
	        
            $uri = str_replace(".json", '', $uri);

            $route = $this->_router->match($uri);
            $module = "";
	        $controller = $this->getConfig("default_controller", "index");
			$action = $this->getConfig("default_action", "index");
			if ($route !== FALSE){
                if ($route[1] !== ""){
                    $module = $route[1];
                }
                if ($route[2] !== ""){
                    $controller = $route[2];    
                }
				if ($route[3] !== ""){
                    $action = $route[3];
                }
				
				$params = $route[4];
			}
			else{
				$params = array();
			}

			$response = $this->execute($module, $controller, $request_method, $action, $params);
            if ($response == null){
                $response = new HVRestSuccessResponse(array("info" => "No return data"));
            }
            $this->sendResponse($response);
		}
        catch(HVRest404Exception $exp){
            error_log($exp->getMessage());
            $response = new HVRestErrorResponse(404, "Resource not found");
            $this->sendResponse($response);
        }
        catch(HVRest500Exception $exp){
        	error_log($exp->getMessage());
            $response = new HVRestErrorResponse(500, "Server error");
            $this->sendResponse($response);
        }
        catch(Exception $exp){
        	error_log($exp->getMessage());
            $response = new HVRestErrorResponse(500, "Server error");
            $this->sendResponse($response);
        }
	}

	public function addRoute($pattern, $controller = "index", $action = "index", $suffix = ""){
		$this->_router->addRoute($pattern, $controller, $action, $suffix);
	}

	public function execute($module, $controller, $method, $func, $params = null){
        $method = ucfirst(strtolower($method));
        $controllerClass = ucfirst($controller) . 'Controller';
        if ($module === ""){
            $controllerFile = $this->getConfig("controller_path") . $controllerClass . '.php';
        } else {
            $controllerFile = $this->getConfig("controller_path") . $module . "/" . $controllerClass . '.php';
        }
        if (!preg_match('#^[A-Za-z0-9_-]+$#', $controller) || ! file_exists($controllerFile)){
            throw new HVRest404Exception("Controller not found: " . $controllerFile);
        }                                                

        require($controllerFile);
        $ctrl = new $controllerClass();
        $ctrl->setApp($this);

        $function = $func . $method;
        if (!preg_match('#^[A-Za-z_][A-Za-z0-9_-]*$#', $function) || !is_callable(array($ctrl, $function))){
            throw new HVRest404Exception("Function not found: " . $function);
        }
        
        if (is_callable(array($ctrl, "init"))){
            $ctrl->init();
        }

        if (is_callable(array($ctrl, "beforeExecute"))){
            $result = $ctrl->beforeExecute($controller, $method, $func);
            if ($result !== true){
                return $result;
            }
        }

        if ($params === null){
        	$params = array();
        }
        
        foreach($params as $name => $value){
            if (!isset($_GET[$name])){
            	$_GET[$name] = $value;
            }
        }
        switch (count($params)){
        	case 0:
        		return $ctrl->$function();
        	case 1:
        		return $ctrl->$function($params[0]);
        	case 2:
        		return $ctrl->$function($params[0], $params[1]);
        	case 3:
        		return $ctrl->$function($params[0], $params[1], $params[2]);
        	case 4:
        		return $ctrl->$function($params[0], $params[1], $params[2], $params[3]);
        	case 5:
        		return $ctrl->$function($params[0], $params[1], $params[2], $params[3], $params[4]);
        	case 6:
        		return $ctrl->$function($params[0], $params[1], $params[2], $params[3], $params[4], $params[5]);
        	case 7:
        		return $ctrl->$function($params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6]);
        	case 8:
        		return $ctrl->$function($params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6], $params[7]);
        	case 9:
        		return $ctrl->$function($params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6], $params[7], $params[8]);
        	case 10:
        		return $ctrl->$function($params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6], $params[7], $params[8], $params[9]);
        	default:
        		return call_user_func_array(array($ctrl, $function), $params);
        }
	}

	public function getServer($key = null, $default = null){
        if (null === $key) {
            return $_SERVER;
        }    
        return (isset($_SERVER[$key])) ? $_SERVER[$key] : $default;
    }

	public function getMethod(){
        return $this->getServer('REQUEST_METHOD');
    }

    public function getQuery($key = null, $default = null){
        if (null === $key) {
            return $_GET;
        }

        return (isset($_GET[$key])) ? $_GET[$key] : $default;
    }

    public function getPost($key = null, $default = null){
        if (null === $key) {
            return $_POST;
        }

        return (isset($_POST[$key])) ? $_POST[$key] : $default;
    }

    public function getJson($key, $default=null) {
        if (null === $key) {
            return $this->_JSON;
        }
        return $this->_JSON[$key] ?? $default;
    }

    public function getClientIP(){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }
        return $ip;
    }
}
