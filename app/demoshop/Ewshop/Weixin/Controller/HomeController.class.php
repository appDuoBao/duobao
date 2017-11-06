<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------

namespace Weixin\Controller;
use Think\Controller;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class HomeController extends Controller {

	function get_by_curl($url,$post = false){
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if($post){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
		}
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	/* 空操作，用于输出404页面 */
	public function _empty(){
		$this->redirect('Index/index');
	}


    protected function _initialize(){
		//微信自动登录注册
		if($_GET['code']) {//微信code码
			  $config = M ( "Wxsetting" )->where ( array ("id" => "1" ) )->find ();
			  if($config){
				  $code = $_GET['code'];
				  $data = $this->get_by_curl('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$config['appid'].'&secret='.$config['appsecret'].'&code='.$code.'&grant_type=authorization_code');
				  $data = json_decode($data);
				  $openid = $data->openid;
				  $access_token = $data->access_token;
			  }
			  //echo 	"openid:".$openid."====access_token:".$access_token;
			  
			  //通过微信进去网站，默认登录操作
			  if($openid){
				  $memberinfo = M("Member")->where(array("openid"=>$openid))->find ();
				  if($memberinfo){//会员存在
					  /* 登录用户 */
					  $Member = D("Member");
					  $Member->login($memberinfo['uid']); //登录用户 
				  }else{//会员不存在
				  		//将微信标示——openid存入session
				 		$_SESSION['openid']=$openid;
						
						/******
						$options = array (
							'token' => $config ["token"], // 填写你设定的key
							'appid' => $config ["appid"], // 填写高级调用功能的app id
							'appsecret' => $config ["appsecret"], // 填写高级调用功能的密钥
						);			  
						$weObj = new \Common\Wechat\Wechat($options);
						$userinfo = $weObj->getUserInfo($openid);//静默模式获取关注会员信息
						if(empty($userinfo['nickname'])){//静默模式为获取会员信息则采用授权模式获取
							$userinfo = $weObj->getOauthUserinfo($access_token,$openid);//授权模式获取未关注会员
						}
						$member['openid']=$openid;
						if($_GET['pd']) {//上级分销会员id,链接来源
							$member['puid']=$_GET['pd'];
						}
						$member['nickname']=$userinfo['nickname'];
						$member['sex']=$userinfo['sex'];
						//$member['province']=$userinfo['province'];
						//$member['city']=$userinfo['city'];
						//$member['country']=$userinfo['country'];
						$member['login']=1;//会员登录次数
						$member['status']=1;//会员状态 1启用 0禁用
						$member['reg_time']=time();//注册时间
						$member['last_login_time']=time();//最后一次登录时间
						$uid = M ("Member")->add($member);
						
						$ucmember['id'] = $uid;
						$ucmember['username'] = $userinfo['nickname']."_".rand(10,100);
						$ucmember['status']=1;//会员状态 1启用 0禁用
						//$ucmember['face']=$userinfo['headimgurl'];//头像
						$ucmember['reg_time'] =time();//注册时间
						$ucmember['last_login_time'] =time();//最后登录时间
						$ucmember['update_time'] =time();//更新时间
						M ("UcenterMember")->add($ucmember);			  
						
						//注册用户
						$Member = D("Member");
						$Member->login($uid); //登录用户 会员
						****/
				  }
			  }
		}		
		//pc端分享分销链接
		if($_GET['issession']=='create'){
			if($_GET['pd']) {//上级分销会员id,链接来源
				$_SESSION['puid']=$_GET['pd'];
			}
		}		
        /* 读取站点配置 */
        $config = api('Config/lists');
        C($config); //添加配置

		if(!C('WEB_SITE_CLOSE')){
			$this->error('站点已经关闭，请稍后访问~');
		}

		/******************公共部分begin*****************/
		/* 购物车调用*/
		$usercart=$this->usercart();
		$this->assign('usercart',$usercart);

		/*获取购物车商品数量*/
		$userCartNum=$this->getCartNum();
		$this->assign('userCartNum',$userCartNum);

		/******************公共部分end*****************/


    }

	/**
	 **获取购物车信息
	 **/
	public function usercart(){
		if(is_login()) {
			$cart=D("shopcart");
			$result= $cart->getcart();
		}else{
			$result=$_SESSION['cart'];
		}
		return $result;
	}

	/*
	 *  获取购物车商品数量
	 */
	public function getCartNum() {
		/*查询购物车*/
		if(is_login()) {
			$cart=D("shopcart");
			$sum= $cart->getNumByuid();
		}else{
			$num = count($_SESSION['cart']);
			if ($num == 0) {
				//种数为0，个数也为0
				$sum = 0;
			}else{
				$sum = 0;
				$data = $_SESSION['cart'];
				foreach ($data as $item) {
					$sum += $item['num'];
				}
			}
		}
		return $sum;
	}



	/* 用户登录检测 */
	protected function login(){
		/* 用户登录检测 */
		is_login() || $this->error('您还没有登录，请先登录！', U('User/login'));
	}

}
