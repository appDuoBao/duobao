<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<title><?php echo ($meta_title); ?>_<?php echo C('WEB_SITE_TITLE');?></title>
	<link rel="stylesheet" href="/Public/Weixin/css/font/iconfont.css" />
	<link rel="stylesheet" href="/Public/Weixin/css/style.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Weixin/css/iconfont2/iconfont.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Weixin/css/font/iconfont.css"/>
	<link rel="stylesheet" type="text/css" href="/Public/Weixin/css/font2/iconfont.css"/>
	<script type="text/javascript" src="/Public/Weixin/js/jquery.min.js" ></script>
	<script type="text/javascript" src="/Public/Weixin/js/common.js" ></script>
</head>
<body>
	<header class="user-wrap">
		<div class="user">
			<div class="user-img">
				<?php if(empty($faceid)): ?><img src="/Public/static/images/noface.jpg" width="120" height="120"/><?php else: ?><img src="<?php echo (get_cover($faceid,'path')); ?>"/><?php endif; ?>
			</div>
			<div class="user-info">
				<p><?php echo (get_nickname($uid)); ?></p>
				<!--<span>非会员</span>-->
			</div>
			<div class="user-btn">
				<a href="<?php echo U("center/information");?>" class="iconfont">&#xe662;</a>
			</div>
		</div>
	</header>
	<div class="user-1">
		<div class="user-1-wrap">
			<div>
				<a href="<?php echo U("center/needpay");?>">
					<p class="iconfont">&#xe621;</p>
					<span>待支付</span>
				</a>
			</div>
			<div>
				<a href="<?php echo U("center/tobeshipped");?>">
					<p class="iconfont">&#xe61e;</p>
					<span>待发货</span>
				</a>
			</div>
			<div>
				<a href="<?php echo U("center/tobeconfirmed");?>">
					<p class="iconfont">&#xe60e;</p>
					<span>待确定</span>
				</a>
			</div>
			<div>
				<a href="<?php echo U("center/valuator");?>">
					<p class="iconfont">&#xe676;</p>
					<span>待评价</span>
				</a>
			</div>
		</div>
	</div>
	<div class="user-2 mrt-20">
		<a href="<?php echo U("center/allorder");?>">
			<div class="user-2-wrap">
				<span class="iconfont">&#xe617;</span>
				<i>全部订单</i>
				<em class="iconfont">&#xe662;</em>
			</div>
		</a>
		<a href="<?php echo U('center/promotionyhj');?>">
			<div class="user-2-wrap">
				<span class="iconfont">&#xe703;</span>
				<i>我的优惠券</i>
				<em class="iconfont">&#xe662;</em>
			</div>
		</a>
		<a href="<?php echo U('center/collect');?>">
			<div class="user-2-wrap">
				<span class="iconfont">&#xe616;</span>
				<i>我的收藏</i>
				<em class="iconfont">&#xe662;</em>
			</div>
		</a>
		
		<!--<a href="<?php echo U('center/collect');?>">
			<div class="user-2-wrap">
				<span class="iconfont"><img src="/Public/static/images/qian.png"/></span>
				<i>我的钱包</i>
				<em class="iconfont">&#xe662;</em>
			</div>
		</a>
		
		<a href="<?php echo U('center/collect');?>">
			<div class="user-2-wrap">
				<span class="iconfont"><img src="/Public/static/images/fx-a.png"/></span>
				<i>分享他人</i>
				<em class="iconfont">&#xe662;</em>
			</div>
		</a>
	</div>-->
	<div class="user-2 mrt-20">
		<a href="<?php echo U('shopcart/index');?>">
			<div class="user-2-wrap">
				<span class="iconfont">&#xe614;</span>
				<i>购物车</i>
				<em class="iconfont">&#xe662;</em>
			</div>
		</a>
		<a href="<?php echo U('center/address');?>">
			<div class="user-2-wrap">
				<span class="iconfont">&#xe674;</span>
				<i>管理地址</i>
				<em class="iconfont">&#xe662;</em>
			</div>
		</a>
	</div>
	<div class="user-2 mrt-20" style="margin-bottom: 3rem;">
		<a href="<?php echo U('User/logout');?>">
			<div class="user-2-wrap">
				<span class="iconfont">&#xe674;</span>
				<i>退出</i>
				<em class="iconfont">&#xe662;</em>
			</div>
		</a>
	</div>
<footer id="w_footer">				<ul class="w_footerNav">					<li>						<a href="/weixin/index.html" class="w_nowColl" >							<p class="iconfont2">&#xe699;</p>							<p>首页</p>						</a>					</li>					<li>						<a href="/weixin/Goods/index.html">							<p style="font-size: 0.8rem;position:relative;top: 0.18rem;" class="iconfont2">&#xe619;</p>							<p>分类</p>						</a>					</li>					<li>						<a href="<?php echo U('Shopcart/index');?>">							<p style="font-size: 1.2rem;" class="iconfont2">&#xe614;</p>							<p>购物车</p>						</a>					</li>					<li style="border-right: none;">						<a href="/weixin/Center/index.html">							<p class="iconfont2">&#xe67f;</p>							<p>我的</p>						</a>					</li>				</ul>			</footer>			<div class="w_homeMao">				<div>					<a href="#"><i class="iconfont2">&#xe603;</i>						<p>客服</p>					</a>				</div>				<div>					<a href="#w_caseTitle">						<i class="iconfont2">&#xe69b;</i>					顶部</a>					<a href="#" class="w_homeMao-a"><i class="iconfont2">&#xe603;</i>					客服</a>				</div>			</div>		</div>	</body></html>