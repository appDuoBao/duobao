<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ewangtx <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------

namespace Weixin\Controller;
use User\Api\UserApi;

/**
 * 用户控制器
 * 包括用户中心，用户登录及注册
 */
class UserController extends HomeController {

	/* 注册页面 */
	public function register($username = "", $password = "", $repassword = "", $email = "", $verify = ""){

		if(!C("USER_ALLOW_REGISTER")){
			$this->error("注册已关闭");
		}
		if(IS_POST){ //注册用户
			/* 检测验证码 */
			//if(!check_verify($verify)){
			//$this->error("验证码输入错误！");
			//}
			$phone = $_POST['mobile_phone'];
			if(empty($phone)){
				$this->error("手机号码不能为空！");
			}
			/* 检测密码 */
			if($password != $repassword){
				$this->error("密码和重复密码不一致！");
			}

			/* 调用注册接口注册用户 */
			$User = new UserApi;
			//返回ucentermember数据表用户主键id
			$uid = $User->register($username, $password, $email,$phone);
			if($uid > 0){ //注册成功
				if(C('MAIL_PASSWORD')){
					//TODO: 发送验证邮件
					// 配置邮件提醒
					$mail=$_POST['email'];//获取会员邮箱
					$title="注册提醒";
					$auth=sha1(C('DATA_AUTH_KEY'));
					$name= $_SERVER['SERVER_NAME'];
					$url=$_SERVER['SERVER_NAME'].U("account/confirm_email",array('regid'=>$uid,'type'=>"email",'auth'=>$auth,'url'=>$name));
					$words=sha1($url);
					$content="您在".C('SITENAME')."注册了账号，<a href=\"".$url."\" target='_blank'>".$words.'</a>请点击激活'.$mail;

					SendMail($mail,$title,$content);
				}

				//*********UC会员同步注册*********//
				/***
				vendor('ThinkphpUcenter.UcApi');
				$reg = UcApi::reg($username, $password,$email,true);
				 ****/
				//*********UC会员同步注册*********//

				// 调用登陆
				$this->login($phone, $password);

			} else { //注册失败，显示错误信息
				$this->error($this->showRegError($uid));
			}

		} else {
			$menu=R("index/menulist");
			$this->assign("categoryq", $menu);//显示注册表单
			session("url",null);
			$url=$_SERVER['HTTP_REFERER'];
			session("url",$url);
			$_SESSION['send_code'] = random(6,1);//生成随机加密码。发送手机短信使用
			$this->meta_title = '会员注册';
			$this->display();
		}
	}

	/* 登录页面 */
	public function login($username = "", $password = "", $verify = ""){
		if(IS_POST){ //登录验证
			/* 检测验证码 */
			if(empty($username)){
				$this->error("请填写用户名！");
			}
			if(empty($password)){
				$this->error("请填写密码！");
			}
			/* 调用UC登录接口登录 */
			$user = new UserApi;
			$uid = $user->login($username, $password);
			if($uid > 0){ //UC登录成功
				/* 登录用户 */
				$Member = D("Member");
				if($Member->login($uid)){ //登录用户
					$url= U('Center/index');
					header("Location: {$url}");
					exit;
				} else {
					$this->error($Member->getError());
				}
			} else { //登录失败
				switch($uid) {
					case -1: $error = "用户不存在或被禁用！"; break; //系统级别禁用
					case -2: $error = "密码错误！"; break;
					default: $error = "未知错误！"; break; // 0-接口参数错误（调试阶段使用）
				}
				$this->error($error);
			}
		} else {
			session("url",null);
			$url=$_SERVER['HTTP_REFERER'];
			session("url",$url);
			$this->meta_title = '会员登录';
			if (is_login()){
				$url = U('Home/index');
				header("Location: {$url}");
				exit;
			}else{
				//显示登录表单
				$this->display();
			}
		}
	}
	/* 登录页面 */
	public function doLogin($username = "", $password = ""){
		if(IS_POST){ //登录验证
			/* 检测验证码 */
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


	
	public function loginfromdialog($username = "", $password = ""){
		if(IS_POST){ //登录验证
			/* 调用UC登录接口登录 */
			$user = new UserApi;
			$uid = $user->login($username, $password);
			if(0 < $uid){ //UC登录成功
				/* 登录用户 */
				$Member = D("Member");
				if($Member->login($uid)){ //登录用户
					//TODO:跳转到登录前页面
					$data["status"] =1;
					$data["info"] = "登录成功";
					$this->ajaxReturn($data);
				} else {
					$this->error($Member->getError());
				}
			} else { //登录失败
				switch($uid) {
				case -1: $error = "用户不存在或被禁用！"; break; //系统级别禁用
				case -2: $error = "密码错误！"; break;
				default: $error ="未知错误！"; break; // 0-接口参数错误（调试阶段使用）
				}
				$this->error($error);
			}
		} else { 
			//显示登录表单
			$this->display();
		}
	}


	/* 退出登录 */
	public function logout(){
		if(is_login()){
			D("Member")->logout();
			$url = U('User/login');
			header("Location: {$url}");
			exit;
		} else {
			$this->redirect("User/login");
		}
	}
	
	public function favor(){
		if(IS_AJAX ){
				$id=$_POST["id"];
				$data["id"] = $id;
				$uid=D("Member")->uid();
				$data["uid"]=$uid;
				$fav=M("favortable");
				$exsit=$fav->where("goodid='$id' and uid='$uid'")->getField("id");
				if(isset($exsit)){
					$data["status"] = 1;
					$data["msg"] = "已收藏过";
					$this->ajaxReturn($data);
			    } else{
					$fav->goodid=$id;
					$fav->uid=$uid;
					$fav->create_time=NOW_TIME;

					$fav->add();
					$data["status"] = 1;
					$data["msg"] = "已收藏";
					$this->ajaxReturn($data);
			    }
		   
		   
		   }
		
	}
	/* 验证码，用于登录和注册 */
	public function verify(){
		$verify = new \Think\Verify();
		$verify->entry(1);
	}

	/**
	 * 获取用户注册错误信息
	 * @param  integer $code 错误编码
	 * @return string        错误信息
	 */
	private function showRegError($code = 0){
		switch ($code) {
			case -1:  $error = "用户名长度必须在16个字符以内！"; break;
			case -2:  $error = "用户名被禁止注册！"; break;
			case -3:  $error = "用户名被占用！"; break;
			case -4:  $error = "密码长度必须在6-30个字符之间！"; break;
			case -5:  $error = "邮箱格式不正确！"; break;
			case -6:  $error = "邮箱长度必须在1-32个字符之间！"; break;
			case -7:  $error = "邮箱被禁止注册！"; break;
			case -8:  $error = "邮箱被占用！"; break;
			case -9:  $error = "手机格式不正确！"; break;
			case -10: $error = "手机被禁止注册！"; break;
			case -11: $error = "手机号被占用！"; break;
			default:  $error = "未知错误";
		}
		return $error;
	}
	
	public function cart(){
		$cart=$_SESSION["cart"];
		if($cart){
			foreach($cart as $k=>$val){ 
				$id=$val["id"];
				$table->goodid=$id;
				$member=D("member");
				$uid=$member->uid();
				$table->uid=$uid;
				$table->partnerid=get_partnerid($uid);
				$num=M("shopcart")->where("goodid='$id'")->getField("num");
				if($num){
					$table->num=$val["num"]+$num;$table->save();
				}else{
					$table->num=$val["num"];$table->add();
				}
			}
			return $uid;
		}
	}

    /**
     * 修改密码提交
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function profile(){
		if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}
        if (IS_POST) {
            //获取参数
            $uid        =   is_login();
            $password   =   I("post.old");
            $repassword = I("post.repassword");
            $data["password"] = I("post.password");
            empty($password) && $this->error("请输入原密码");
            empty($data["password"]) && $this->error("请输入新密码");
            empty($repassword) && $this->error("请输入确认密码");

            if($data["password"] !== $repassword){
                $this->error("您输入的新密码与确认密码不一致");
            }

            $Api = new UserApi();
            $res = $Api->updateInfo($uid, $password, $data);
            if($res['status']){
                $this->success("修改密码成功！");
            }else{
                $this->error($res["info"]);
            }
        }else{    $this->meta_title = '修改密码';
            $this->display();
        }
    }

	/*****领优惠券***************/
	 public  function getcoupon() {
		$id=$_POST["couponid"];
		$member=D("member");
		$uid=$member->uid();
		$coupon=M("usercoupon");
		if($coupon->where("uid='$uid'and couponid='$id'")->select() ){
			$data["msg"] = "已领取过";
			$data["status"] = "0";
			$this->ajaxreturn($data); 
		}else{ $data["uid"] = $uid;
			$data["couponid"] = $id;
			$data["create_time"] = NOW_TIME;
			$data["status"] = "1";
			$data["info"] = "未使用";
			$coupon->add($data);
			$data["msg"] = "已成功领取，请刷新查看";

			$this->ajaxreturn($data); 

		}
	 
	 }

	public  function cut() {
		if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}
		$uid=I("get.id");
		$cut=M("member")->where("uid='$uid'")->select();
		$this->assign('cut',$cut);  
		$images = new \Think\Image();
		if ($_POST['pic']){	
			//$src=C('DOMAIN').$_POST["pic"];
			$src=$_POST['pic'];
			$images->open($src);
			$name= time().$src;
			$x=$_POST["x"];
			$y=$_POST["y"];	$w=$_POST["w"];
			$h=$_POST["h"];
			$s=$images->crop(400, 400,100,30)->save('./'.$name);
			echo $s;
		}
		$this->meta_title = '修改图像';
		$this->display();
	
	}
	
	//发送短信 -- 注册
	public  function sendphone() {
		$phone=$_POST['phone'];
		if (empty($phone)) {
			$return=array("status"=>0,"info"=>'手机号不正确');
			header('Content-type: text/html; charset=UTF-8');
			$this->ajaxreturn($return); 
			exit();
		}
		
		$oldphone = $_GET['oldphone'];//修改手机号操作：原手机号码
		if($oldphone){
			if($phone==$oldphone){
				$return=array("status"=>0,"info"=>'请填写新的手机号码');
				$this->ajaxreturn($return); 
				exit();	
			}
		}
	
		$map['mobile']=$phone;
		$user_id = M('UcenterMember')->where($map)->getField('id');	
		if ($user_id){
			$return=array("status"=>0,"info"=>'手机号已经被注册');
			header('Content-type: text/html; charset=UTF-8');
			$this->ajaxreturn($return); 
			exit();
		}
		
		$mobile_code =random(4,1);//生成手机验证码
		$send_code = $_POST['send_code'];//获取提交随机加密码
		$content="您的短信验证码为：".$mobile_code."，有效期一小时[".C('SMSTAG')."]";
		$result = sendsmscode($phone,$content,$send_code,$mobile_code);
		 
		$this->ajaxreturn($result); 

	}
	//发送短信 --找回密码
	public  function sendgetphone() {
		$phone=$_POST['phone'];
		if (empty($phone)) {
			$return=array("status"=>0,"info"=>'手机号不正确');
			header('Content-type: text/html; charset=UTF-8');
			$this->ajaxreturn($return); 
			exit();
		}
	
		$map['mobile']=$phone;
		$user_id = M('UcenterMember')->where($map)->getField('id');	
		if (!$user_id){
			$return=array("status"=>0,"info"=>'您的手机号还没有注册');
			header('Content-type: text/html; charset=UTF-8');
			$this->ajaxreturn($return); 
			exit();
		}

		$mobile_code =random(4,1);//生成手机验证码
		$send_code = $_POST['send_code'];//获取提交随机加密码
		$content="找回密码操作：您的短信验证码为：".$mobile_code."，有效期一小时[".C('SMSTAG')."]";
		$result = sendsmscode($phone,$content,$send_code,$mobile_code);
		if($result['status']=='1'){//发送成功返回会员id
			$result['uid']=$user_id;
		}
		$this->ajaxreturn($result); 

	}
		
	//验证用户名是否被使用
	public  function isname_registered() {	
		$username = $_POST['username'];
		
		$map['username']=$username;
		$userid = M('UcenterMember')->where($map)->getField('id');	
		if ($userid)
		{
			$return=array("status"=>0,"info"=>'用户名已经被使用');
		}else{
			$return=array("status"=>1,"info"=>'');
		}
		$this->ajaxreturn($return); 
	}
	
	//验证机构名是否重复
	public  function isname_jgregistered() {	
		$jgusername = $_POST['jgusername'];
		
		$map['institution_name']=$jgusername;
		$userid = M('Member')->where($map)->getField('uid');	
		if ($userid)
		{
			$return=array("status"=>0,"info"=>'机构名已经被使用');
		}else{
			$return=array("status"=>1,"info"=>'');
		}
		$this->ajaxreturn($return); 
	}
	
	
	
	//验证手机是否被使用
	public  function ismobile_registered() {	
		$mobile_phone = $_POST['mobile_phone'];
		
		$oldphone = $_GET['oldphone'];//修改手机号操作：原手机号码
		if($oldphone){
			if($mobile_phone==$oldphone){
				$return=json_encode(array("status"=>1,"info"=>''));
				echo $return;
				exit();	
			}
		}
		
		$map['mobile']=$mobile_phone;
		$userid = M('UcenterMember')->where($map)->getField('id');	
		if ($userid)
		{
			$return=array("status"=>0,"info"=>'手机号已经被使用');
		}else{
			$return=array("status"=>1,"info"=>'');
		}
		$this->ajaxreturn($return); 
	}
	
	//检查验证码短信
	public  function checkphone() {		
        if (trim($_POST['miss'])==$_SESSION['mobile_code']){  
			$return=array("status"=>1,"info"=>"");	
        }else{
        	$return=array("status"=>0,"info"=>'短信验证码不正确');
        }
	    $this->ajaxreturn($return); 
	}
	
	//修改密码
	public function makepass() {
		
		if ( !is_login() ) {
		$this->error( "您还没有登陆",U("User/login") );
		}
		//获取会员ID
		$uid        =   is_login();
		$oldpassword = $_POST['oldpassword'];
		$newpassword = $_POST['password'];
		$repassword = $_POST['repassword'];
		$rightpassword = M('UcenterMember')->getFieldById($uid,'password');
		$username = M('UcenterMember')->getFieldById($uid,'username');
		
					/* 调用UC登录接口登录 */
				$user = new UserApi;
				$thisuid = $user->login($username, $oldpassword);
		
		if($thisuid<=0){
			$data['msg']='原密码不正确';
		}elseif(strlen($newpassword)<6){
			$data['msg']='密码长度过短';	
		}elseif($newpassword!=$repassword){
			$data['msg']='两次输入密码不一致';	
		}else{
			$Api = new UserApi();
			$da['password'] = $newpassword;
			$res = $Api->updateInfo($uid, $oldpassword, $da);
			if($res['status']){
				$data['msg']='修改成功';	
			}else{
				$data['msg']='修改失败';	
			}		
		}

		 $this->ajaxReturn($data,'JSON');

	}
	//weixin修改密码页
	public function change_pwd(){
		$this->display();
	}
	//weixin修改密码
	public function alterpass() {
		
		if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}
		//获取会员ID
		$uid        =   is_login();
		$oldpassword = $_POST['oldpassword'];
		$newpassword = $_POST['password'];
		$repassword = $_POST['repassword'];
		$rightpassword = M('UcenterMember')->getFieldById($uid,'password');
		$username = M('UcenterMember')->getFieldById($uid,'username');
		
		/* 调用UC登录接口登录 */
		$user = new UserApi;
		$thisuid = $user->login($username, $oldpassword);
		
		if($thisuid<=0){
			$data['msg']='原密码不正确';
		}elseif(strlen($newpassword)<6){
			$data['msg']='密码长度过短';	
		}elseif($newpassword!=$repassword){
			$data['msg']='两次输入密码不一致';	
		}else{
			$Api = new UserApi();
			$da['password'] = $newpassword;
			$res = $Api->updateInfo($uid, $oldpassword, $da);
			if($res['status']){
				$data['msg']='修改成功';	
			}else{
				$data['msg']='修改失败';	
			}		
		}														

		 $this->success($data['msg'],U('center/index'));

	}


	/* 找回密码页面 */
	public function repassword(){
		if(IS_POST){ //登录验证
			$mobile_phone = $_POST['mobile_phone'];//手机号码
			$uid = $_POST['uid'];//会员id
			if(empty($uid)){
				$this->error("会员不存在！");
			}
			$map['uid']=$uid;
			$userid = M('UcenterMember')->where($map)->getField('id');
			if (!$userid)
			{
				$this->error("会员不存在！");
			}
			$password = $_POST['password'];
			$repassword = $_POST['repassword'];
			if(empty($password) || empty($repassword)){
				$this->error("密码不能为空！");
			}
			if($password !== $repassword){
				$this->error("您输入的新密码与确认密码不一致");
			}
			$data["password"] = $password;
			$Api = new UserApi();
			$res = $Api->updateInfo($uid, $oldpassword, $data,false);
			if($res['status']){
				$this->success("修改密码成功！",U('User/logout'));
			}else{
				$this->error($res["info"]);
			}
			//$this->assign("uid", $uid);//会员id
			$this->meta_title = '设置密码';
			//$this->display('User/setpassword');
		} else {
			$_SESSION['send_code'] = random(6,1);//生成随机加密码。发送手机短信使
			$this->meta_title = '找回密码';
			$this->display();
		}
	}

	/*设置新密码页面 */
	public function setpassword(){
		if(IS_POST){ //登录验证
			$uid = $_POST['uid'];//会员id
			if(empty($uid)){
				$this->error("会员不存在！");
			}		
			$password = $_POST['password'];
			$repassword = $_POST['repassword'];
			if(empty($password) || empty($repassword)){
				$this->error("密码不能为空！");
			}
            if($password !== $repassword){
                $this->error("您输入的新密码与确认密码不一致");
            }
			/**
			$map['uid']=$uid;
			$oldpassword = M('UcenterMember')->where($map)->getField('password');	
			if(empty($oldpassword)){
				$this->error("会员不存在！");
			}	
			**/
            $data["password"] = $password;
            $Api = new UserApi();
            $res = $Api->updateInfo($uid, $oldpassword, $data,false);
            if($res['status']){
                $this->success("修改密码成功！",U('User/logout'));
            }else{
                $this->error($res["info"]);
            }			
		}else{
			$this->redirect("Index/index");
		}
	}
	public function checkcode(){
		/* uid调用*/
		$uid=D('member')->uid();

		$sn=$_POST["couponid"];
		$orderprice=$_POST["orderprice"];//订单金额
		$PYHJD=M("promotionYhjdata");
		$yhqdinfo=$PYHJD->where("sn='$sn' and (user_id='$uid' or user_id=0)")->find();//优惠券信息
		if($yhqdinfo){
			if($yhqinfo['used_time']!=0){
				$data["info"] = "抱歉，优惠券已经使用";
				$data["msg"] = "no";
				$data["status"] = "1";
			}
			$PYHJ = M("promotionYhj");
			$yhj_id = $yhqdinfo['yhj_id'];
			$yhqinfo=$PYHJ->where("id='$yhj_id'")->find();//优惠券信息
			if($yhqinfo){
				$curtime = time();
				$starttime = strtotime($yhqinfo['start_time']);
				$endtime = strtotime($yhqinfo['end_time']);
				if($starttime>$curtime || $endtime<$curtime){
					$data["info"] = "抱歉，当前时间无法使用此优惠券";
					$data["msg"] = "no";
					$data["status"] = "1";
				}elseif($yhqinfo['min_price']>$orderprice){
					$data["info"] = "抱歉，当前优惠券最低使用金额为".$yhqinfo['min_price']."元";
					$data["msg"] = "no";
					$data["status"] = "1";
				}else{
					if($orderprice>$yhqinfo['price']){
						$nowprice = $orderprice-$yhqinfo['price'];
					}else{
						$nowprice = 0;
					}
					$data["yhqprice"] = (int)$yhqinfo['price'];
					$data["nowprice"] = $nowprice;
					$data["msg"] = "yes";
					$data["status"] = "1";
				}
			}else{
				$data["info"] = "抱歉，优惠券不存在";
				$data["msg"] = "no";
				$data["status"] = "1";
			}
		}else{
			$data["info"] = "抱歉，优惠券不存在";
			$data["msg"] = "no";
			$data["status"] = "1";
		}
		$this->ajaxreturn($data);

	}

}
