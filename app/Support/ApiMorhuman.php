<?php
namespace App\Support;

class ApiMorhuman
{
    private $version, $server, $baseUrl;
    private $userName, $password, $apiKey;
    private $endPoint, $postField;

    private $isActive;
    private $curlConfig = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    );

    public function __construct()
    {
        $this->isActive = config('morhuman.active');
        $this->baseUrl = config('morhuman.baseurl');
        $this->server = config('morhuman.server');
        $this->version = config('morhuman.version');
        $this->userName = config('morhuman.username');
        $this->password = config('morhuman.password');
        $this->apiKey = config('morhuman.apikey');
    }

    public function isActive()
    {
        return $this->isActive;
    }

    public function getUrl($url)
    {
        return $this->baseUrl .'/api/'. $this->server .'/'. $this->version .'/'. $url;
    }

    public function getToken()
    {
        $curl = curl_init();
        curl_setopt_array($curl, $this->curlConfig);
		curl_setopt($curl, CURLOPT_URL, $this->baseUrl .'/api/mobile/auth/JWT_token');
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic '. base64_encode($this->userName.":".$this->password)
        ));

        $response = curl_exec($curl);
        if($response === false)
        {
            return json_decode(json_encode([
                'status' => false,
                'message' => 'curl error : ' . curl_error($curl),
                'data' => []
            ]));
            curl_close($curl);
            return $response;
        }

        curl_close($curl);
        return $response;
    }

    public function curlExec($method, $url, $request=null)
    {
        if ($this->isActive === false) {
            return json_decode(json_encode([
                'status' => true,
                'isActive' => false,
                'message' => 'Fitur bridging tidak aktif.',
                'data' => []
            ]));
        }

        $token = '';
        $getToken = $this->getToken();
        $tokenObj = json_decode($getToken);
        if($tokenObj->status === true) {
            $token = $tokenObj->data->token;
        } else {
            return $tokenObj;
        }

        $curl = curl_init();
        curl_setopt_array($curl, $this->curlConfig);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request));
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer '.$token,
            'API-KEY: ' . $this->apiKey,
        ));

        $response = curl_exec($curl);

        if($response === false)
        {
            return json_decode(json_encode([
                'status' => false,
                'message' => 'curl error : ' . curl_error($curl),
                'data' => []
            ]));
        }

        curl_close($curl);
        return json_decode($response);
    }

    public function get()
    {
        // $urlWithParams = empty($data) ? $url : $url . '?' . http_build_query($data);
        $urlWithParams = empty($this->postField) ? $this->endPoint : $this->endPoint . '?' . http_build_query($this->postField);
        return $this->curlExec('GET', $this->getUrl($urlWithParams));
    }

    public function post()
    {
        // return $this->curlExec('POST', $this->getUrl($url), $data);
        return $this->curlExec('POST', $this->getUrl($this->endPoint), $this->postField);
    }

    /*
    |--------------------------------------------------------------------------
    | Endpoint
    |--------------------------------------------------------------------------
    */

    public function transaction_history(Array $data = [])
    {
        $this->postField = $data;
        $this->endPoint = '/payroll/transaction_history';
    }

    public function transaction_list(Array $data = [])
    {
        $this->postField = $data;
        $this->endPoint = '/payroll/transaction_list';
    }
}
