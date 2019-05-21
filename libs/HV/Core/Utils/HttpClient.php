<?
namespace HV\Core\Utils;
class HttpResponse{
    public $status;
    public $headers = null;
    public $body = null;

    public function __construct($raw_data, $redirect_count){
        if ($raw_data == null){
            $this->status = -1;
            return;
        }
        $response = explode("\r\n\r\n", $raw_data, 2 + $redirect_count);
        $this->body = array_pop($response);
        $this->headers = array_pop($response);
        $this->status = $this->parseCode($this->headers);
    }

    public function isError(){
        return $this->status >= 400 || $this->status < 0;
    }

    private function parseCode($headers){
        $parts = explode(' ', substr($headers, 0, strpos($headers, "\r\n")));
        if (count($parts) < 2 || !is_numeric($parts[1])) {
            return -1;
        }
        return intval($parts[1]);
    }    
}

class HttpClient{
	public static function post($url, $data){
        $fields_string = [];

        /*
        $fields_string = '';
        foreach ($data as $key => $value) { 
            $fields_string .= $key . '=' . $value . '&'; 
        }
        $fields_string = rtrim($fields_string, '&');
        */
        $fields_string = http_build_query($data, null, '&');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT , "GotIt/Api v1.4");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        $result = curl_exec($ch);

        if ($result === false) {
			$msg = curl_error($ch);
			curl_close($ch);
			return new HttpResponse(null, 0);
 		}

 		$info = curl_getinfo($ch);
        curl_close($ch);

        return new HttpResponse($result, $info['redirect_count']);
	}

    public static function get($url, $params){
        $fields_string = [];

        foreach($params as $key => $value){
            $fields_string[] = $key . '=' . urlencode($value); 
        }
        $urlStringData = $url . '?' . implode('&', $fields_string);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT , "GotIt/Api v1.4");
        curl_setopt($ch, CURLOPT_URL, $urlStringData);

        $result = curl_exec($ch);

        if ($result === false) {
            $msg = curl_error($ch);
            curl_close($ch);
            return new HttpResponse(null, 0);
        }

        $info = curl_getinfo($ch);

        curl_close($ch);

        return new HttpResponse($result, $info['redirect_count']);
    }	
}