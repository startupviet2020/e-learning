<?
use HV\Core\Rest\HVRestController;
use HV\Core\Rest\HVRestSuccessResponse;
use HV\Core\Rest\HVRestErrorResponse;
use HV\Core\Utils\DBFactory;
use Config\AppConfig;
use EInvoice\MySQL\Dal\Exams;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class BaseController extends HVRestController
{
    private $logger = null;
	protected function getDb($key){
		return DBFactory::getInstance()->getDb($key);
	}

    protected function getUser(){
    	return $this->_app->getUser();
    }

    protected function getAnonymousUser(){
        return $this->_app->getAnonymousUser();
    }

    protected function isAuthenticated(){
    	$user = $this->getUser();
    	return $user->id > 0;
    }

	protected function _getUniqueCode($length = NULL)
	{
		$hash = md5(uniqid(rand(), true));
		if ($length){
			$hash = substr($hash, 0, $length);
		}
		return $hash;
	}

	protected function _standardizeName($str){
        $str = preg_replace('/\s\s+/', ' ', $str);
        $str = trim($str,' _-');
        $str = ucwords(strtolower($str));
        return $str;
	}

    protected function successResponse($data = null, $status = 0){
    	if ($data === null){
    		$data = array("status" => 1);
    	}
    	return new HVRestSuccessResponse($data, $status);
    }
	
	protected function successResponse2($data = null, $status = 0){
    	if ($data === null){
    		$data = array("status" => 1);
    	}
    	return new HVRestSuccessResponse($data, $status, false);
    }
	
    protected function errorResponse($info, $status = 400){
    	return new HVRestErrorResponse($status, $info);
    }

    protected function unauthorizedResponse($response = 'Unauthorized') {
        return $this->errorResponse($response, 401);
    }

    protected function getBeginDayTime(){
        return strtotime("midnight", time());
    }

    public function getEndDayTime(){
        return strtotime("tomorrow", $this->getBeginDayTime()) - 1;
    }

    public function downloadData($excel, $filename){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Headers: Authorization, Content-Type, X-Requested-With, Accept, Origin");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0'); 
        
        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save('php://output');
    }
	
    public function logs($level, $msg, $data = array()){
        if ($this->logger == null){
            $this->logger = new Logger('einvoice');
            $this->logger->pushHandler(new StreamHandler(LOG_FILE_NAME . 'einvoice_' . date("ymd") . '.logs', Logger::DEBUG));
        }
        if ($level == "info"){
            $this->logger->addInfo($msg, $data);    
        }
        else{
            $this->logger->addError($msg, $data);
        }
    }
}