<?php if (!defined('THINK_PATH')) exit();?><!doctype html>

<html lang="en">
<head>
<meta charset="UTF-8">
<title>欢迎您登录代理管理系统</title>
<link rel="stylesheet" type="text/css" href="/Public/Agent/css/login.css" media="all">
<link rel="stylesheet" type="text/css" href="/Public/Agent/css/<?php echo (C("COLOR_STYLE")); ?>.css" media="all">
</head>

<body id="login-page">
<div id="main-content"> 
  
  <!-- 主体 -->
  
  <div class="login-body">
    <div class="login-main pr">
      <form  id="formUser" action="<?php echo U('resetps');?>" method="post" class="login-form">
        <h3 class="welcome">代理管理系统</h3>
        <div id="itemBox" class="item-box">
          <div class="item"> <i class="icon-login-user"></i>
            <input type="text" id="mobile" name="username" placeholder="请填写手机号" autocomplete="off" />
            <span class="check_tips error_tip" id="dmobile" style="right: 2.5rem;"></span>
          </div>
          <span class="placeholder_copy placeholder_un">请填写用户名</span>
          <div class="item b0"> <i class="icon-login-pwd"></i>
            <input type="password" id="rpass" name="password" placeholder="请填写密码" autocomplete="off" />
          </div>
          <div class="item b0"> <i class="icon-login-pwd"></i>
            <input type="password" id="pass" name="repassword" placeholder="请填写密码" autocomplete="off" />
          </div>
          <span class="placeholder_copy placeholder_pwd">请填写密码</span> </div>
            
            <div class="item b0"> <i class="icon-login-pwd"></i>
            <input type="text" name="miss" style="width:88px" id="miss" oninput="checkphone();" placeholder="验证码">
            <span class="check_tips error_tip" id="dmiss" style="right: 2.5rem;"></span>
        </div>
			  <div class="login_btn_panel">
			    <button type="button" id="sendma" style="width:98px;" onclick="sendphone();" name="sendma">获取验证码</button>
			</div>
          
        <div class="login_btn_panel">
          <button class="login-btn" onclick="register()" type="button"> <span class="in"><i class="icon-loading"></i>修改中 ...</span> <span class="on">修改</span> </button>
          <div class="check-tips"></div>
        </div>
      </form>
    </div>
  </div>
</div>

<!--[if lt IE 9]>

    <script type="text/javascript" src="/Public/static/jquery-1.10.2.min.js"></script>

    <![endif]--> 

<!--[if gte IE 9]><!--> 

<script type="text/javascript" src="/Public/static/jquery-2.0.3.min.js"></script> 

<!--<![endif]--> 

<script type="text/javascript">

    	/* 登陆表单获取焦点变色 */

    	$(".login-form").on("focus", "input", function(){

            $(this).closest('.item').addClass('focus');

        }).on("blur","input",function(){

            $(this).closest('.item').removeClass('focus');

        });



    	//表单提交

    	$(document)

	    	.ajaxStart(function(){

	    		$("button:submit").addClass("log-in").attr("disabled", true);

	    	})

	    	.ajaxStop(function(){

	    		$("button:submit").removeClass("log-in").attr("disabled", false);

	    	});



    	$("form").submit(function(){

    		var self = $(this);

    		$.post(self.attr("action"), self.serialize(), success, "json");

    		return false;



    		function success(data){

    			if(data.status){

    				window.location.href = data.url;

    			} else {

    				self.find(".check-tips").text(data.info);

    				//刷新验证码

    				$(".reloadverify").click();

    			}

    		}

    	});



		$(function(){

			//初始化选中用户名输入框

			$("#itemBox").find("input[name=username]").focus();

			//刷新验证码

			var verifyimg = $(".verifyimg").attr("src");

            $(".reloadverify").click(function(){

                if( verifyimg.indexOf('?')>0){

                    $(".verifyimg").attr("src", verifyimg+'&random='+Math.random());

                }else{

                    $(".verifyimg").attr("src", verifyimg.replace(/\?.*$/,'')+'?'+Math.random());

                }

            });



            //placeholder兼容性

                //如果支持 

            function isPlaceholer(){

                var input = document.createElement('input');

                return "placeholder" in input;

            }

                //如果不支持

            if(!isPlaceholer()){

                $(".placeholder_copy").css({

                    display:'block'

                })

                $("#itemBox input").keydown(function(){

                    $(this).parents(".item").next(".placeholder_copy").css({

                        display:'none'

                    })                    

                })

                $("#itemBox input").blur(function(){

                    if($(this).val()==""){

                        $(this).parents(".item").next(".placeholder_copy").css({

                            display:'block'

                        })                      

                    }

                })

                

                

            }

		});
		
		var secs = 60;
	function backcount(){
		$(".yzm").attr("onClick",'');
		for(i=1;i<=secs;i++) {
			window.setTimeout("update1(" + i + ")", i * 1000);
		}
	}
	function update1(num) {
		if(num == secs) {
			$("#sendma").html('获取验证码');
			$(".yzm").attr("onClick",'sendphone();');
		}else {
			printnr = secs-num;
			$("#sendma").html("(" + printnr +")秒后重发");
		}
	}
	function err_msg(str, id) {
		document.getElementById('d'+id).style.display = "inline-block";
		document.getElementById('d'+id).innerHTML=str;
	}

	function ismobile_registered(){
		var mobile=$("#mobile").val();
		if(mobile ==''){
			err_msg('请填写手机号码', 'mobile');
		}else{
			if(!(/^1[34578]\d{9}$/.test(mobile))){
				err_msg('不正确的手机号码', 'mobile');
			}else{
				err_msg('', 'mobile');
//				$.ajax({
//					type:'post', //传送的方式,get/post
//					url:'<?php echo U("User/ismobile_registered");?>', //发送数据的地址
//					data:{mobile:mobile},
//					dataType: "json",
//					success:function(data)
//					{
//						err_msg(data.info, 'mobile');
//					}
//				})
			}
		}
	}


	function sendphone(){
		var phone=$("#mobile").val();
		if(!(/^1[34578]\d{9}$/.test(phone))){
			err_msg('不正确的手机号码', 'mobile');
			return false;
		}
		if($('#dmobile').html()=="手机号已经被使用" || $('#dmobile').html()=="短信已发送") {
			return false;
		}
		$.ajax({
			type:'post', //传送的方式,get/post
			url:'<?php echo U("public/sendphone");?>', //发送数据的地址
			data:{phone:phone},
			dataType: "json",
			success:function(data){
				err_msg(data.info, 'mobile');
			}
		})
		backcount();
	}

	function checkphone(){
		var miss=$("#miss").val();
		if(miss ==''){
			err_msg('请填写验证码', 'miss');
		}else{
			$.ajax({
				type:'post', //传送的方式,get/post
				url:'<?php echo U("public/checkphone");?>', //发送数据的地址
				data:{miss:miss},
				dataType: "json",
				success:function(data)
				{
					err_msg(data.info, 'miss');
				}
			})
		}
	}
	function register(){
		var mobile=$("#mobile").val();
		if(mobile ==''){
			err_msg('请填写手机号码', 'mobile');
			return false;
		}
		if($('#dmobile').html() != "" && $('#dmobile').html() != "短信已发送") {
			return false;
		}
		var miss=$("#miss").val();
		if(miss ==''){
			alert('请填写验证码');
			return false;
		}
		if($('#dmiss').html() != "") {
			alert('验证码不正确');
			return false;
		}
		if($("#pass").val() == "" || $("#rpass")== ""){
		    alert('密码不正确');
			return false;
		 }
		$('#formUser').submit();//提交
	}

    </script>
</body>
</html>