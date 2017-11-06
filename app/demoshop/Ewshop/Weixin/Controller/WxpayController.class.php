<?php
namespace Weixin\Controller;
use Think\Controller;
class WxpayController extends Controller {
 
    private $wxpayConfig;
    private $wxpay;
 
    public function _initialize(){
        header("Content-type: text/html; charset=utf-8");
        vendor('Wxpay.jsapi.WxPaypubconfig');
        vendor('Wxpay.jsapi.WxPayPubHelper');
        vendor('Wxpay.jsapi.demo.log_');
        vendor('Wxpay.jsapi.SDKRuntimeException');
 
        $this->wxpayConfig = array(
                'SSLCERT_PATH' => __ROOT__ . THINK_PATH . 'Library/Vendor/Wxpay/jsapi/cacert/apiclient_cert.pem',        // 证书路径,注意应该填写绝对路径
                'SSLKEY_PATH' => __ROOT__ . THINK_PATH . 'Library/Vendor/Wxpay/jsapi/cacert/apiclient_key.pem',          // 证书路径,注意应该填写绝对路径
                'CURL_TIMEOUT' => 30
        );
		$wxconfig = M('wxsetting')->where('id = 1')->find();
        $this->wxpayConfig['APPID'] = $wxconfig['appid'];      // 微信公众号身份的唯一标识
        $this->wxpayConfig['APPSECRET'] = $wxconfig['appsecret']; // JSAPI接口中获取openid
        $this->wxpayConfig['MCHID'] = $wxconfig['mchid'];     // 受理商ID
        $this->wxpayConfig['SKEY'] = $wxconfig['key'];       // 商户支付密钥Key
        $this->wxpayConfig['js_api_call_url'] = $this->get_url();
        $this->wxpayConfig['notifyurl'] = $_SERVER['SERVER_NAME'].U('/Weixin/Wxpay/Paynotify');//完整地址路径
        $this->wxpayConfig['returnurl'] = U('/Weixin/center');
 
        // 初始化WxPayConf_pub
        $wxpaypubconfig = new \WxPayConf_pub($this->wxpayConfig);
    }
 
    /**
     * 获取当前页面完整URL地址
     */
    private function get_url() {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
        return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;
    }
 
    public function index() {
    }
 
    /**
     *  获取openid
     */
    private function get_openid() {
         $openid = $_COOKIE['apiopenid'];

        if(empty($openid)) {
            // 使用jsapi接口
            $jsApi = new \JsApi_pub();

            // 通过code获得openid
            if (!isset($_GET['code'])) {
                // 触发微信返回code码
                $url = $jsApi->createOauthUrlForCode(\WxPayConf_pub::$JS_API_CALL_URL);
                Header("Location: " . $url);
            } else {
                // 获取code码,以获取openid
                $code = $_GET['code'];
                $jsApi->setCode($code);
                $openid = $jsApi->getOpenId();
                setcookie('apiopenid', $openid, time() + 86400);
            }
        }
        return $openid;
    }
 
    /**
     *  支付
     */
    public function pay() {
		$orderid = $_POST['orderid'];
		if(empty($orderid)){	
			if(isset($_SESSION['orderinfo']) && !empty($_SESSION['orderinfo']['orderid'])) {
				$orderid = $_SESSION['orderinfo']['orderid'];
			}
		}
		
		if($orderid){
			$orderinfo = M('order')->where(array('tag' => $orderid))->select();
			if($orderinfo){
				$payprice = $orderinfo[0]['pricetotal'];//获取订单金额
			}
		}			 
        if(empty($orderid) || empty($payprice)) {
            die('订单参数不完整!');
        }
 
        // 1,获取openid
        $openid = $this->get_openid();

        // 2,使用统一支付接口
        $unifiedOrder = new \UnifiedOrder_pub();
 
        // 设置统一支付接口参数
        // 设置必填参数
        // appid已填,商户无需重复填写
        // mch_id已填,商户无需重复填写
        // noncestr已填,商户无需重复填写
        // spbill_create_ip已填,商户无需重复填写
        // sign已填,商户无需重复填写
        $unifiedOrder->setParameter("openid", $openid);
        $unifiedOrder->setParameter("body", $orderid);                          // 商品描述
        // 自定义订单号,此处仅作举例
        $out_trade_no = $orderid;
        $unifiedOrder->setParameter("out_trade_no", $out_trade_no);              // 商户订单号
        $unifiedOrder->setParameter("total_fee", $payprice * 100);               // 总金额
        $unifiedOrder->setParameter("notify_url", \WxPayConf_pub::$NOTIFY_URL);  // 通知地址
        $unifiedOrder->setParameter("trade_type", "JSAPI");                      // 交易类型
        // 非必填参数,商户可根据实际情况选填
        //$unifiedOrder->setParameter("sub_mch_id", "XXXX");                 // 子商户号
        //$unifiedOrder->setParameter("device_info", "XXXX");                    // 设备号
        //$unifiedOrder->setParameter("attach", "XXXX");                     // 附加数据
        //$unifiedOrder->setParameter("time_start", "XXXX");                 // 交易起始时间
        //$unifiedOrder->setParameter("time_expire", "XXXX");                    // 交易结束时间
        //$unifiedOrder->setParameter("goods_tag", "XXXX");                      // 商品标记
        //$unifiedOrder->setParameter("openid", "XXXX");                     // 用户标识
        //$unifiedOrder->setParameter("product_id", "XXXX");                 // 商品ID
 
        $prepay_id = $unifiedOrder->getPrepayId();
 
        // 3,使用jsapi调起支付
        $jsApi = new \JsApi_pub();
        $jsApi->setPrepayId($prepay_id);
        $jsApiParameters = $jsApi->getParameters();
		$this->assign('jsApiParameters',$jsApiParameters);
        $returnurl = \WxPayConf_pub::$RETURN_URL;
		$this->assign('returnurl',$returnurl);
        $path =  dirname(__FILE__);

 		$this->display();

    }
 
    /**
     *  服务器异步通知页面路径
     */
    public function Paynotify() {
		
        /**
         * 通用通知接口demo
         * ====================================================
         * 支付完成后，微信会把相关支付和用户信息发送到商户设定的通知URL，
         * 商户接收回调信息后，根据需要设定相应的处理流程。
         *
         * 这里举例使用log文件形式记录回调信息。
         */
        // 使用通用通知接口
        $notify = new \Notify_pub();
 
        // 存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);
 
        // 验证签名,并回应微信。
        // 对后台通知交互时,如果微信收到商户的应答不是成功或超时,微信认为通知失败，
        // 微信会通过一定的策略（如30分钟共8次）定期重新发起通知
        // 尽可能提高通知的成功率,但微信不保证通知最终能成功。
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code", "FAIL");      // 返回状态码
            $notify->setReturnParameter("return_msg", "签名失败");   // 返回信息
        } else {
            $notify->setReturnParameter("return_code", "SUCCESS");   // 设置返回码
        }
        $returnXml = $notify->returnXml();
        echo $returnXml;
 
        //==商户根据实际情况设置相应的处理流程，此处仅作举例=======
 
        if($notify->checkSign() == TRUE) {
            if ($notify->data["return_code"] == "FAIL") {
                // 此处应该更新一下订单状态,商户自行增删操作
                //$log_->log_result($log_name, "【通信出错】:\n" . $xml . "\n");
            } elseif ($notify->data["result_code"] == "FAIL"){
                // 此处应该更新一下订单状态,商户自行增删操作
                //$log_->log_result($log_name, "【业务出错】:\n" . $xml . "\n");
            } else {
                // 此处应该更新一下订单状态,商户自行增删操作
                $order = $notify->getData();
                $orderid = $order["out_trade_no"];
				
                $Order =  M('order');
				$orderinfo = $Order->where(array('orderid' => $orderid))->find();
				
				if($orderinfo['status']=='-1' || $orderinfo['ispay']=='1'){
					//处理业务订单、改业务订单状态
					M("pay")->where(array('out_trade_no' => $orderid))->setField('status',2);
					$data = array('status'=>'1','ispay'=>'2');//设置订单为已经支付,状态为已提交
					M('order')->where(array('tag' => $orderid))->setField($data);   
					
					$disbut_open = M('Config')->getFieldByName('DISBUT_OPEN','value');
					if($disbut_open && $orderinfo['disbutmoney']>0){
						$walletrecord = M('Walletrecord');
						$user = D('member');		
						$disbut = M('Disbut');						
						$puid = $user->getFieldByUid($orderinfo['uid'],'puid');
						if($puid){
						//判断顶级是否为分销商
						$rootuserid = getuserrootid($orderinfo['uid']);
						$isdis = $user->getFieldByUid($rootuserid,'isdis');
							if($isdis){
									//获取分销百分比
							$pdisnum = $user->getFieldByUid($puid,'disnum');						
							$ppuid = $user->getFieldByUid($puid,'puid');	
							//获取默认分销百分比
							$pbaifenbi = getdislevel($puid);													
							$data['pid']=$puid;
							if($ppuid){
								$data['ppid']=$ppuid;
								$ppdisnum= $user->getFieldByUid($ppuid,'disnum');
								if($ppdisnum && $ppdisnum!=0.00){
									$data['pdisbutmoney']=$orderinfo['pricetotal']*$ppdisnum/100;
								}else{
									$data['pdisbutmoney']=$orderinfo['pricetotal']*getdislevel($ppuid)/100;
								}
							}else{
								$data['ppid']=0;
								$data['pdisbutmoney']=0;
							}
												
							$data['uid']=$orderinfo['uid'];	
							$data['total']=$orderinfo['pricetotal'];
							if($pdisnum && $pdisnum!=0.00){
								$data['disbutmoney']=$orderinfo['pricetotal']*$pdisnum/100;
							}else{
								$data['disbutmoney']=$orderinfo['pricetotal']*$pbaifenbi/100;
							}
							$data['goodsnum']=$orderinfo['goodsnum'];
							$data['orderid']=$orderinfo['orderid'];		
							$data['createtime']=NOW_TIME;	
							$ifdisbut = $disbut->add($data);
							if($ifdisbut){
								$pmoney = $disbut->getFieldById($ifdisbut,'disbutmoney');
								$wdata['uid'] = $puid;
								$wdata['money'] = $pmoney;
								$wdata['status'] = 1;
								$wdata['cometype'] = 2;
								$wdata['comeorder'] = 'FX'.$orderinfo['orderid'];
								$wdata['changetime'] = NOW_TIME;
								$wdata['note'] = '分销佣金';
								$walletrecordid = $walletrecord->add($wdata);
								if($walletrecordid){
									$oldmoney =	$user->getFieldByUid($puid,'account');
									$oldgoodsnum =	$user->getFieldByUid($puid,'num');
									$newnum = $oldgoodsnum+$orderinfo['goodsnum'];
									$newmoney = $oldmoney+$pmoney;
									$user->account = $newmoney;
									$user->num = $newnum;
									$user->where(array('uid' => $puid))->save();
								}
								if($ppuid){
									$ppmoney = $disbut->getFieldById($ifdisbut,'pdisbutmoney');
									$wdata['uid'] = $ppuid;
									$wdata['money'] = $ppmoney;
									$wdata['status'] = 1;
									$wdata['cometype'] = 2;
									$wdata['comeorder'] = 'FX'.$orderinfo['orderid'];
									$wdata['changetime'] = NOW_TIME;
									$wdata['note'] = '分销佣金';
									$walletrecordid = $walletrecord->add($wdata);
									if($walletrecordid){
										$oldmoney =	$user->getFieldByUid($ppuid,'account');
										//$oldgoodsnum =	$user->getFieldByUid($ppuid,'num');
										$newmoney = $oldmoney+$ppmoney;
										//$newnum = $oldgoodsnum+$orderinfo['goodsnum'];
										$user->account = $newmoney;
										//$user->num = $newnum;
										$user->where(array('uid' => $ppuid))->save();
									}								
								}
								
							}
								
							}else{
							
							$ppuid = $user->getFieldByUid($puid,'puid');
							if(!$ppuid){
								$ppuid = 0;
							}
							$data['pid']=$puid;
							$data['ppid']=$ppuid;					
							$data['uid']=$orderinfo['uid'];	
							$data['total']=$orderinfo['pricetotal'];
							$data['disbutmoney']=$orderinfo['disbutmoney'];
							if($ppuid){
								$data['pdisbutmoney']=$orderinfo['disbutmoney'];
							}else{
								$data['pdisbutmoney']=0;
							}
							$data['orderid']=$orderinfo['orderid'];		
							$data['createtime']=NOW_TIME;	
							$ifdisbut = $disbut->add($data);
							if($ifdisbut){
								
								$wdata['uid'] = $puid;
								$wdata['money'] = $orderinfo['disbutmoney'];
								$wdata['status'] = 1;
								$wdata['cometype'] = 2;
								$wdata['comeorder'] = 'FX'.$orderinfo['orderid'];
								$wdata['changetime'] = NOW_TIME;
								$wdata['note'] = '分销佣金';
								$walletrecordid = $walletrecord->add($wdata);
								if($walletrecordid){
									$oldmoney =	$user->getFieldByUid($puid,'account');
									$newmoney = $oldmoney+$orderinfo['disbutmoney'];
									$user->account = $newmoney;
									$user->where(array('uid' => $puid))->save();
								}
								if($ppuid){
									$wdata['uid'] = $ppuid;
									$wdata['money'] = $orderinfo['disbutmoney'];
									$wdata['status'] = 1;
									$wdata['cometype'] = 2;
									$wdata['comeorder'] = 'FX'.$orderinfo['orderid'];
									$wdata['changetime'] = NOW_TIME;
									$wdata['note'] = '分销佣金';
									$walletrecordid = $walletrecord->add($wdata);
									if($walletrecordid){
										$oldmoney =	$user->getFieldByUid($ppuid,'account');
										$newmoney = $oldmoney+$orderinfo['disbutmoney'];
										$user->account = $newmoney;
										$user->where(array('uid' => $ppuid))->save();
									}
								}
							}
							}
							
						}
					}
				}	
            }
 
            //商户自行增加处理流程,
            //例如：更新订单状态
            //例如：数据库操作
            //例如：推送支付完成信息
        }
    }
}