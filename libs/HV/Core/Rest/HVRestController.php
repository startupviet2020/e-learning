<?
namespace HV\Core\Rest;
class HVRestController{
	protected $_app = null;
	public function __construct(){
		
	}

    public function setApp($app){
        $this->_app = $app;
    }

    public function getServer($key = null, $default = null){
        return $this->_app->getServer($key, $default);
    }

	public function getMethod(){
        return $this->_app->getMethod();
    }

    public function getQuery($key, $def = null){
    	return $this->_app->getQuery($key, $def);
    }

	public function getPost($key, $def = null){
    	return $this->_app->getPost($key, $def);
    }

    public function getJson($key=null, $default=null){
        return $this->_app->getJson($key, $default);
    }

	public function execute($module, $controller, $method, $func, $params = null){
		return $this->_app->execute($module, $controller, $method, $func, $params);
	}
}