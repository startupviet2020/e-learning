<?
namespace HV\Core\Rest;
class HVRestRouter{
	private $_routes = array();

	public function addRoute($pattern, $controller = "index", $action = "index"){
		$this->_routes[] = array($pattern, $controller, $action);
	}

	public function match($uri){
		if ($uri == ""){
			return FALSE;
		}

		/*
		foreach ($this->_routes as $route) {
			$pattern = $route[0];
			if (preg_match('#^/?' . $pattern . '/?$#', $uri, $matches)){
				if (count($matches) > 1){
					array_shift($matches);
				}
				$route[] = $matches;
				return $route;
			}
		}
		*/

		$segments = explode('/', $uri);
		$module = $segments[0];
		if (strlen($module) > 1 && $module[0] === "~" && $module[1] !== "~") {
			$module = ltrim($module, "~");
			array_shift($segments);
		} else {
			$module = "";
		}
		$controller = $segments[0];
		if (count($segments) > 1){
			$action = $segments[1];
		} 
		else{
			$action = "";
		}
		if (count($segments) > 2){
			$params = array_slice($segments, 2);
		}
		else{
			$params = array();
		}
		return array($uri, $module, $controller, $action, $params);
	}
}