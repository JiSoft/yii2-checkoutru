<?php
/**
 * Checkout.ru API
 * @author JiSoft <jisoft.dn@gmail.com>
 */
 class CheckoutRuApi 
 {
    protected $apiKey;
    protected $ticket = false;
    protected $baseUrl = 'http://platform.checkout.ru';
    
    public function __construct($apiKey,$ticket='',$baseUrl='')
    {
      $this->apiKey = $apiKey;
      if (!empty($ticket))
        $this->ticket = $ticket;
      else
        $this->getTicket();
      if (!empty($baseUrl))
        $this->baseUrl = $baseUrl;
    }
    
    public function getTicket()
    {
      $url = $this->baseUrl.'/service/login/ticket/'.$this->apiKey;
      
      $response = $this->send($url);
      if (isset($response['ticket']) && !empty($response['ticket']) {
        $this->ticket = $response['ticket'];
        return $response['ticket'];
      } else {
        return false;
      }
    }
    
    public function getPlacesByQuery($text)
    {
      $url = $this->baseUrl.'/service/checkout/getPlacesByQuery/?ticket='.$this->ticket.'&place='.$text;
     
      $response = $this->send($url);
      
      if (isset($response['ticket']) && !empty($response['ticket']) {
        $this->token = $response['ticket'];
        return $response['ticket'];
      } else {
        return false;
      }
    }
    
    protected function send($url,$method='get',$params=[])
    {
      if (empty($this->ticket))
        return false;
      $curl=curl_init();
      curl_setopt($curl,CURLOPT_URL,$url);
      curl_setopt($curl,CURLOPT_VERBOSE,0);
      curl_setopt($curl,CURLOPT_HEADER,0);
      curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
      $curlData=curl_exec($curl);
      curl_close($curl);
      $responce=json_decode($curlData,true);
      return $response;
    }
    
 }
 
