<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title><?php echo ($meta_title); ?>_<?php echo C('WEB_SITE_TITLE');?></title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link rel="stylesheet" type="text/css" href="/Public/Weixin/css/font/iconfont.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/Weixin/css/font2/iconfont.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/Weixin/css/swiper.min.css"/>
		<link rel="stylesheet" type="text/css" href="/Public/Weixin/css/style.css"/>
		<script type="text/javascript" src="/Public/Weixin/js/jquery.min.js" ></script>
		<script type="text/javascript" src="/Public/Weixin/js/common.js" ></script>
		<script type="text/javascript" src="/Public/Weixin/js/swiper.min.js" ></script>
	</head>
	<body>
		<div style="background-color: #fbf9fe;">
			<header id="w_caseTitle">
				<form action="<?php echo U('Search/index');?>" name="Searchform" method="post" onsubmit="return doSearch();">
					<input type="text" id="words" name="words" value="<?php echo ($keyword); ?>" placeholder="搜索一网全部商品" />
					<i class="iconfont2">&#x343b;</i>
					<input type="submit" value="搜索" />
				</form>
				<script>
					function doSearch(){
						var words =  $('#words').val();
						if(words == ''){
							alert('请输入关键词！');
							return false;
							$('#words').focus();
						}else{
							return true;
						}
					}
				</script>
			</header>
<div id="w_banner">
	<div class="swiper-container">
		<div class="swiper-wrapper">
		 <?php if(is_array($slide)): $i = 0; $__LIST__ = $slide;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
				<a href="<?php echo (get_nav_url($vo["url"])); ?>"><img src="<?php echo (get_cover($vo["icon"],'path')); ?>"></a>
			</div><?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
		<!-- Add Pagination -->
	</div>
	<div class="swiper-pagination"></div>
</div>
<style type="text/css">
	.swiper-pagination{
		bottom: 0px !important;
		text-align: center;
		display: block;
		width: 100%;
	}
	.swiper-pagination span{
		margin-right: 5px;
	}
	.swiper-container{
		/*height: 100%;*/
	}
</style>
<script>
	window.onload = function(){
		var swiper = new Swiper('.swiper-container', {
		pagination: '.swiper-pagination',
		paginationClickable: true,
		autoplay:2000
		});
	}
</script>
<nav id="w_homeNav">
	<ul class="w_homeNav_1">
	  <?php if(is_array($catlist)): $k = 0; $__LIST__ = $catlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k; if($k < 5): ?><li>
			<a href="<?php echo U('Goods/lists?category='.$vo['name']);?>">
				<div>
					<img src="<?php echo (get_cover($vo["icon"],'path')); ?>">
				</div>
				<p><?php echo ($vo["title"]); ?></p>
			</a>
		</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
	</ul>
	<ul class="w_homeNav_2">
	  <?php if(is_array($catlist)): $k = 0; $__LIST__ = $catlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k; if($k > 4): ?><li>
			<a href="<?php echo U('Goods/lists?category='.$vo['name']);?>">
				<div>
					<img src="<?php echo (get_cover($vo["icon"],'path')); ?>">
				</div>
				<p><?php echo ($vo["title"]); ?></p>
			</a>
		</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
	</ul>
</nav>
<div class="w_homeNews">
	<div class="w_homeNewsTitle">
		<h5>动态资讯</h5>
		<p id="indexList">
		  <?php if(is_array($newslist)): $i = 0; $__LIST__ = $newslist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U('service/shows?id='.$vo['id']);?>"><span><?php echo ($vo["title"]); ?></span></a><?php endforeach; endif; else: echo "" ;endif; ?>
		</p>
	</div>
	<script>
		var timer1 = setInterval(function(){
			var index = $("#indexList").attr("index"),
				h = parseInt($("#indexList").parent().css("height")),
				len = $("#indexList span").length;
			index = !index ? 1 : (index >= len ? 0 : index);
			$("#indexList").animate({"margin-top":"-"+(index * h)+"px"},500).attr("index",parseInt(index)+1);
		},3000);
	</script>
	<div class="w_homeNewsMain">
	 <?php if(is_array($jinpin1)): $i = 0; $__LIST__ = $jinpin1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="w_homeNewsMainLeft">
			<a href="<?php echo (get_nav_url($vo["url"])); ?>">
			<!--<p>￥98</p>-->
			<p><?php echo ($vo["title"]); ?></p>
			<p><?php echo ($vo["description"]); ?></p>
			<div class="w_homeNewsMainLeftImg">
				<img src="<?php echo (get_cover($vo["icon"],'path')); ?>" />
			</div>
			</a>
		</div><?php endforeach; endif; else: echo "" ;endif; ?>
		<div class="w_homeNewsMainRight">
		 <?php if(is_array($jinpin2)): $i = 0; $__LIST__ = $jinpin2;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="w_homeNewsMainRightTop">
				<a href="<?php echo (get_nav_url($vo["url"])); ?>">
				<div>
					<!--<p>￥98</p>-->
					<p><?php echo ($vo["title"]); ?></p>
					<p><?php echo ($vo["description"]); ?></p>
				</div>
				<div>
					<img src="<?php echo (get_cover($vo["icon"],'path')); ?>" />
				</div>
				</a>
			</div><?php endforeach; endif; else: echo "" ;endif; ?>
			<div class="w_homeNewsMainRightBottom">
			 <?php if(is_array($jinpin3)): $i = 0; $__LIST__ = $jinpin3;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div>
					<a href="<?php echo (get_nav_url($vo["url"])); ?>">
					<p><?php echo ($vo["title"]); ?></p>
					<p><?php echo ($vo["description"]); ?></p>
					<div>
						<img src="<?php echo (get_cover($vo["icon"],'path')); ?>" />
					</div>
					</a>
				</div><?php endforeach; endif; else: echo "" ;endif; ?>
				 <?php if(is_array($jinpin4)): $i = 0; $__LIST__ = $jinpin4;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div>
					<a href="<?php echo (get_nav_url($vo["url"])); ?>">
					<p><?php echo ($vo["title"]); ?></p>
					<p><?php echo ($vo["description"]); ?></p>
					<div>
						<img src="<?php echo (get_cover($vo["icon"],'path')); ?>" />
					</div>
					</a>
				</div><?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
		</div>
	</div>
</div>
<!-- 动态资讯部分 -->
<div class="w_goodsList">
	<div class="w_goodsListTitle">
		<h5>热销推荐</h5>
		<a href="#">更多 > </a>
	</div>
	<ul class="w_goodsListMain">
		 <?php if(is_array($remailist)): $k = 0; $__LIST__ = $remailist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li class="w_goodsListShow">
				<a href="<?php echo U('Goods/detail?id='.$vo['id']);?>">
				<div>
					<img src="<?php echo (get_cover($vo["cover_id"],'path')); ?>" />
				</div>
				<p><?php echo ($vo["title"]); ?></p>
				<p>￥ <?php echo ($vo["price"]); ?></p>
				</a>
			</li><?php endforeach; endif; else: echo "" ;endif; ?>
		<div style="clear: both;"></div>
	</ul>
</div>
<!-- 热销推荐部分 -->
<footer id="w_footer">				<ul class="w_footerNav">					<li>						<a href="/weixin/index.html" class="w_nowColl" >							<p class="iconfont2">&#xe699;</p>							<p>首页</p>						</a>					</li>					<li>						<a href="/weixin/Goods/index.html">							<p style="font-size: 0.8rem;position:relative;top: 0.18rem;" class="iconfont2">&#xe619;</p>							<p>分类</p>						</a>					</li>					<li>						<a href="<?php echo U('Shopcart/index');?>">							<p style="font-size: 1.2rem;" class="iconfont2">&#xe614;</p>							<p>购物车</p>						</a>					</li>					<li style="border-right: none;">						<a href="/weixin/Center/index.html">							<p class="iconfont2">&#xe67f;</p>							<p>我的</p>						</a>					</li>				</ul>			</footer>			<div class="w_homeMao">				<div>					<a href="#"><i class="iconfont2">&#xe603;</i>						<p>客服</p>					</a>				</div>				<div>					<a href="#w_caseTitle">						<i class="iconfont2">&#xe69b;</i>					顶部</a>					<a href="#" class="w_homeMao-a"><i class="iconfont2">&#xe603;</i>					客服</a>				</div>			</div>		</div>	</body></html>