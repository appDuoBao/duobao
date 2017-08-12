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
    
    private static  $merchantCertPass='NskNUN'; 
    
    private static  $deskey = 'cputest';    
    public function index(){
		
	     $this->meta_title = '代付首页';
       
		 $this->display();
    }

    public function paySinTrans(){
	header("Content-type: text/html; charset=utf-8"); 
        $reUrl = 'http://43.227.141.32/paygateway/mpsGate/mpsTransaction';//接口类型
        $order['mcSequenceNo'] = "123456789";
        $order["mcTransDateTime"] = date('YmdHis');
        $order["orderNo"] = "201704070000013094";
        $order["amount"] = "1000";
        $order["cardNo"] = self::do_des('6225880175058792',self::$deskey); //'9df04f691e75d4fad0b57592b1dcfc14906ad91d4dbb3063';
        $order["accName"] =  '李立军';
        $order["accType"] = '0';
        $order1["lBnkNo"]  = '';
        $order1["lBnkNam"] = '';
        $order["crdType"]= "00";
        $order1["validPeriod"] ='';
        $order1["cvv2"] ="";
        $order1["cellPhone"] = "";
	$order1['remark']='';
	$order1['bnkRsv'] = '';
	$order['capUse'] ='9';
	//$order['callBackUrl']='http://duobao.akng.net/pay.php?s=index/callbak';
	$params['service'] = 'capSingleTransfer';
        $publicp = self::publicParams($params);
   	$order = array_merge($order,$publicp);
	ksort($order);
	$signdata = self::arrToStr($order);
        $sign = $this->RSAsign($signdata,self::$merchantCertPass);//password私钥证书的密码

        //$header =array($signdata.'&merchantSign='.$sign['sign'] . '&merchantCert='.$sign['cert']);
	$header =array();
	$order= array_merge($order,$order1);
	$order['merchantSign'] = $sign['sign'];
	$order['merchantCert'] = $sign['cert'];
        $res =mb_convert_encoding(PostHttp($reUrl,$order,$header),'UTF-8','auto');
	//var_dump($order,$res);exit;
	$ret = self::formartRet($res);
	var_dump($signdata);
	print_r($ret);exit;
            
    }
    public static function formartRet($ret){
	    if($ret){
		    $ret = explode('&', $ret);
		    if(is_array($ret)){

			    foreach($ret as $k=>$v){
				    $retv = explode('=', $v);
				    $arrret[$retv[0]] = $retv[1];
			    }

		    }
	    }
	return $arrret;
    }
    public static function publicParams($param = array()){
	$params["charset"] = '02';
	$params["version"] = '1.0';
	$params["merchantId"] = '800000101000109';
	$params["requestTime"] = date('YmdHis');
	$params["requestId"] = time();
	$params["service"] = $param['service'];
	$params["signType"] = 'RSA256';
	//$params['merchantCert'] = '';
	//$params['merchanSign']='';
	return $params;
    }
    public function getCardInfo(){
       
        $reUrl = 'http://43.227.141.32/paygateway/mpsGate/mpsTransaction';//接口类型
	//$reUrl = 'http://43.227.141.32/paygateway/rpmGate/rpmCardInfo';
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
public static function RSAsign($source, $password)
    {
        $certs = array ();
        $fd = fopen (self::$certFilePath, 'r' );
        $p12buf = fread ( $fd, filesize (self::$certFilePath) );
        fclose ( $fd );
        if (openssl_pkcs12_read ( $p12buf, $certs, $password )) {
            $pkeyid = openssl_pkey_get_private ( $certs ['pkey'] );//var_dump($pkeyid);exit;
            $signature = "";
	    $pubder = self::pem2der( $certs ['cert'] );
            openssl_sign ($source, $signature, $pkeyid,OPENSSL_ALGO_SHA256);
            openssl_free_key ( $pkeyid );
            return array('sign'=>self::asc2hex ( $signature ),'cert'=>self::asc2hex($pubder));
        }
    }

 public static function asc2hex($str)
    {
        return chunk_split(bin2hex($str), 2, '');
    }
 public static function pem2der($pem_data)
    {
        $begin = "CERTIFICATE-----";
        $end   = "-----END";
        $pem_data = substr($pem_data, strpos($pem_data, $begin)+strlen($begin));
        $pem_data = substr($pem_data, 0, strpos($pem_data, $end));
        $der = base64_decode($pem_data);
        return $der;
    }
public static function encodeArr($input){
	 while (list($k, $v) = each($input)) {
            if (!empty ($v)) {
                $ret[] = "$k=" . mb_convert_encoding($v, 'UTF-8', 'GB18030');
            } else if (gettype($v) != 'boolean' && !is_array($v)) {
                $ret[] = "$k=" . mb_convert_encoding($v, 'UTF-8', 'GB18030');
            }
        }
	return $ret;
 }
 private static function arrToStr($arrdata){
   if($arrdata){
	$ret = [];
   	while(list($k,$v)=each($arrdata)){
	  if(!empty($v)||$v===0 || $v ==='0'){
		$ret[] = "$k=$v";	
	  }	
	}
	return	$str = implode('&',$ret);
     }
	return false;
 }
 private static  function do_des($input, $key)
    {
        //$key = substr(md5($key), 0, 24);
        $td = mcrypt_module_open('tripledes', '', 'ecb', '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $encrypted_data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return self::asc2hex($encrypted_data);
    }
}
