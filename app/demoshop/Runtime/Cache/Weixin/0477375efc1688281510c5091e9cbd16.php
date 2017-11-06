<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<title>找回密码-<?php echo C('WEB_SITE_TITLE');?></title>
	<meta name="keywords" content="<?php echo C('WEB_SITE_KEYWORD');?>"/>
	<meta name="description" content="<?php echo C('WEB_SITE_DESCRIPTION');?>" />
	<link rel="stylesheet" href="/Public/Weixin/css/css.css" />
	<script type="text/javascript" src="/Public/Weixin/js/jquery.min.js" ></script>
	<script  type="text/javascript" src="/Public/Weixin/js/common.js"></script>
</head>
<body>
<div class="xd-box-zhuce">
	<form class="form-horizontal" role="form" action="/index.php?s=/Weixin/User/repassword.html" method="post" name="formUser" id="formUser"  onsubmit="return submitPwdInfo();">
	<div  class="xd-zhuce-list">
		<ul>
			<li>
				<input type="text" name="mobile_phone" id="mobile_phone" onBlur="ismobile_registered();" placeholder="请填写您的手机号码" />
				<span class="check_tips error_tip" id="dmobile_phone"></span>
			</li>
			<li>
				<div class="miss_yzm">
					<input type="text" name="miss" id="miss"  onBlur="checkphone();"  placeholder="填写验证码" />
					<span class="check_tips error_tip" style="top: 2px;" id="dmiss"></span>
				</div>
				<a style="cursor:pointer;" id="sendma" name="sendma" onClick="sendphone();">获取验证码</a>
				
			</li>
			<li>
				<input type="password"  name="password" id="password"  placeholder="设置新密码" />
				<span id="dpassword"  class="error_tip"></span>
			</li>
			<li>
				<input type="password" type="password" name="repassword" id="repassword" placeholder="确认新密码" />
				<span id="drepassword" class="error_tip"></span>
			</li>
		</ul>
		<a href="javascript:void(0);" onclick="doSubmit();" class="xd-zhuce-a">保存</a>
	</div>
    </form>
</div>
</body>
<script type="text/javascript">
	function doSubmit(){
		alert(123);
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
	function sendphone(){
		if(document.getElementById('dmobile_phone').innerHTML!="" && document.getElementById('dmobile_phone').innerHTML!="短信已发送到您的手机，请查看") {
			$("#mobile_phone").focus();
			err_msg('请填写手机号码', 'mobile_phone');
			return;
		}
		var phone=$("#mobile_phone").val();
		if(phone.length!=11||isNaN(phone)){
			err_msg('这个不是个正确的手机号码', 'mobile_phone');
			$("#mobile_phone").focus();
			return;
		}else{
			$.ajax({
				type:'post', //传送的方式,get/post
				url:'<?php echo U("User/sendgetphone");?>', //发送数据的地址
				data:{phone:phone,send_code:<?php if($_SESSION['send_code']){echo $_SESSION['send_code'];}else{echo '8888';} ?>},
				dataType: "json",
				success:function(data)
				{
					if(data.status=="1"){
						$("#uid").val(data.uid);
						err_msg(data.info, 'mobile_phone');
					}else{
						err_msg(data.info, 'mobile_phone');
					}
				},
				//error:function (event, XMLHttpRequest, ajaxOptions, thrownError) {alert("表单错误，"+XMLHttpRequest+thrownError); }
			})
			backcount();
		}
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
				$.ajax({
					type:'post', //传送的方式,get/post
					url:'<?php echo U("User/ismobile_registeredd");?>', //发送数据的地址
					data:{mobile_phone:mobile_phone},
					dataType: "json",
					success:function(data)
					{
						err_msg(data.info, 'mobile_phone');
						$('#dmobile_phone').hide();
					},
					//error:function (event, XMLHttpRequest, ajaxOptions, thrownError) {alert("表单错误，"+XMLHttpRequest+thrownError); }
				})
			}
		}
	}

	function checkphone(){
		var miss=$("#miss").val();
		if(miss==''){
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
				//error:function (event, XMLHttpRequest, ajaxOptions, thrownError) { }
			})
		}
	}

	function submitPwdInfo(){
		var mobile_phone=$("#mobile_phone").val();
		var password=$("#password").val();
		if(mobile_phone ==''){
			err_msg('请填写手机号码', 'mobile_phone');
			$("#mobile_phone").focus();
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

		if(password ==''){
			err_msg('请设置新密码', 'password');
			$("#password").focus();
			return false;
		}
		var repassword=$("#repassword").val();
		if(repassword ==''){
			err_msg('请填写确认密码', 'repassword');
			$("#repassword").focus();
			return false;
		}
		if(password!=repassword){
			err_msg('两次密码不一致，请重新填写', 'repassword');
			$("#repassword").focus();
			return false;
		}else{
			err_msg('', 'conform_password');
			$('#drepassword').hide();
		}
	}
</script>
</html>