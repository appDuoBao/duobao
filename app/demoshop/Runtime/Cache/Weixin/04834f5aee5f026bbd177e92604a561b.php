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
	<div class="xd-Navigation">
		<ul>
			<li><a href="<?php echo U("center/allorder");?>">全部</a></li>
			<li><a href="<?php echo U("center/needpay");?>">待付款</a></li>
			<li><a href="<?php echo U("center/tobeshipped");?>">待发货</a></li>
			<li class="hover"><a href="<?php echo U("center/tobeconfirmed");?>">待确认</a></li>
			<li><a href="<?php echo U("center/valuator");?>">待评价</a></li>

		</ul>
	</div>
<?php if(empty($tobeconfirmed)): ?><div class="xd-There">
		<img src="/Public/Weixin/images/xd/xd-2.png"/>
	  </div>
 <?php else: ?>
       <?php if(is_array($tobeconfirmed)): $i = 0; $__LIST__ = $tobeconfirmed;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$po): $mod = ($i % 2 );++$i;?><div class="xd-content">
			<h3>订单号：<meta name="format-detection"content="telphone=no"/> <?php echo ($po["tag"]); ?> </meta><span>
			 <?php $status=$po['status']; if($status==-1){ echo "待支付";}; if($status==1){ echo "待发货";}; if($status==2){ echo "已发货";}; if($status==3){ echo "交易成功";}; if($status==4){ echo "申请取消订单";}; if($status==5){ echo "取消订单被拒绝";}; if($status==6){ echo "订单已取消";}; ?>       
			</span></h3>
			    <?php if(is_array($po['id'])): $i = 0; $__LIST__ = $po['id'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="xd-list">
				<img src="<?php echo (get_cover(get_cover_id($vo["goodid"]),'path')); ?>" alt="" />
				<ul>
					<li><a href="<?php echo U('Goods/detail?id='.$vo['goodid']);?>"><?php echo (get_good_name($vo["goodid"])); ?></a></li>
					<li>数量  <?php echo ($vo["num"]); ?></li>
					<li>¥ <?php echo ($vo["price"]); ?> 
						<?php if($vo['status'] == '3' && $vo['child'] != '0' && $vo['child'] != '2' && $vo['child'] != '3' && $vo['child'] != '5'): ?><p class="sqth"> <a href="<?php echo U('center/application?cpid='.$vo['goodid'].'&orderid='.$vo['orderid']);?>">申请退货</a></p><?php endif; ?>
					 <?php if($vo['child'] == '0'): ?><p class="sqth"><a href="javascript:void(0);">已申请退货</a></p><?php endif; ?>
				<?php if($vo['child'] == '2'): ?><p class="sqth"> <a href="javascript:void(0);">退货中</a></p><?php endif; ?>
				 <?php if($vo['child'] == '3'): ?><p class="sqth"><a href="javascript:void(0);">拒绝退货</a></p><?php endif; ?>
				 <?php if($vo['child'] == '5'): ?><p class="sqth"> <a href="javascript:void(0);">已退货</a></p><?php endif; ?>
					</li>
					
				</ul>
				
			</div><?php endforeach; endif; else: echo "" ;endif; ?>
			<div class="payment">
			  <form method="post" name="payform" id="payform" action="<?php echo U('Shopcart/topay');?>" >
            	<input type="hidden" name="orderid" value="<?php echo ($po["orderid"]); ?>" />
            </form>        
				<span><?php echo (date('Y-m-d H:i:s',$po["create_time"])); ?></span> 
				<!--<a href="<?php echo U('order/detail?id='.$po['orderid']);?>" >订单111详情</a>-->
				<?php if($po['status'] == '2'): ?><a href="<?php echo U('order/complete?id='.$po['orderid']);?>">确认收货</a><?php endif; ?>
				<?php if(($po['ispay'] == '1') AND ($po['status'] == '-1')): ?><a href="<?php echo U('order/cancel?id='.$po['orderid']);?>">取消订单</a>
				<a href='javascript:void(0);' onclick="document.getElementById('payform').submit();" class="hover">立即付款</a><?php endif; ?>
				<?php if(($po['ispay'] == '-1') AND ($po['status'] == '1')): ?><a href="<?php echo U('order/cancel?id='.$po['orderid']);?>">取消订单</a><?php endif; ?>
				<?php if($po['status'] == '2'): ?><!--<A href="<?php echo U('order/wuliu?id='.$po['orderid']);?>">查看物流</a>--><?php endif; ?>      
			</div>
		
		</div><?php endforeach; endif; else: echo "" ;endif; ?>
	<div class="xd-order">
		没有更多订单了
	</div><?php endif; ?>
		
		
<footer id="w_footer">				<ul class="w_footerNav">					<li>						<a href="/weixin/index.html" class="w_nowColl" >							<p class="iconfont2">&#xe699;</p>							<p>首页</p>						</a>					</li>					<li>						<a href="/weixin/Goods/index.html">							<p style="font-size: 0.8rem;position:relative;top: 0.18rem;" class="iconfont2">&#xe619;</p>							<p>分类</p>						</a>					</li>					<li>						<a href="<?php echo U('Shopcart/index');?>">							<p style="font-size: 1.2rem;" class="iconfont2">&#xe614;</p>							<p>购物车</p>						</a>					</li>					<li style="border-right: none;">						<a href="/weixin/Center/index.html">							<p class="iconfont2">&#xe67f;</p>							<p>我的</p>						</a>					</li>				</ul>			</footer>			<div class="w_homeMao">				<div>					<a href="#"><i class="iconfont2">&#xe603;</i>						<p>客服</p>					</a>				</div>				<div>					<a href="#w_caseTitle">						<i class="iconfont2">&#xe69b;</i>					顶部</a>					<a href="#" class="w_homeMao-a"><i class="iconfont2">&#xe603;</i>					客服</a>				</div>			</div>		</div>	</body></html>