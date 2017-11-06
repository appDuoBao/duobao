<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<title>会员登录-<?php echo C('WEB_SITE_TITLE');?></title>
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
	<div  class="xd-zhuce-list  xd-denglu-list1">
		<ul>
			<li>
				<input type="text" name="username" id="inputusername" placeholder="请输入用户名或手机号" /></li>
			<li>
				<input type="password" name="password" id="inputpassword" placeholder="请输入密码" /></li>
			<li>
				<a href="<?php echo U('User/repassword');?>">忘记密码?</a>
			</li>
			<div class="dl_t_tishi" id="J_Message" style="display: none;"><i></i>提示： <a id="errortext"></a></div>
		</ul>
		<a href="javascript:void(0);" onclick="act_login();" class="xd-zhuce-a">登录</a>
		<a href="<?php echo U('User/register');?>" class="xd-zhuce-a xd-zhuce-a1">注册</a>
	</div>
</div>
</body>
</html>
<script type="text/javascript">
	//提交表单
	function act_login(){
		var username=$("#inputusername").val();
		var password=$("#inputpassword").val();
		if(username ==''){
			$("#errortext").text('用户名不能为空！');
			$("#J_Message").show();
			$("#inputusername").focus();
		}else if(password ==''){
			$("#errortext").text('密码不能为空！');
			$("#J_Message").show();
			$("#inputpassword").focus();
		}else{
			$.post("<?php echo U('User/doLogin');?>",{username:username,password:password},function(data){
				if(data.status == 1){
					window.location.href = "<?php echo U('Index/index');?>";
				}else if(data.status == 0){
					if(data.msg){
						$("#errortext").text(data.msg);
						$("#J_Message").show();
					}
				}
			});
		}
	}
</script>