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
<?php if(empty($list)): ?><div class="personal_info_none_goods bcy_dpl_top_nomessage">
            <p>没有优惠券信息！<a href='<?php echo U("index/index");?>' class="red">去首页</a></p>
        </div>
	<?php else: ?>
 <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="you-wrap">
		<div class="you-left keyong">
			￥<span><?php echo ($vo["price"]); ?></span>
			<img class="jt-bot" src="images/jt.png"/>
		</div>
		<div class="you-right">
			<p><?php echo ($vo["name"]); ?></p>
			<span>有效期：<?php echo ($vo["start_time"]); ?> ～<?php echo ($vo["end_time"]); ?></span>
			 <?php if($vo['isguoqi'] != ''): ?><img src="/Public/Weixin/images/gq.png"/>
			 <?php else: ?>
			<img src="/Public/Weixin/images/ky.png"/><?php endif; ?>
		</div>
	</div><?php endforeach; endif; else: echo "" ;endif; endif; ?>   
<footer id="w_footer">				<ul class="w_footerNav">					<li>						<a href="/weixin/index.html" class="w_nowColl" >							<p class="iconfont2">&#xe699;</p>							<p>首页</p>						</a>					</li>					<li>						<a href="/weixin/Goods/index.html">							<p style="font-size: 0.8rem;position:relative;top: 0.18rem;" class="iconfont2">&#xe619;</p>							<p>分类</p>						</a>					</li>					<li>						<a href="<?php echo U('Shopcart/index');?>">							<p style="font-size: 1.2rem;" class="iconfont2">&#xe614;</p>							<p>购物车</p>						</a>					</li>					<li style="border-right: none;">						<a href="/weixin/Center/index.html">							<p class="iconfont2">&#xe67f;</p>							<p>我的</p>						</a>					</li>				</ul>			</footer>			<div class="w_homeMao">				<div>					<a href="#"><i class="iconfont2">&#xe603;</i>						<p>客服</p>					</a>				</div>				<div>					<a href="#w_caseTitle">						<i class="iconfont2">&#xe69b;</i>					顶部</a>					<a href="#" class="w_homeMao-a"><i class="iconfont2">&#xe603;</i>					客服</a>				</div>			</div>		</div>	</body></html>