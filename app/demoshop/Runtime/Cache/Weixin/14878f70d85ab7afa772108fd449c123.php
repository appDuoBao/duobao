<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html style="height: auto;">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<title>会员注册-<?php echo C('WEB_SITE_TITLE');?></title>
	<meta name="keywords" content="<?php echo C('WEB_SITE_KEYWORD');?>"/>
	<meta name="description" content="<?php echo C('WEB_SITE_DESCRIPTION');?>" />
	<link rel="stylesheet" href="/Public/Weixin/css/css.css" />
	<script type="text/javascript" src="/Public/Weixin/js/jquery.min.js" ></script>
	<script  type="text/javascript" src="/Public/Weixin/js/common.js"></script>
</head>
<body>
<div class="xd-box-zhuce">
	<div class="xd-zhuce-logo">
		<img src="/Public/Weixin/images/logo2.png" alt="" />
	</div>
	<form class="form-horizontal" role="form" action="/index.php?s=/Weixin/User/register.html" method="post" name="formUser" id="formUser"  onsubmit="return register();">
		<div  class="xd-zhuce-list">
			<ul>
				<li>
					<input type="text" name="username" id="username" onBlur="isname_registered();" placeholder="请填写用户名" />
					<span class="check_tips error_tip" id="dusername"></span>
				</li>
				<li>
					<input type="password" id="password1" onblur="check_password(this.value);" onkeyup="checkIntensity(this.value)"  name="password" placeholder="设置6～12位密码" />
					<span class="check_tips error_tip" id="dpassword1"></span>
				</li>
				<li>
					<input type="password" name="repassword" id="conform_password" onblur="check_conform_password(this.value);"  placeholder="请再次输入密码" />
					<span class="check_tips error_tip" id="dconform_password"></span>
				</li>
				<li>
					<input type="text" name="mobile_phone" id="mobile_phone" onBlur="ismobile_registered();" placeholder="请填写手机号码" />
					<span class="check_tips error_tip" id="dmobile_phone"></span>
				</li>
				<li>
					<div class="miss_yzm">
						<input type="text" name="miss" id="miss" onBlur="checkphone();"  placeholder="填写验证码" />
						<span class="check_tips error_tip" id="dmiss"style="line-height:16px; margin-top:10px;"></span>
					</div>
					<a style="cursor:pointer;" id="sendma" name="sendma" onClick="sendphone();" >获取验证码</a>
					
				</li>
			</ul>

			<a href="javascript:void(0);" onclick="submitFormUser();" class="xd-zhuce-a">立即注册</a>
			<a href="<?php echo U('User/login');?>" class="xd-zhuce-a xd-zhuce-a1">登录</a>
		</div>
	</form>
</div>
</body>
<script type="text/javascript">
	function submitFormUser(){
		$('#formUser').submit();//提交
	}

	var secs = 60;
	function backcount(){
		$("#sendma").attr("onClick",'');
		for(i=1;i<=secs;i++) {
			window.setTimeout("update1(" + i + ")", i * 1000);
		}
	}
	function update1(num) {
		if(num == secs) {
			$("#sendma").html('获取短信验证码');
			$("#sendma").attr("onClick",'sendphone();');
		}else {
			printnr = secs-num;
			$("#sendma").html("(" + printnr +")秒后重发");
		}
	}
	function err_msg(str, id) {
		document.getElementById('d'+id).style.display = "inline-block";
		document.getElementById('d'+id).innerHTML=str;
	}

	function changecode(){
		var img = document.getElementById("showing");
		img.src = '<?php echo U("User/verify");?>?'+Math.random(1);
		return ;
	}


	function sendphone(){
		if(document.getElementById('dmobile_phone').innerHTML!="" && document.getElementById('dmobile_phone').innerHTML!="短信已发送到您的手机，请查看") {
			$("#mobile_phone").focus();
			return;
		}
		var phone=$("#mobile_phone").val();
		var vericode=$("#vericode").val();
		if(phone.length!=11||isNaN(phone)){
			err_msg('这个不是个正确的手机号码', 'mobile_phone');
			$("#mobile_phone").focus();
		}else{
			$.ajax({
				type:'post', //传送的方式,get/post
				url:'<?php echo U("User/sendphone");?>', //发送数据的地址
				data:{phone:phone,send_code:<?php if($_SESSION['send_code']){echo $_SESSION['send_code'];}else{echo '8888';} ?>,verify:vericode},
				dataType: "json",
				success:function(data){
					err_msg(data.info, 'mobile_phone');
				},
				//error:function (event, XMLHttpRequest, ajaxOptions, thrownError) {alert("表单错误，"+XMLHttpRequest+thrownError); }
			})
			backcount();
		}
	}

	function getStrLength(str) {
		var cArr = str.match(/[^\x00-\xff]/ig);
		return str.length + (cArr == null ? 0 : cArr.length);
	}

	function ismobile_registered(){
		var mobile_phone=$("#mobile_phone").val();
		if(mobile_phone ==''){
			err_msg('请填写手机号码', 'mobile_phone');
		}else{
			if(mobile_phone.length!=11||isNaN(mobile_phone)){
				err_msg('这个不是个正确的手机号码', 'mobile_phone');
				$("#mobile_phone").focus();
			}else{
				console.log(222);
				$.ajax({
					type:'post', //传送的方式,get/post
					url:'<?php echo U("User/ismobile_registered");?>', //发送数据的地址
					data:{mobile_phone:mobile_phone},
					dataType: "json",
					success:function(data)
					{
						err_msg(data.info, 'mobile_phone');
					},
					//error:function (event, XMLHttpRequest, ajaxOptions, thrownError) {alert("表单错误，"+XMLHttpRequest+thrownError); }
				})
			}
		}
	}


	function isname_registered(){
		var username=$("#username").val();
		if(username ==''){
			err_msg('请填写用户名', 'username');
		}else{
			if(getStrLength(username) < 4 ){
				err_msg('用户名不能小于4个字符', 'username');
				$("#username").focus();
			}else{
				$.ajax({
					type:'post', //传送的方式,get/post
					url:'<?php echo U("User/isname_registered");?>', //发送数据的地址
					data:{username:username},
					dataType: "json",
					success:function(data)
					{
						err_msg(data.info, 'username');
						$('#dusername').hide();
					},
					//error:function (event, XMLHttpRequest, ajaxOptions, thrownError) {alert("表单错误，"+XMLHttpRequest+thrownError); }
				})
			}
		}
	}

	function checkphone(){
		var miss=$("#miss").val();
		if(miss ==''){
			err_msg('请填写短信验证码', 'miss');
		}else{
			$.ajax({
				type:'post', //传送的方式,get/post
				url:'<?php echo U("User/checkphone");?>', //发送数据的地址
				data:{miss:miss},
				dataType: "json",
				success:function(data)
				{
					err_msg(data.info, 'miss');
					$('#dmiss').hide();
				},
				error:function (event, XMLHttpRequest, ajaxOptions, thrownError) {alert("表单错误，"+XMLHttpRequest+thrownError); }
			})
		}
	}

	function check_password(password){
		if(password.length < 6 ){
			err_msg('密码不能小于6位', 'password1');
			return false;
		}else{
			err_msg('', 'password1');
			$('#dpassword1').hide();
		}
		conform_password = document.getElementById('conform_password').value;
		if(conform_password!=''){
			if ( conform_password != password ){
				err_msg('两次密码不一致', 'conform_password');
				$("#conform_password").focus();
			}else{
				err_msg('', 'password1');
				err_msg('', 'conform_password');
				$('#dpassword1,#dconform_password').hide();
			}
		}
	}

	function check_conform_password( conform_password )
	{
		password = document.getElementById('password1').value;

		if ( conform_password.length < 6 ){
			err_msg('密码不能小于6位', 'conform_password');
			//$("#conform_password").focus();
			return false;
		}
		if ( conform_password != password ){
			err_msg('两次密码不一致', 'conform_password');
			return false;
			//$("#conform_password").focus();
		}else{
			err_msg('', 'password1');
			err_msg('', 'conform_password');
			$('#dpassword1,#dconform_password').hide();
		}
	}

	function register(){
		var username=$("#username").val();
		if(username ==''){
			err_msg('请填写用户名', 'username');
			$("#username").focus();
			return false;
		}
		if(document.getElementById('dusername').innerHTML!="") {
			$("#username").focus();
			return false;
		}

		var password=$("#password1").val();
		if(password ==''){
			err_msg('请填写设置密码', 'password1');
			$("#password1").focus();
			return false;
		}else{
			err_msg('', 'password1');
			$('#dpassword1').hide();
		}

		var conform_password=$("#conform_password").val();
		if(conform_password ==''){
			err_msg('请填写确认密码', 'conform_password');
			$("#conform_password").focus();
			return false;
		}else{
			err_msg('', 'conform_password');
			$('#dconform_password').hide();
		}
		if(password!=conform_password){
			err_msg('两次密码不一致，请重新填写', 'conform_password');
			$("#conform_password").focus();
			return false;
		}else{
			err_msg('', 'conform_password');
			$('#dconform_password').hide();
		}

		var mobile_phone=$("#mobile_phone").val();
		if(mobile_phone ==''){
			err_msg('请填写手机号码', 'mobile_phone');
			$("#mobile_phone").focus();
			return false;
		}
		if(document.getElementById('dmobile_phone').innerHTML!="" && document.getElementById('dmobile_phone').innerHTML!="短信已发送到您的手机，请查看") {
			$("#mobile_phone").focus();
			return false;
		}


		var vericode=$("#vericode").val();//随机验证码
		if(vericode ==''){
			err_msg('请填写随机验证码', 'vericode');
			$("#vericode").focus();
			return false;
		}

		var miss=$("#miss").val();
		if(miss ==''){
			err_msg('请填写短信验证码', 'miss');
			$("#miss").focus();
			return false;
		}
		if(document.getElementById('dmiss').innerHTML!="") {
			$("#miss").focus();
			return false;
		}
		return true;
	}
</script>

</html>