<?php
namespace jisoft\checkoutru;

/**
 * Checkout.ru API
 * @author JiSoft <jisoft.dn@gmail.com>
 */
class CheckoutRuApi
{
    protected $apiKey = false;
    protected $ticket = false;
    protected $baseUrl = 'http://platform.checkout.ru';

    public function __construct($apiKey,$baseUrl='',$ticket='')
    {
        $this->apiKey = $apiKey;
        if (!empty($ticket))
            $this->ticket = $ticket;
        if (!empty($baseUrl))
            $this->baseUrl = $baseUrl;
    }

    public function getTicket()
    {
        if ($this->ticket != false)
            return $this->ticket;

        $url = $this->baseUrl.'/service/login/ticket/'.$this->apiKey;

        $response = $this->send($url);
        if (isset($response['ticket']) && !empty($response['ticket'])) {
            $this->ticket = $response['ticket'];
            return $response['ticket'];
        } else {
            return false;
        }
    }

    public function setTicket($ticket)
    {
        $this->ticket = $ticket;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function getPlacesByQuery($text)
    {
        $url = $this->baseUrl.'/service/checkout/getPlacesByQuery/?ticket='.$this->ticket.'&place='.$text;

        $response = $this->send($url);

        if (isset($response['suggestions']) && !empty($response['suggestions'])) {
            return $response['suggestions'];
        } else {
            return false;
        }
    }

    public function createOrder($data)
    {
        $url = $this->baseUrl.'/service/order/create';

        $response = $this->send($url,'post',$data);

//        if (isset($response['order']) && !empty($response['order'])) {
//            return $response;
//        } else {
//            return false;
//        }
        return $response;
    }

    protected function send($url,$method='get',$params=[])
    {
        if ($method=='get' && empty($this->ticket) && substr_count($url,'/service/login/ticket/')==0)
            return false;
        if ($method=='post' && empty($this->apiKey))
            return false;
        if ($method=='post' && !isset($params['apiKey']))
            $params['apiKey'] = $this->apiKey;
            
        $curl=curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_VERBOSE,0);
        curl_setopt($curl,CURLOPT_HEADER,0);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        if ($method=='post')
            curl_setopt($curl, CURLOPT_POST, true);
        if (count($params)>0 && is_array($params)) {
            $datajson = json_encode($params);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $datajson);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [                                                                        
                'Content-Type: application/json',                                                                                
                'Content-Length: ' . strlen($datajson)                                                                     
            ]);    
        }    
        $curlData=curl_exec($curl);
        echo '<h3>Curl</h3><pre>'; var_dump(curl_getinfo($curl)); echo "</pre><pre>"; var_dump(curl_error($curl)); echo '</pre>'; 
        curl_close($curl);
        return(json_decode($curlData,true));
    }
}
