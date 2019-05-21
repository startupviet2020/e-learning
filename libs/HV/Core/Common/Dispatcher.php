<?
namespace HV\Core\Common;
use \Exception;
class Dispatcher {
    protected $_listeners = array();
 
    public function subscribe($event, $listener, $callback){
    	$this->_listeners[$event][spl_object_hash($listener)] = array("listener" => $listener, "callback" => $callback);
    	return $this;
    }
 
    public function unsubscribe($event, $listener){
    	unset($this->_listeners[$event][spl_object_hash($listener)]);
    	return $this;
    }
 
    public function publish(){ 
    	$args = func_get_args();
    	if (count($args) == 0){
    		throw new Exception("Missing event name parameter");
    	}
    	$event = array_shift($args);
    	if (isset($this->_listeners[$event])){
    		foreach($this->_listeners[$event] as $hash_key => $listener){
    			call_user_func_array(array($listener["listener"], $listener["callback"]), $args);
    		}
    	}
    	return $this;
    }
}