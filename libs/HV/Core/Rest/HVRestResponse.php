<?
namespace HV\Core\Rest;
abstract class HVRestResponse{
	protected $_is_success = false;
	protected $_status_code = 0;
	protected $_http_status = null;

	public function __construct(){
		$this->_http_status = array(
			201 => 'Created',
			400	=> "Bad Request",
			401 => "Unauthorized",
			403 => "Forbidden",
			404 => "Not Found",
			405 => 'Method Not Allowed',
            429 => 'Too Many Requests',
			500 => "Internal Server Error",
			501 => "Not Implemented"
			);
	}

	protected function sendStatusCode(){
		if (isset($this->_http_status[$this->_status_code])){
			header('HTTP/1.1 ' . $this->_status_code . ' ' . $this->_http_status[$this->_status_code]);
		}
	}

	public function isSuccess(){
		return $this->_is_success;
	}

	public function getStatus(){
		return $this->_status_code;
	}

	abstract public function toJson();
	abstract public function sendResponse();
}