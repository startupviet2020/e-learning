<?
namespace HV\Core\Rest;
class HVRestPreflighResponse extends HVRestResponse{
	public function toJson(){
		return "";
	}
	public function sendResponse(){
		header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Authorization, Content-Type, X-Requested-With, Accept, Origin");
		header("Content-type: text/html; charset=utf-8");
        echo $this->toJson();
	}	
}