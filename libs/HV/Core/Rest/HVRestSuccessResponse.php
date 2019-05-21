<?
namespace HV\Core\Rest;
class HVRestSuccessResponse extends HVRestResponse{
	public $data = null;
	private $numeric_check = true;
	public function __construct($data, $status = 0, $numeric_check = true){
		parent::__construct();
		$this->_is_success = true;
		$this->_status_code = $status;		
		$this->data = $data;
		$this->numeric_check = $numeric_check;
	}

	public function toJson(){
            if ($this->data == 'empty'){
                return null;
            }
			if ($this->numeric_check){
				return json_encode($this->data, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
			}
			else{
				return json_encode($this->data, JSON_UNESCAPED_UNICODE);
			}
            
	}

	public function sendResponse(){
		header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
		header("Access-Control-Allow-Headers: Authorization, Content-Type, X-Requested-With, Accept, Origin");
		header("Access-Control-Expose-Headers: TUExtInfo");
		header("Content-type: application/json; charset=utf-8");
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        echo $this->toJson();
	}
}