<?php
// +----------------------------------------------------------------------
// | 微信管理系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010-2017  All rights reserved.
// +----------------------------------------------------------------------
// | Author:
// +----------------------------------------------------------------------

namespace Weixin\Controller;
use User\Api\UserApi;

/**
 * 用户控制器
 * 包括用户中心，用户登录及注册
 * Class UserController
 * @package Weixin\Controller
 * @author
 */
class UserController extends HomeController {

    /**
     * 检查用户是否登录
     * @author
     */
    protected function checkLogin(){
        if (is_login()) {
            $url = U('Index/index');
            header("Location: {$url}");
            exit;
        }
    }
    /**
     * 注册
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $mobile_phone
     * @author
     */
    public function register($username = '' , $password = '' , $email = '' , $mobile = ''){
	$this->checkLogin();
	if($_GET['code']){
		//get user openid
		$user = $this->getOpenid($_GET['code']);
		if($user){
			$mem = D('Member');
			$userinfo = $mem->where('openid = "'.$user['openid'].'"')->find();
			if($userinfo){
				if ($mem->login($userinfo['uid'])) { //登录用户
					//跳转首页
					$url = U("Index/index");
					header("Location: $url");
				} 
			}else{//register
				$wxuser = $this->getWxuser($user['openid'],$user['access_token']);
				if($wxuser){
					$_SESSION['wxuser']['openid']=$user['openid'];
					$_SESSION['wxuser']['nickname'] = $wxuser['nickname'];
					$_SESSION['wxuser']['headimgurl'] = $wxuser['headimgurl'];
					$_SESSION['wxuser']['parent_id'] = $_GET['parent_id'];
					$_SESSION['wxuser']['root_id'] = $_GET['root_id'];
				}
			}
		}
			$this->meta_title = '会员注册';
			$this->display('register');
			return;
	}else{
		if (IS_POST) {
			$username             = $mobile;
			$password             = 'ewangtx'.time().$mobile;
			$phone                    = $mobile;
			$is_exist = M('UcenterMember')->where("username = {$mobile}")->find();
			$Member = D("Member");
			if ($Member->login($is_exist['id'])) {
				if($_SESSION['wxuser']){
					$arr['nickname'] = ($_SESSION['wxuser']['nickname']) ? $_SESSION['wxuser']['nickname'] :  "wx".$mobile;
					$arr['headimgurl'] = $_SESSION['wxuser']['headimgurl'];
					$arr['openid'] = $_SESSION['wxuser']['openid'];
					$Member->where(array('uid'=>$is_exist["id"]))->save($arr);

				}
				if ($Member->login($is_exist['id'])) { //登录用户
					//跳转首页
					$url = U("Index/index");
					header("Location: $url");
				}
			}
			
			/* 调用注册接口注册用户口注册用户 */
			$User = new UserApi;
			//返回ucentermember数据表用户主键id
			$uid = $User->register($username , $password , $email , $phone);
			if ($Member->login($uid)) { //登录用户
				$arr['nickname'] = ($_SESSION['wxuser']['nickname']) ? $_SESSION['wxuser']['nickname'] :  "wx".$mobile;
				$arr['headimgurl'] = $_SESSION['wxuser']['headimgurl'];
				$arr['openid'] = $_SESSION['wxuser']['openid'];
				$arr['parent_id']=$_SESSION['wxuser']['parent_id'];
				$arr['root_id']= $_SESSION['wxuser']['root_id'];
				$Member->where(array('uid'=>$uid))->save($arr);

				//跳转首页
				$url = U("Index/index");
				header("Location: $url");
			}
		}else if($_SESSION['wxuser']){
			$_SESSION['send_code'] = random(6 , 1);//生成随机加密码。发送手机短信使用
			$this->assign('code' , $_GET['code']);
			$this->meta_title = '会员注册';
			$this->display('register');
			return;

		}else{
			$uid = $_GET['parent_id'] ? $_GET['parent_id'] : 0;
			$root = $_GET['root_id'] ? $_GET['root_id'] : 0;
			//eg :http://duobao.akng.net/Weixin/User/register/parent_id/231/root_id/74
			$weixin = M ( "Wxsetting" )->where ( array ("id" => "1" ) )->find ();
			$shareurl ='http://' . $_SERVER['HTTP_HOST'] . '/Weixin/User/register/parent_id/'.$uid.'/root_id/'.$root;
			$wurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$weixin['appid']."&redirect_uri=".$shareurl."&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
			header('Location:'.$wurl);
		}
	}

    }
   function getOpenid($code){
	   $userinfo=[];
	   $weixin = M ( "Wxsetting" )->where ( array ("id" => "1" ) )->find ();
	   $get_openid_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$weixin['appid'].'&secret='.$weixin['appsecret'].'&code='.$code.'&grant_type=authorization_code';
	   $data = $this->get_by_curl($get_openid_url);
	   $data = json_decode($data,true);
	   if(isset($data['openid'])){
		   $userinfo['openid']= $data['openid'];
		   $userinfo['access_token'] = $data['access_token'];
		   return $userinfo;
	   }
	   return false;
   }
 function getWxuser($openid,$access_token){
	 $scope_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
	 $scope_res = json_decode($this->get_by_curl($scope_url),true);
	   error_log(print_r($scope_res,true),3,'/home/logs/my.log');
	 if(!isset($scope_res['errcode'])){
		 $wxuser['nickname'] = $scope_res['nickname'];
		 $wxuser['headimgurl'] = $scope_res['headimgurl'];
		 $wxuser['sex'] = $scope_res['sex'];
		 return $wxuser;
	 }
	return false;
 }
    /**
     * 登录
     * @param string $username
     * @param string $password
     * @param string $verify
     * @author
     */
    public function login($username = "" , $password = "" , $verify = ""){
		$this->checkLogin();
        if (IS_POST) {
            /* 调用UC登录接口登录 */
            $user = new UserApi;
            $uid  = $user->login($username , $password);
            if ($uid >0) { //UC登录成功
                /* 登录用户 */
                $Member = D("Member");
                if($_SESSION['parent_id']){
                    $arr['parent_id'] = $_SESSION['parent_id'];
                }
                //$arr['nickname'] = $_SESSION['wx_info']['nickname'];
				if($_SESSION['wx_info']['nickname']){//当微信用户昵称为空时 设置手机号码为用户名
					$arr['nickname'] = $_SESSION['wx_info']['nickname'];
				}else{
					$arr['nickname'] = "wx_".$mobile;
				}				
                $arr['headimgurl'] = $_SESSION['wx_info']['headimgurl'];
                $arr['sex'] = $_SESSION['wx_info']['sex'];
                $Member -> where(array('uid'=>$uid)) -> save($arr);
                if ($Member->login($uid)) { //登录用户
                    //跳转首页
 					$url = U("Index/index");
					header("Location: $url");
                } else {
                    $this->error($Member->getError());
                }
            } else { //登录失败
                switch ($uid) {
                    case -1:
                        $error = "用户不存在或被禁用！";
                        break; //系统级别禁用
                    case -2:
                        $error = "密码错误！";
                        break;
                    default:
                        $error = "未知错误！";
                        break; // 0-接口参数错误（调试阶段使用）
                }
                $this->error($error);
            }
        } else {
            session("url" , NULL);
            $url = $_SERVER['HTTP_REFERER'];
            session("url" , $url);
            $this->meta_title = '会员登录';
            //显示登录表单
            $this->display();
        }
    }


    /**
     * 登录
     * @param string $username
     * @param string $password
     * @author
     */
    public function doLogin($username = "", $password = ""){
        if(IS_POST){ //登录验证
            /* 检测验证码 */
//            $password             = 'ewangtx'.time().$username;
            if(empty($username)){
                $status = 0;
                $msg = '请填写用户名！';
            }elseif(empty($password)){
                $status = 0;
                $msg = '请填写密码！';
            }else{
                /* 调用UC登录接口登录 */
                $user = new UserApi;
                $uid = $user->login($username, $password);
                if(0 < $uid){ //UC登录成功
                    /* 登录用户 */
                    $Member = D("Member");
                    if($Member->login($uid)){ //登录用户
                        $status = 1;
                        $msg = 'success';
                    } else {
                        $status = 0;
                        $msg = $Member->getError();
                    }
                } else { //登录失败
                    $status = 0;
                    switch($uid) {
                        case -1: $msg = "用户不存在或被禁用！"; break; //系统级别禁用
                        case -2: $msg = "密码错误！"; break;
                        default: $msg = "未知错误！"; break; // 0-接口参数错误（调试阶段使用）
                    }
                }
            }
        }else{
            $status = 0;
            $msg = '未知错误！';
        }

        $data['status'] = $status;//状态：0登录失败 1登录成功
        $data['msg'] = $msg;
        $this->ajaxReturn($data);
    }


    /**
     * 退出登录
     * @author
     */
    public function logout(){
        if (is_login()) {
            D("Member")->logout();
        }
        $this->redirect("User/register");
    }

    /**
     * 验证码，用于登录和注册
     * @author
     */
    public function verify(){
        $verify = new \Think\Verify();
        $verify->entry(1);
    }

    /**
     * 获取用户注册错误信息
     * @param int $code 错误编码
     * @return string   错误信息
     * @author
     */
    private function showRegError($code = 0){
        switch ($code) {
            case -1:
                $error = "用户名长度必须在16个字符以内！";
                break;
            case -2:
                $error = "用户名被禁止注册！";
                break;
            case -3:
                $error = "用户名被占用！";
                break;
            case -4:
                $error = "密码长度必须在6-30个字符之间！";
                break;
            case -5:
                $error = "邮箱格式不正确！";
                break;
            case -6:
                $error = "邮箱长度必须在1-32个字符之间！";
                break;
            case -7:
                $error = "邮箱被禁止注册！";
                break;
            case -8:
                $error = "邮箱被占用！";
                break;
            case -9:
                $error = "手机格式不正确！";
                break;
            case -10:
                $error = "手机被禁止注册！";
                break;
            case -11:
                $error = "手机号被占用！";
                break;
            default:
                $error = "未知错误";
        }
        return $error;
    }


    /**
     * 发送短信 -- 注册
     * @author
     */
    public function sendphone(){
        $phone = $_POST['phone'];
        if (empty($phone)) {
            $return = array ("status" => 0 , "info" => '手机号不正确');
            header('Content-type: text/html; charset=UTF-8');
            $this->ajaxreturn($return);
            exit();
        }

        $oldphone = $_GET['oldphone'];//修改手机号操作：原手机号码
        if ($oldphone) {
            if ($phone == $oldphone) {
                $return = array ("status" => 0 , "info" => '请填写新的手机号码');
                $this->ajaxreturn($return);
                exit();
            }
        }

//        $map['mobile'] = $phone;
//        $user_id       = M('UcenterMember')->where($map)->getField('id');
//        if ($user_id) {
//            $return = array ("status" => 0 , "info" => '手机号已经被注册');
//            header('Content-type: text/html; charset=UTF-8');
//            $this->ajaxreturn($return);
//            exit();
//        }

        $mobile_code = random(4 , 1);//生成手机验证码
        $send_code   = (!empty($_SESSION['send_code'])) ? $_SESSION['send_code'] : '8888';//获取提交随机加密码
        $content     = "您的短信验证码为：" . $mobile_code . "，有效期一小时。【千亩阳光】";
        $result      = sendsmscode($phone , $content , $send_code , $mobile_code);

        $this->ajaxreturn($result);

    }



    /**
     * 发送短信 -- 设置查询密码
     * @author
     */
    public function sendCodebyPhone(){
        $uid = D('Member')->uid();
        $phone       = M('UcenterMember')->where("id = {$uid}")->getField('mobile');
        $mobile_code = random(4 , 1);//生成手机验证码
        $send_code   = $_POST['send_code'];//获取提交随机加密码
        $content     = "您的短信验证码为：" . $mobile_code . "，有效期一小时。【千亩阳光】";
        $result      = sendsmscode($phone , $content , $send_code , $mobile_code);

        $this->ajaxreturn($result);

    }

    /**
     * 验证手机是否被使用
     * @author
     */
    public function ismobile_registered(){
        $mobile = $_POST['mobile'];
        $oldphone = $_GET['oldphone'];//修改手机号操作：原手机号码
        if ($oldphone) {
            if ($mobile == $oldphone) {
                $return = json_encode(array ("status" => 1 , "info" => ''));
                echo $return;
                exit();
            }
        }

        $map['mobile'] = $mobile;
        $userid        = M('UcenterMember')->where($map)->getField('id');
        if ($userid) {
            $return = array ("status" => 0 , "info" => '手机号已经被使用');
        } else {
            $return = array ("status" => 1 , "info" => '');
        }
        $this->ajaxreturn($return);
    }

    /**
     * 检查验证码短信
     * @author
     */
    public function checkphone(){
      // if (trim($_POST['miss']) == $_SESSION['mobile_code']) {
            $return = array ("status" => 1 , "info" => "");
     //   } else {
     //       $return = array ("status" => 0 , "info" => '验证码不正确');
     //   }
        $this->ajaxreturn($return);
    }




}
