<?php
// +----------------------------------------------------------------------
// | 微信管理系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010-2017  All rights reserved.
// +----------------------------------------------------------------------
// | Author:
// +----------------------------------------------------------------------

namespace Pay\Controller;
use User\Api\UserApi as UserApi;

/**
 * 后台首页控制器
 * @author ew_xiaoxiao
 */
class IndexController extends ControlController {
    /**
     * 后台首页
     * @author ew_xiaoxiao
     */
    private static  $certFilePath='/home/cert/800000101000109.p12';
    
    private static  $merchantCertPass='zIUhXP'; 
    
     
    public function index(){
		
	     $this->meta_title = '代付首页';
        exit('teste');
		 $this->display();
    }

    public function payByCode(){
        
        $order['orderId'] = "123456789";
        $order["memberId"] = "123456789";
        $order["contractId"] = "201704070000013094";
        $order["checkCode"] = "111111";
        $order["payType"] = "DQP";
        $order["currency"] =  "CNY";
        $order["orderTime"] = date('Ymd H:i:s');
        $order["clientIP"]  =  "100.202.103.87";
        $order["validUnit"] = "01";
        $order["validNum"]= "3";
        $order["goodsName"] = "手机";
        $order["goodsDesc"] ="红米世代";
        $order["amount"] = "1000";
        $order["offlineNotifyUrl"] = "http://100.66.155.60:8090/payNotice.jsp";
        //2、请求流程
   
            
    }
    public function getCardInfo(){
       
        $reUrl = 'http://43.227.141.32/paygateway/mpsGate/mpsTransaction';//接口类型

		$cardNo["charset"] = '';
		$cardNo["version"] = '1.0';
		$cardNo["service"] = 'rpmCardInfo';
		$cardNo["signType"] = 'RSA256';
		$cardNo["merchantId"] = '800000101000109';
		$cardNo["requestTime"] = date('YmdHis');
		$cardNo["requestId"] = time();
		$cardNo['cardNo'] = "6225880154901171";
        //2、请求流程
        // String buf = reqData + "&merchantSign=" + merchantSign + "&merchantCert=" + merchantCert;
        $password = 'zIUhXP';
        $signdata = http_build_query($cardNo);
        $sign = $this->sign($signdata,$password);//password私钥证书的密码
       
        $header =array($signdata.'&merchantSign='.$sign['sign'] . '&merchantCert='.$sign['cert']);
	$cardNo['merchantSign'] = $sign['sign'];
	$cardNo['merchantCert'] = $sign['cert'];
        $res = PostHttp($reUrl,$cardNo,$header);
        $ret = explode('&', $res);
        if(is_array($ret)){
            
            foreach($ret as $k=>$v){
                $retv = explode('=', $v);
                $arrret[$retv[0]] = $retv[1];
            }
            
        }
	print_r($arrret);exit;
        self::verifySign($ret,$retsign,$password);
    }
    public static function getSign($request,$encoding = 'gbk'){
        $certFilePath =self::$certFilePath;
        $password = self::$merchantCertPass;
        $reqStr=http_build_query($request);
        $asci = self::getBytes($reqStr);
        $sign = self::ascii2hex($asci);
        
        return $sign;
        
    }
    
     public static function getBytes($string) {  
        $bytes = array();  
        for($i = 0; $i < strlen($string); $i++){  
             $bytes[] = ord($string[$i]);  
        }  
        return $bytes;  
    }  
    public static function ascii2hex($ascii) {
          $hex = '';
          for ($i = 0; $i < strlen($ascii); $i++) {
            $byte = strtoupper(dechex(ord($ascii{$i})));
            $byte = str_repeat('0', 2 - strlen($byte)).$byte;
            $hex.=$byte." ";
          }
          return $hex;
    }
    
    public static function getCert(){
        openssl_private_encrypt();    
    }
    
    /**
   * 根据原文生成签名内容
   *
   * @param string $data 原文内容
   *
   * @return string
   * @author confu
   */
  public static function sign($data,$password)
  {
    $filePath = self::$certFilePath;
    if(!file_exists($filePath)) {
      return false;
    }
   
   $pkcs12 = file_get_contents($filePath);
    if(openssl_pkcs12_read($pkcs12,$certs,$password)){ //私钥带有密码
      $privateKey = $certs['pkey']; //根据实际情况键值可能不同
      $publicKey = $certs['cert'];
      if(openssl_sign($data, $binarySignature, $privateKey, OPENSSL_ALGO_SHA1)){
	
        return array('sign'=>base64_encode($binarySignature),'cert'=>base64_encode($publicKey));
     } 
    }
    return false;
  }
  
  /**
   * 验证签名自己生成的是否正确
   *
   * @param string $data 签名的原文
   * @param string $signature 签名
   *
   * @return bool
   * @author confu
   */
  public static function verifySign($data, $signature,$password)
  {
    $filePath = self::$certFilePath;
    if(!file_exists($filePath)) {
      return false;
    }
  
    $pkcs12 = file_get_contents($filePath);
    if (openssl_pkcs12_read($pkcs12, $certs, $password)) {
      $publicKey = $certs['cert'];
      $ok = openssl_verify($data, $signature, $publicKey);
      if ($ok == 1) {
        return true;
      }
    }
    return false;
  }
  
  /**
   * 验证返回的签名是否正确
   *
   * @param string $data　要验证的签名原文
   * @param string $signature 签名内容
   *
   * @return bool
   * @author confu
   */
  public static function verifyRespondSign($data, $signature)
  {
    $filePath = 'allinpay-pds.pem';
    if(!file_exists($filePath)) {
      return false;
    }
  
    $fp = fopen($filePath, "r");
    $cert = fread($fp, 8192);
    fclose($fp);
    $pubkeyid = openssl_get_publickey($cert);
  
    if(!is_resource($pubkeyid)) {
      return false;
    }
  
    $ok = openssl_verify($data, $signature, $pubkeyid);
    if ($ok == 1) {
      openssl_free_key($pubkeyid);
      return true;
    }
    return false;
  }
}
