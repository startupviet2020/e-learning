<?
namespace HV\Core\Rest;
class HVRestErrorResponse extends HVRestResponse{
	public $data = null;
	public $info = null;
	private $_send_info = false;
	function __construct($status, $data, $info = null, $send_info = false){
		parent::__construct();
		$this->_is_success = false;
		$this->_status_code = $status;
		$this->data = $data;
		$this->info = $info;
		$this->_send_info = $send_info;
	}

	public function toJson(){
		return json_encode(array("status" => $this->getStatus(), "data" => $this->data, "info" => $this->_send_info ? $this->info : null), JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
	}

	public function sendResponse(){
		header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
		header("Access-Control-Allow-Headers: Authorization, Content-Type, X-Requested-With, Accept, Origin");
		header('Content-type: application/json; charset=utf-8');
		$this->sendStatusCode();
        echo $this->toJson();
	}
}