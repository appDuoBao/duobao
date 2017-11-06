<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE >
<html>
<head>
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<link href="/Public/Weixin/css/styles.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="/Public/Weixin/css/style.css"/>
<link rel="stylesheet" type="text/css" href="/Public/Weixin/css/iconfont2/iconfont.css" />
<link rel="stylesheet" type="text/css" href="/Public/Weixin/css/font/iconfont.css"/>
<link rel="stylesheet" type="text/css" href="/Public/Weixin/css/font2/iconfont.css"/>
<script src="/Public/Weixin/js/script.js"/></script>
<script type="text/javascript" src="/Public/Weixin/js/jquery.min.js"></script>
<script type="text/javascript" src="/Public/Weixin/js/swiper.jquery.min.js"></script>
<script type="text/javascript" src="/Public/Weixin/js/common.js" ></script>
<style>
.controls{
	padding-left: 1.2rem;
	padding-right: 1.2rem;
}
	#ewshop_center_input{
		display: block;
		width: 100%;
		height: 1.4rem;
		font-size: 0.5rem;
		padding: 0.3rem;
	}
	#reason{
		resize: none;
		width: 100%;
		height: 4.4rem;
		border-style:none;
		margin-top: .5rem;
		font-size: 0.5rem;
		padding: 0.3rem;
	}
	.submit{
		padding-left: 1.2rem;
		padding-right: 1.2rem;
	}
	.btn_submit_pay{
		display: block;
		border-style: none;
		width: 100%;
		height: 1.4rem;
		border-radius: 20px;
		margin-top: .5rem;
		font-size: .7rem;
		color: #fff;
		font-weight: bold;
		background-color: #C00;
	}
</style>
<title></title>

</head>
<body>

<!-- 头部 -->
<!-- 头部结束 -->
<div class="bcy_body">
 
<span  style="display: block;padding:15px;font-size:0.8rem;text-align:center;color:red;margin:20px 0;"><?php echo ($msg); ?> &nbsp;&nbsp;<?php echo ($id); ?></span>
<form action='<?php echo U();?>' method="post" name="actform" style="padding:2%;" onsubmit="return doCheck()">
    <input type="hidden" name="id" value="<?php echo ($id); ?>">
    <div id="write">
    <ul> <li><div class="form-item">
    <!--<label class="item-label">提示:<span class="red"><?php echo ($tip); ?></span> </label>-->
    </div></li>
    <li>
    <div class="form-item">
    <label class="item-label"><span class="check-tips"></span> </label>
    <div class="controls">
    	<input type="text" class="text input-large" placeholder="说明" id="ewshop_center_input" name="title" >				
    </div>
    </div></li>
    <li>
    <div class="form-item">
    <label class="item-label"><span class="check-tips"></span> </label>
    <div class="controls">
    <textarea  placeholder="描述"  rows="9" cols="60" name="reason" id="reason"></textarea>				
    </div>
    </div></li>
    <li>
    	<div class="submit">
    		<input type="submit" class="btn_submit_pay" value="提交" />
    	</div>
    </li>
    </ul> 
    </div>
</form>

 
</div>
 <script type="text/javascript">
    function doCheck(){
      var title = $('#title').val();
      var reason = $('#reason').val();
      if(title == ''){
        alert("标题不能为空！");
        $('#title').focus();
        return false;
      }else if(reason == '') {
        alert("说明不能为空！");
        $('#reason').focus();
        return false;
      }else{
        return true;
      }
    }
  </script>
<footer id="w_footer">				<ul class="w_footerNav">					<li>						<a href="/weixin/index.html" class="w_nowColl" >							<p class="iconfont2">&#xe699;</p>							<p>首页</p>						</a>					</li>					<li>						<a href="/weixin/Goods/index.html">							<p style="font-size: 0.8rem;position:relative;top: 0.18rem;" class="iconfont2">&#xe619;</p>							<p>分类</p>						</a>					</li>					<li>						<a href="<?php echo U('Shopcart/index');?>">							<p style="font-size: 1.2rem;" class="iconfont2">&#xe614;</p>							<p>购物车</p>						</a>					</li>					<li style="border-right: none;">						<a href="/weixin/Center/index.html">							<p class="iconfont2">&#xe67f;</p>							<p>我的</p>						</a>					</li>				</ul>			</footer>			<div class="w_homeMao">				<div>					<a href="#"><i class="iconfont2">&#xe603;</i>						<p>客服</p>					</a>				</div>				<div>					<a href="#w_caseTitle">						<i class="iconfont2">&#xe69b;</i>					顶部</a>					<a href="#" class="w_homeMao-a"><i class="iconfont2">&#xe603;</i>					客服</a>				</div>			</div>		</div>	</body></html>