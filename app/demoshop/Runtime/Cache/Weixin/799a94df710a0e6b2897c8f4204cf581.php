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
	<ul id="w_caseClass">
		 <?php if(is_array($catlist)): $k = 0; $__LIST__ = $catlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li class="w_caseClass_list">
				<div class="w_caseClassImg">
					<a href="<?php echo U('Goods/lists?category='.$vo['name']);?>"><img src="<?php echo (get_cover($vo["icon"],'path')); ?>" /></a>
					<a href="<?php echo U('Goods/lists?category='.$vo['name']);?>"><h5><?php echo ($vo["title"]); ?></h5></a>
				</div>
				 <?php if($vo.child): ?><ul class="w_caseClassList">
					 <?php if(is_array($vo["child"])): $j = 0; $__LIST__ = $vo["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($j % 2 );++$j;?><li><a href="<?php echo U('Goods/lists?category='.$vo1['name']);?>"><?php echo ($vo1["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
						<div style="clear: both;"></div>
					</ul><?php endif; ?>
				<div style="clear: both;"></div>
			</li><?php endforeach; endif; else: echo "" ;endif; ?>
	</ul>
<footer id="w_footer">				<ul class="w_footerNav">					<li>						<a href="/weixin/index.html" class="w_nowColl" >							<p class="iconfont2">&#xe699;</p>							<p>首页</p>						</a>					</li>					<li>						<a href="/weixin/Goods/index.html">							<p style="font-size: 0.8rem;position:relative;top: 0.18rem;" class="iconfont2">&#xe619;</p>							<p>分类</p>						</a>					</li>					<li>						<a href="<?php echo U('Shopcart/index');?>">							<p style="font-size: 1.2rem;" class="iconfont2">&#xe614;</p>							<p>购物车</p>						</a>					</li>					<li style="border-right: none;">						<a href="/weixin/Center/index.html">							<p class="iconfont2">&#xe67f;</p>							<p>我的</p>						</a>					</li>				</ul>			</footer>			<div class="w_homeMao">				<div>					<a href="#"><i class="iconfont2">&#xe603;</i>						<p>客服</p>					</a>				</div>				<div>					<a href="#w_caseTitle">						<i class="iconfont2">&#xe69b;</i>					顶部</a>					<a href="#" class="w_homeMao-a"><i class="iconfont2">&#xe603;</i>					客服</a>				</div>			</div>		</div>	</body></html>