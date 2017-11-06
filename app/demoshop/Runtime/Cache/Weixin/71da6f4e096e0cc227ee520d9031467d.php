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
		<nav id="w_caseNav">
				<div <?php if(($type == '') OR ($type == 2) OR ($type == 5)): ?>class="w_hover"<?php endif; ?>>默认排序<i class="iconfont2">&#xe6b7;</i></div>
				<div>
					<a <?php if($type == 4): ?>class="w_hover"<?php endif; ?> href="<?php echo U('Goods/lists','category='.$category['name'].'&type=4&sort=desc');?>">销量</a>
				</div>
				<div class="x-d-span">
					<a <?php if($type == 3): ?>class="w_hover"<?php endif; ?> href="<?php echo U('Goods/lists','category='.$category['name'].'&type=3&sort='.$sort_val);?>">价格
						<span >
			<!--<i class="iconfont2">&#xe6ac;</i>-->
			<?php if($sort_val == desc): ?><i class="iconfont2 now xdx-ok xdx-1">&#xe6b7;</i>
					<?php else: ?>
			<i class="iconfont2">&#xe6ac;</i><?php endif; ?>
		</span>
					</a>
				</div>
				<div class="w_ClassParent" style="border-right: none;">分类
					<i class="iconfont2">&#xe6b7;</i>
					<ul class="w_Class">
						 <?php if($dqfl): if(is_array($dqfl)): $i = 0; $__LIST__ = $dqfl;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Goods/lists?category='.$vo['name']);?>"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
						 <?php else: ?>
							<?php if(is_array($zdqfl)): $i = 0; $__LIST__ = $zdqfl;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Goods/lists?category='.$vo['name']);?>"><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; endif; ?>
					</ul>
				</div>
				<ul class="w_Under">
					<li><a href="<?php echo U('Goods/lists','category='.$category['name']);?>">默认排序</a></li>
					<li><a href="<?php echo U('Goods/lists','category='.$category['name'].'&type=2&sort=desc');?>">新品优先</a></li>
					<li><a href="<?php echo U('Goods/lists','category='.$category['name'].'&type=5&sort=desc');?>">评价数从高到低</a></li>
					<li class="w_cancle"><a href="#">取消</a></li>
				</ul>
		</nav>
<script>
	$("#w_caseNav > div").click(function(){
		if($(this).index() == 0){
			$(".w_Under").css({'display' : 'block'});
			$(".w_Class").css({'height' : '0'});
			setTimeout(function(){
				$(".w_Under").css({'height' : '250/40rem','opacity' : '1'});
			},1);
			$(this).children('i').html('&#xe6ac;');
		}
	});
	$(".w_cancle").click(function(){
		this.parentNode.style.height = 0;
		this.parentNode.style.opacity = 0;
		_self = this;
		setTimeout(function(){
			_self.parentNode.style.display = "none";
		},500);
		$(".w_hover").children('i').html('&#xe6b7;');
		return _self = this;
	});
	var _Show = true;
	$(".w_ClassParent").click(function(){
		var _height = $(this).children(".w_Class").children();
		$(".w_Under").css({'height' : 0});
		$(".w_Under").css({'opacity' : 0});
		$(".w_hover").children('i').html('&#xe6b7;');
		setTimeout(function(){
			$(".w_Under").css({'display' : 'none'});
		},500);
		if(_Show){
			$(this).children(".w_Class").css({'height' : _height.length*$(_height[0]).height()});
			$(this).children(".w_Class").prev().html('&#xe6ac;');
			return _Show = false;
		}else{
			$(this).children(".w_Class").css({'height' : '0'});
			$(this).children(".w_Class").prev().html('&#xe6b7;');
			return _Show = true;
		}
		
	});
</script>
			<ul id="w_caseList">
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "暂时没有数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
						<div>
							<a href="<?php echo U('Goods/detail?id='.$vo['id']);?>"><img src="<?php echo (get_cover($vo["cover_id"],'path')); ?>" /></a>
						</div>
						<div>
							<a href="<?php echo U('Goods/detail?id='.$vo['id']);?>">
							<p><?php echo ($vo["title"]); ?></p>
							<p>￥<?php echo ($vo["price"]); ?></p>
							<p><?php echo ($vo["comment"]); ?>人评价</p>
							</a>
						</div>
					</li><?php endforeach; endif; else: echo "暂时没有数据" ;endif; ?>
			</ul>

<footer id="w_footer">				<ul class="w_footerNav">					<li>						<a href="/weixin/index.html" class="w_nowColl" >							<p class="iconfont2">&#xe699;</p>							<p>首页</p>						</a>					</li>					<li>						<a href="/weixin/Goods/index.html">							<p style="font-size: 0.8rem;position:relative;top: 0.18rem;" class="iconfont2">&#xe619;</p>							<p>分类</p>						</a>					</li>					<li>						<a href="<?php echo U('Shopcart/index');?>">							<p style="font-size: 1.2rem;" class="iconfont2">&#xe614;</p>							<p>购物车</p>						</a>					</li>					<li style="border-right: none;">						<a href="/weixin/Center/index.html">							<p class="iconfont2">&#xe67f;</p>							<p>我的</p>						</a>					</li>				</ul>			</footer>			<div class="w_homeMao">				<div>					<a href="#"><i class="iconfont2">&#xe603;</i>						<p>客服</p>					</a>				</div>				<div>					<a href="#w_caseTitle">						<i class="iconfont2">&#xe69b;</i>					顶部</a>					<a href="#" class="w_homeMao-a"><i class="iconfont2">&#xe603;</i>					客服</a>				</div>			</div>		</div>	</body></html>