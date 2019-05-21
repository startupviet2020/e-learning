<?
namespace HV\Core\Rest;
use Exception;
class HVRest500Exception extends Exception{
	public function __construct($message, Exception $previous = null) {
        parent::__construct($message, 500, $previous);
    }
}