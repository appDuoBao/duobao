<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>充值卡排行榜</title>
	<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<!--标准mui.css-->
	<link rel="stylesheet" href="Public/Weixin/css/mui.min.css">
	<!--App自定义的css-->
	<link rel="stylesheet" type="text/css" href="Public/Weixin/css/app.css" />
	<link rel="stylesheet" type="text/css" href="Public/Weixin/css/ranklist.css" />
	<script src="Public/Weixin/js/flexible.js"></script> 
</head>
<body>
<style>
	
</style>
	<header class="mui-bar mui-bar-nav ph_head">
		<a class="mui-icon mui-icon-arrowleft ph_lefticon"></a>
		<h1 class="mui-title">排行榜</h1>
	</header>
	<div class="mui-content">
		<div class="navbox"></div>
		<div class="mui-slider-indicator mui-segmented-control mui-segmented-control-inverted ph_nav">
		    <?php if($type == to): ?><a class="mui-control-item mui-active" href="#item1">
				日榜
			</a>
			<a class="mui-control-item"   href="<?php echo U('Index/topsort?t=mo');?>">
				周榜
			</a>
		    <?php else: ?>
		        <a class="mui-control-item " href="<?php echo U('Index/topsort?t=to');?>">
				日榜
			</a>
			<a class="mui-control-item mui-active" href="#item2">
				周榜
			</a><?php endif; ?>
			<!-- <a class="mui-control-item" href="#item3">
				月榜
			</a> -->
		</div>
		<!-- 日榜 -->
		<div id="item1" class="mui-slider-item mui-control-content mui-active">
			<div class="ph_onnumlist clearfix">
				<div class="mui-pull-left ph_rankl">
					NO.1
				</div>
				<div class="ph_haveone">
					<img src="Public/Weixin/images/crown.png" class="ph_crown">
					<div class="ph_haveoneup">
						<img class="ph_zimg" src="<?php echo ($one["userinfo"]["headimgurl"]); ?>" />
						<img src="Public/Weixin/images/bj_01.png" class="ph_crown2">
					</div>
					<b class="ph_word2"><?php echo ($one["userinfo"]["nickname"]); ?></b>
					<p>已获胜：<i><?php echo ($one["num"]); ?></i>单</p>
				</div>
			</div>

			<ul class="mui-table-view mui-table-view-striped mui-table-view-condensed">
				<li class="mui-table-view-cell ph_numlist clearfix">
					<div class="ph_rank2">NO.2</div>
					<div class="mui-slider-cell ph_listnr">
						<div class="oa-contact-cell mui-table">
							<div class="oa-contact-avatar mui-table-cell">
								<img class="ph_img2" src="<?php echo ($two["userinfo"]["headimgurl"]); ?>" />
							</div>
							<div class="oa-contact-content mui-table-cell">
								<div class="mui-clearfix ph_word">
									<h4 class="oa-contact-name"><?php echo ($two["userinfo"]["nickname"]); ?></h4>
									<span class="oa-contact-position">已获胜：<i><?php echo ($two["num"]); ?></i>单</span>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="mui-table-view-cell ph_numlist clearfix">
					<div class="ph_rank3">NO.3</div>
					<div class="mui-slider-cell ph_listnr">
						<div class="oa-contact-cell mui-table">
							<div class="oa-contact-avatar mui-table-cell">
								<img class="ph_img3" src="<?php echo ($three["userinfo"]["headimgurl"]); ?>" />
							</div>
							<div class="oa-contact-content mui-table-cell">
								<div class="mui-clearfix ph_word">
									<h4 class="oa-contact-name"><?php echo ($three["userinfo"]["nickname"]); ?></h4>
									<span class="oa-contact-position">已获胜：<i><?php echo ($three["num"]); ?></i>单</span>
								</div>
							</div>
						</div>
					</div>
				</li>
				 <?php if(!empty($users)): if(is_array($users)): $k = 0; $__LIST__ = $users;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li class="mui-table-view-cell ph_numlist clearfix">
					<div class="ph_rank4">NO.<?php echo ($k+3); ?></div>
					<div class="mui-slider-cell ph_listnr">
						<div class="oa-contact-cell mui-table">
							<div class="oa-contact-avatar mui-table-cell">
								<img class="ph_img4" src="<?php echo ($vo["userinfo"]["headimgurl"]); ?>" />
							</div>
							<div class="oa-contact-content mui-table-cell">
								<div class="mui-clearfix ph_word">
									<h4 class="oa-contact-name"><?php echo ($vo["userinfo"]["nickname"]); ?></h4>
									<span class="oa-contact-position">已获胜：<i><?php echo ($vo["num"]); ?></i>单</span>
								</div>
							</div>
						</div>
					</div>
				</li><?php endforeach; endif; else: echo "" ;endif; endif; ?> 
				
			</ul>



		</div>
		<!-- 日榜 end-->
		<!-- 周榜 -->
		<div id="item2" class="mui-slider-item mui-control-content">



			<div class="ph_onnumlist clearfix">
				<div class="mui-pull-left ph_rankl">
					NO.1
				</div>
				<div class="ph_haveone">
					<img src="Public/Weixin/images/crown.png" class="ph_crown">
					<div class="ph_haveoneup">
						<img class="ph_zimg" src="<?php echo ($one["headimgurl"]); ?>" />
						<img src="Public/Weixin/images/bj_01.png" class="ph_crown2">
					</div>
					<b class="ph_word2">Sunshine</b>
					<p>已获胜：<i>49</i>单</p>
				</div>
			</div>

			<ul class="mui-table-view mui-table-view-striped mui-table-view-condensed">
				<li class="mui-table-view-cell ph_numlist clearfix">
					<div class="ph_rank2">NO.2</div>
					<div class="mui-slider-cell ph_listnr">
						<div class="oa-contact-cell mui-table">
							<div class="oa-contact-avatar mui-table-cell">
								<img class="ph_img2" src="Public/Weixin/images/logo2.png" />
							</div>
							<div class="oa-contact-content mui-table-cell">
								<div class="mui-clearfix ph_word">
									<h4 class="oa-contact-name">小小鱼2</h4>
									<span class="oa-contact-position">已获胜：<i>49</i>单</span>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="mui-table-view-cell ph_numlist clearfix">
					<div class="ph_rank3">NO.3</div>
					<div class="mui-slider-cell ph_listnr">
						<div class="oa-contact-cell mui-table">
							<div class="oa-contact-avatar mui-table-cell">
								<img class="ph_img3" src="Public/Weixin/images/logo.png" />
							</div>
							<div class="oa-contact-content mui-table-cell">
								<div class="mui-clearfix ph_word">
									<h4 class="oa-contact-name">某某某人2</h4>
									<span class="oa-contact-position">已获胜：<i>49</i>单</span>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="mui-table-view-cell ph_numlist clearfix">
					<div class="ph_rank4">NO.4</div>
					<div class="mui-slider-cell ph_listnr">
						<div class="oa-contact-cell mui-table">
							<div class="oa-contact-avatar mui-table-cell">
								<img class="ph_img4" src="Public/Weixin/images/logo.png" />
							</div>
							<div class="oa-contact-content mui-table-cell">
								<div class="mui-clearfix ph_word">
									<h4 class="oa-contact-name">某某某人</h4>
									<span class="oa-contact-position">已获胜：<i>49</i>单</span>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="mui-table-view-cell ph_numlist clearfix">
					<div class="ph_rank4">NO.5</div>
					<div class="mui-slider-cell ph_listnr">
						<div class="oa-contact-cell mui-table">
							<div class="oa-contact-avatar mui-table-cell">
								<img class="ph_img4" src="Public/Weixin/images/logo2.png" />
							</div>
							<div class="oa-contact-content mui-table-cell">
								<div class="mui-clearfix ph_word">
									<h4 class="oa-contact-name">某某某人</h4>
									<span class="oa-contact-position">已获胜：<i>29</i>单</span>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="mui-table-view-cell ph_numlist clearfix">
					<div class="ph_rank4">NO.6</div>
					<div class="mui-slider-cell ph_listnr">
						<div class="oa-contact-cell mui-table">
							<div class="oa-contact-avatar mui-table-cell">
								<img class="ph_img4" src="Public/Weixin/images/logo2.png" />
							</div>
							<div class="oa-contact-content mui-table-cell">
								<div class="mui-clearfix ph_word">
									<h4 class="oa-contact-name">某某某人</h4>
									<span class="oa-contact-position">已获胜：<i>24</i>单</span>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="mui-table-view-cell ph_numlist clearfix">
					<div class="ph_rank4">NO.7</div>
					<div class="mui-slider-cell ph_listnr">
						<div class="oa-contact-cell mui-table">
							<div class="oa-contact-avatar mui-table-cell">
								<img class="ph_img4" src="Public/Weixin/images/logo2.png" />
							</div>
							<div class="oa-contact-content mui-table-cell">
								<div class="mui-clearfix ph_word">
									<h4 class="oa-contact-name">某某某人</h4>
									<span class="oa-contact-position">已获胜：<i>24</i>单</span>
								</div>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
		
		<!-- 周榜 end-->
		<!-- 月榜 -->
		<!-- <div id="item3" class="mui-slider-item mui-control-content">
			<div class="ph_onnumlist clearfix">
				<div class="mui-pull-left ph_rankl">
					NO.1
				</div>
				<div class="ph_haveone">
					<img src="img/crown.png" class="ph_crown">
					<div class="ph_haveoneup">
						<img class="ph_zimg" src="img/zpic2.png" />
						<img src="img/bj_01.png" class="ph_crown2">
					</div>
					<b class="ph_word2">Sunshine</b>
					<p>已获胜：<i>49</i>单</p>
				</div>
			</div>

			<ul class="mui-table-view mui-table-view-striped mui-table-view-condensed">
				<li class="mui-table-view-cell ph_numlist clearfix">
					<div class="ph_rank2">NO.2</div>
					<div class="mui-slider-cell ph_listnr">
						<div class="oa-contact-cell mui-table">
							<div class="oa-contact-avatar mui-table-cell">
								<img class="ph_img2" src="img/logo2.png" />
							</div>
							<div class="oa-contact-content mui-table-cell">
								<div class="mui-clearfix ph_word">
									<h4 class="oa-contact-name">小鱼儿</h4>
									<span class="oa-contact-position">已获胜：<i>49</i>单</span>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="mui-table-view-cell ph_numlist clearfix">
					<div class="ph_rank3">NO.3</div>
					<div class="mui-slider-cell ph_listnr">
						<div class="oa-contact-cell mui-table">
							<div class="oa-contact-avatar mui-table-cell">
								<img class="ph_img3" src="img/logo.png" />
							</div>
							<div class="oa-contact-content mui-table-cell">
								<div class="mui-clearfix ph_word">
									<h4 class="oa-contact-name">某某某人</h4>
									<span class="oa-contact-position">已获胜：<i>49</i>单</span>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="mui-table-view-cell ph_numlist clearfix">
					<div class="ph_rank4">NO.4</div>
					<div class="mui-slider-cell ph_listnr">
						<div class="oa-contact-cell mui-table">
							<div class="oa-contact-avatar mui-table-cell">
								<img class="ph_img4" src="img/logo.png" />
							</div>
							<div class="oa-contact-content mui-table-cell">
								<div class="mui-clearfix ph_word">
									<h4 class="oa-contact-name">某某某人</h4>
									<span class="oa-contact-position">已获胜：<i>49</i>单</span>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="mui-table-view-cell ph_numlist clearfix">
					<div class="ph_rank4">NO.5</div>
					<div class="mui-slider-cell ph_listnr">
						<div class="oa-contact-cell mui-table">
							<div class="oa-contact-avatar mui-table-cell">
								<img class="ph_img4" src="img/logo2.png" />
							</div>
							<div class="oa-contact-content mui-table-cell">
								<div class="mui-clearfix ph_word">
									<h4 class="oa-contact-name">某某某人</h4>
									<span class="oa-contact-position">已获胜：<i>29</i>单</span>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="mui-table-view-cell ph_numlist clearfix">
					<div class="ph_rank4">NO.6</div>
					<div class="mui-slider-cell ph_listnr">
						<div class="oa-contact-cell mui-table">
							<div class="oa-contact-avatar mui-table-cell">
								<img class="ph_img4" src="img/logo2.png" />
							</div>
							<div class="oa-contact-content mui-table-cell">
								<div class="mui-clearfix ph_word">
									<h4 class="oa-contact-name">某某某人</h4>
									<span class="oa-contact-position">已获胜：<i>24</i>单</span>
								</div>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div> -->
		<!-- 月榜 end-->
	</div>
	<script src="Public/Weixin/js/jquery-1.9.1.min.js"></script>
<!--<script src="Public/Weixin/js/mui.min.js"></script>-->
	<script>
		$(function(){  
	    var navH = $(".ph_nav").height();
	    //获取导航距离页面顶部的距离  
	    var toTopHeight = $(".ph_nav").offset().top;  
	      
	    //监听页面滚动  
	    $(window).scroll(function() {  
	        //判断页面滚动超过导航时执行的代码  
	        if( $(document).scrollTop() > 0){  
	            
	                $(".ph_nav").addClass("nav-fixed");
	                $(".navbox").height(navH);  
	 
	        }else  
	    });  
	});  
	
	</script>

</body>
</html>