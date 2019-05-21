<?
namespace HV\Core\Rest;
use Exception;
class HVRest404Exception extends Exception{
	public function __construct($message, Exception $previous = null) {
        parent::__construct($message, 404, $previous);
    }
}