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
    private static  $certFilePath='/home/cert/800001407940001.p12';
    
    private static  $merchantCertPass='YEbbam'; 
    
     
    public function index(){
		
	     $this->meta_title = '代付首页';
        exit('teste');
		 $this->display();
    }

    public function paySinTrans(){
	header("Content-type: text/html; charset=utf-8"); 
        $reUrl = 'http://43.227.141.32/paygateway/mpsGate/mpsTransaction';//接口类型
        $order['mcSequenceNo'] = "123456789";
        $order["mcTransDateTime"] = date('YmdHis');
        $order["orderNo"] = "201704070000013094";
        $order["amount"] = "1000";
        $order["cardNo"] = "6225880175058792";
        $order["accName"] =  mb_convert_encoding("李立军",'utf-8','auto');
        $order["accType"] = '0';
        $order["lBnkNo"]  = '';
        $order["lBnkNam"] = '';
        $order["crdType"]= "00";
        $order["validPeriod"] ='';
        $order["cvv2"] ="";
        $order["cellPhone"] = "";
	$order['remark']='';
	$order['bnkRsv'] = '';
	$order['capUse'] ='9';
	//$order['callBackUrl']='http://duobao.akng.net/pay.php?s=index/callbak';
	$params['service'] = 'capSingleTransfer';
        $publicp = self::publicParams($params);
   	$order = array_merge($order,$publicp);
	ksort($order);
        //$signdata = mb_convert_encoding(http_build_query($order),'utf-8','auto');
	$signdata = self::encodeArr($order);
	$signdata = http_build_query($signdata);
        $sign = $this->RSAsign($signdata,self::$merchantCertPass);//password私钥证书的密码

        //$header =array($signdata.'&merchantSign='.$sign['sign'] . '&merchantCert='.$sign['cert']);
	$header =array();
	$order['merchantSign'] = $sign['sign'];
	$order['merchantCert'] = $sign['cert'];
        $res =mb_convert_encoding(PostHttp($reUrl,$order,$header),'UTF-8','auto');
	//var_dump($order,$res);exit;
	$ret = self::formartRet($res);
	var_dump($order);
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
            openssl_sign ( $source, $signature, $pkeyid );
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
private static  function getRequestUrl(&$arrOpt) {

        $strUrl = $arrOpt['url'];
        if(!isset($arrOpt['get'])) {
            return $strUrl;
        }

        // 设置GET参数
        if (is_array($arrOpt['get']) && !empty($arrOpt['get'])) {
            $strGet = http_build_query($arrOpt['get']);
            if (strpos($strUrl, '?', 7) > 0) {
                $strUrl .= '&' . $strGet;
            } else {
                $strUrl .= '?' . $strGet;
            }
        }
        unset($arrOpt['get']);

        return $strUrl;
    }
}
