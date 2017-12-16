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
	<div class="sc-wrap">
	
	<?php if(is_array($favorlist)): $i = 0; $__LIST__ = $favorlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$fo): $mod = ($i % 2 );++$i;?><div class="sc-wrap">
		<div class="sc-top">
			<img src="<?php echo (get_cover(get_cover_id($fo["goodid"]),'path')); ?>"/>
			<div>
				<a href="<?php echo U('Goods/detail?id='.$fo['goodid']);?>"><p><?php echo (get_good_name($fo["goodid"])); ?></p></a>
				<span>￥<?php echo (get_good_price($fo["goodid"])); ?></span>
			</div>
		</div>
		<div class="sc-bottom">
			<!--<div>
				<a href="#">加入购物车</a>
			</div>-->
			<div class="delete">
				<a href="javascript:void(0)" onclick="deletefavor(<?php echo ($fo["id"]); ?>);"><button>删除</button></a>
			</div>
		</div>
	</div>
	
	</div><?php endforeach; endif; else: echo "" ;endif; ?>
	</empty>  
	
<script type="text/javascript">
//删除收藏
function deletefavor(str) { 
	$.ajax({
		type:'post', //传送的方式,get/post
		url:'<?php echo U("center/deletefavor");?>', //发送数据的地址
		data:{id:str},
		dataType: "json",
		success:function(data){
			alert(data.msg);
			window.location.reload();
		},
		error:function (event, XMLHttpRequest, ajaxOptions, thrownError) {alert(XMLHttpRequest+thrownError); }
	  })        
}
</script>
<footer id="w_footer">