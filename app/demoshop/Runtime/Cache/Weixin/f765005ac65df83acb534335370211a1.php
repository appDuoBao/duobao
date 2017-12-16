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

<div class="order_sus">
    <div class="info">提交成功！(*^__^*)</div>
    <div class="link"><a rel="nofollow" href="<?php echo U('index/index');?>">继续购物</a><a rel="nofollow" href="<?php echo U('center/index');?>">查看订单</a></div>
</div>

<!--脚本开始-->
<footer id="w_footer">