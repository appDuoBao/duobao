<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<?php if($meta_title == '首页'): ?><title><?php echo C('WEB_SITE_TITLE');?></title>
	<?php else: ?>
		<title><?php echo ($meta_title); ?>_<?php echo C('WEB_SITE_TITLE');?></title><?php endif; ?>
	<meta name="keywords" content="<?php echo C('WEB_SITE_KEYWORD');?>"/>
	<meta name="description" content="<?php echo C('WEB_SITE_DESCRIPTION');?>" />
	<link rel="stylesheet" href="/Public/Weixin/css/style.css" />
	<link rel="stylesheet" href="/Public/Weixin/css/font/iconfont.css" />
	<link rel="stylesheet" href="/Public/Weixin/css/font1/iconfont.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Weixin/css/swiper.min.css"/>
	<script src="/Public/Weixin/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="/Public/Weixin/js/common.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="/Public/Weixin/js/swiper.min.js" ></script>
</head>
<body> 
<div class="xd-product-box swiper-container">
	<div class="xd-product swiper-wrapper">
		<?php if(is_array($pics)): $i = 0; $__LIST__ = $pics;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide"><img src="<?php echo (get_cover($vo,'path')); ?>" alt="" /></div><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
</div>
<div class="xd-Price xd-border">
	<div class="xd-product-left"><?php echo ($info["title"]); ?></div>

	<div class="xd-hide xd-product-right" onclick="favor();"  <?php if(($isitfav) == "0"): ?>style="display: block;"<?php endif; ?>>
        <em class="iconfont">&#xe616;</em> <br />收藏
	</div>
	<div class="xd-hide xd-product-color" onclick="favor();"  <?php if(($isitfav) == "1"): ?>style="display: block;"<?php endif; ?>>
		<em class="iconfont">&#xe616;</em> <br />已收藏
	</div>
	<p>价格： ¥ <?php echo ($info['price']); ?></p>

</div>
<style>
	.xd-hide{
		display: none;}
	.buttons-submit > div{
		display: none;
	}
</style>
<div class="xd-number xd-border" id = "shuliang">
	<span>数量:</span>
	<em class="iconfont">&#xe662;</em>
</div>
<div class="xd-tab xd-border" id = "xd-xq-tab">
	<ul class="xd-tab-ul">
		<li class="active">商品详情</li>
		<li>商品评价</li>
	</ul>
	<div class="xd-tab-div" style="display: block;">
		<?php echo ($info["content"]); ?>
	</div>
	<div class="xd-tab-div">
		<?php if(!empty($list)): ?><script type="text/javascript" charset="utf-8" src="/Public/static/ueditor/ueditor.all.js"></script>
			<div class="xd-evaluate">
				<a href="#" class="xd-evaluate-bg">全部</a>
				<a href="#">好评(<?php echo ($haoping); ?>)</a>
				<a href="#">中评(<?php echo ($zhongping); ?>)</a>
				<a href="#">差评(<?php echo ($chaping); ?>)</a>
			</div>
			<div class="xd-evaluate-box">
				<ul class="xd-xq-pinglun">
					<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
							<p><?php echo ($vo["nickname"]); ?>
								<span><img class="img" src="/Public/Weixin/images/xd/xingxing<?php echo ($vo["serve"]); ?>.png" alt="" /></span>
							</p>
							<p><?php echo ($vo["content"]); ?></p>
							<i><?php echo (date("Y-m-d H:i:s",$vo["create_time"])); ?></i>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			</div>
		
		<?php else: ?>
			<div class="xd-evaluate-box">
				<div class="xd-There-comment">
					<img src="/Public/Weixin/images/xd/xd-meiyou.png" alt="" />
					<span>暂无评价</span>
				</div>
			</div><?php endif; ?>
	</div>
</div>
<div class="xd-purchase">
	<a href="<?php echo U('Shopcart/index');?>" class="xd-purchase-1">
		<em id="totalnum"><?php echo ((isset($userCartNum) && ($userCartNum !== ""))?($userCartNum):'0'); ?></em>
		<i class="iconfont">&#xe614;</i>购物车</a>
	<a href="#" class="xd-purchase-2">加入购物车</a>
	<a href="#" class="xd-purchase-3">立即购买</a>


</div>
<div class="alert-bg" style="display: none;"></div>

<form action="<?php echo U("Shopcart/order");?>" name="orderform" id="orform" method="post" onsubmit="return trySubmit()">
<input type="hidden" class="chanpinid" name="id[]" value="<?php echo ($info["id"]); ?>"/>
<div class="xq-xq-alert" style="display: none;">
	<div class="close icon">&#xe652;</div>
	<div class="content">
		<div class="xq-alert-wrap">
			<div class="left">
				<div class="img">
					<div><img src="<?php echo (get_cover($info["cover_id"],'path')); ?>"/></div>
				</div>
			</div>
			<div class="right">
				<p id="resetprice"><span>¥</span><font id="pic_price"><?php echo ($info['price']); ?></font></p>
				<p>库存<font id="pic_kucun"><?php echo ($info['stock']); ?></font>件</p>
			</div>
		</div>
		
		<?php if($GoodsDataList): ?><ul class="xg-gouwu-ul">
			<?php if(!empty($GoodsDataList)): if(is_array($GoodsDataList)): $i = 0; $__LIST__ = $GoodsDataList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li src='id-<?php echo ($v["id"]); ?>'>
	        	<span><?php echo ($v["name"]); ?> ： </span>
				 <?php if(is_array($v["value"])): $i = 0; $__LIST__ = $v["value"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sv): $mod = ($i % 2 );++$i;?><label  data-id="<?php echo ($sv["id"]); ?>">
				 		<input type="radio" name="attr_<?php echo ($v["id"]); ?>" value="<?php echo ($sv["id"]); ?>"/>
				 		<span><?php echo ($sv["extend_name"]); ?></span>
				 	</label><?php endforeach; endif; else: echo "" ;endif; ?>
			</li><?php endforeach; endif; else: echo "" ;endif; endif; ?>
	
			</ul><?php endif; ?>
		<div class="xq-alert-wrap" <?php if($nofenlei != '1'): ?>style="display:none"<?php endif; ?>>
		
			<div class="left">
				<div class="txt">数量</div>
			</div>
			<div class="right">
				<div class="jisuan" data-kucun="">
					<i class="icon">&#xe603;</i>
					<input type="text" id="goodnum" value="1"  name="num[]" readonly />
					<i class="icon">&#xe629;</i>
				</div>
			</div>
		</div>
		<style type="text/css">
			.xg-gouwu-ul{
				
			}
			.xg-gouwu-ul li{
				font-size: 0.65rem;
				padding: .5rem;
			}
			.xg-gouwu-ul label{
				display: inline-block;
				text-align: center;
				font-size: 0.65rem;
				padding: .3rem .5rem;
				border: 1px solid #ddd;
				margin-right: .1rem;
			}
			.xg-gouwu-ul .hover{
				border-color: #ff8522;
			}
			.xg-gouwu-ul label input{
				display: none;
			}
		</style>
		<script>
			var nux,index_num,nul;
			var zong_price  =  <?php echo ($info['price']); ?>; 					//总价 => 默认是 产品单价
			var pic_one_price = nub_price = parseFloat(<?php echo ($info['price']); ?>)*100;	//产品单价（不含属性）
			var value_price = '';						//产品属性id综合
			var nub_vlue	=	parseInt($('#goodnum').val()); 		//产品数量

			$(".xg-gouwu-ul li label").change(function(){
				var last = $(this).find('input[type="radio"]').val();
				$(this).parent().find("label").removeClass("hover");
				$(this).addClass("hover");
				value_price = '';
				$(".xg-gouwu-ul li label.hover").each(function(index,element){
					value_price	+= parseFloat($(element).find('input').val());
				})
				//判断是否选择产品属性
				var this_nub	=	 0;
				var exarr	=	 new Array();
				var z_length	=	 $('.xg-gouwu-ul li')?$('.xg-gouwu-ul li').size():0;
				$('.xg-gouwu-ul li').each(function(index,element){
				if($(element).find('label').hasClass('hover'))
				{
				this_nub++;
				}
				})

				$('.xg-gouwu-ul li label.hover').each(function(index,element){
				exarr[index] = $(element).data('id');

				})
				var infoid = <?php echo ($info["id"]); ?>;

			$.ajax({
						type:'post', //传送的方式,get/post
						url:'<?php echo U("Goods/getnewprice");?>', //发送数据的地址
						data:{id:infoid,shuxing:exarr,last:last},
						dataType: "json",
						async: true,
						success:function(data){
							nux = data.total;
							nul = data.num;

							if(data.status==3){
							//商品卖完了
								if(confirm(data.msg)){
									history.go(-1);
								}	
							}else if(data.status > 3){
							//商品属性出错了
							if(confirm(data.msg)){
									location.reload();
								}	
							}else{
								if(data.status==1){
									//获取数据成功，但是属性并未选择完全

									$("#pic_kucun").text(nul);
									nub_price = zong_price=$("#pic_kucun").text();
									$("#pic_price").text(nux);
									$.each(data['value'], function(key, val) {
										$("li[src='id-"+key+"']").children('label').css('background',"#fff");
										$("li[src='id-"+key+"']").children('label').children("input").removeAttr('disabled');
										$("li[src='id-"+key+"']").children('label').each(function(){
											var that = $(this);
											that.removeClass("hover");
											if($.inArray(that.attr('data-id'), data['value'][key]) == -1){
												that.css('background','#ddd');	
//												that.css('border-color','#ddd');	 
												that.children("input").attr('disabled',true); 
											}
										})
									}); 
								}
								if(data.status==2){
									console.log(1)
									$(".xq-alert-wrap").show();
									$("#pic_price").text(nux);
									$("#pic_kucun").text(data.num);
									$("#goodnum").parent().attr("data-kucun",data.num);
									$("#goodnum").val(1);
									$(".aaa").children("button").removeClass("xd-cla")
									
								}
							}
						},
							error:function (event, XMLHttpRequest, ajaxOptions, thrownError) {
							alert(XMLHttpRequest+thrownError);
						}
					})
				//$('#pic_price').attr('data-value_price',value_price);//计算属性差价，赋值于价格 data-value_price
				//nub_price 	=	(value_price + pic_one_price);
 				//zong_price = (nub_price * nub_vlue/100).toFixed(2);
				//$('#pic_price').text(zong_price);					//计算产品总价（单价+属性差价）
check()

			})
			$(".xg-gouwu-ul li label").click(function(){
				var is = $(this).find("input").attr("disabled");
				if(!is){
					$(this).addClass("hover");
					$(this).find("input").addClass("hover");
				}
//				$(this).addClass("hover");
//				$(this).find("input").attr("checked","true");
			})
			
			function check(){
				var all=0;
				var now=0;
				$(".xg-gouwu-ul li").each(function(){
					all++;
					$(this).children('label').each(function(){
						if($(this).attr('class') =='hover'){
							now++;
						}
					})
				})
				if(all == now){
					
					$(".buttons.buttons-button div button").removeAttr('disabled');
			
			
				}
				
			}
			
			
			
			
		</script>
	</div>
	<style>
		.xg-gouwu-ul li label{
			/*border-color: #000;*/
		}
		.xd-cla{
			background: #ccc !important;
		}
	</style>
	
	
	<div class="buttons buttons-button">
		<div class="aaa">

			<button class="<?php if($nofenlei != '1'): ?>xd-cla<?php endif; ?> max-btn btn-huang" <?php if($nofenlei != '1'): ?>disabled<?php endif; ?>  onclick="buy(<?php echo ($info["id"]); ?>);return false;">加入购物车</button>
		</div>
		<div  class="aaa">
			<button class="<?php if($nofenlei != '1'): ?>xd-cla<?php endif; ?> max-btn btn-red" <?php if($nofenlei != '1'): ?>disabled<?php endif; ?> type="button"  onclick="order(<?php echo ($info["id"]); ?>);return false;">立即购买</button>
		</div>
	</div>


	<div class="buttons buttons-submit">
		<div  class="aaa">
			<button class="<?php if($nofenlei != '1'): ?>xd-cla<?php endif; ?> max-btn btn-huang" onclick="buy(<?php echo ($info["id"]); ?>);return false;">确定</button>
		</div>
		<div  class="aaa">
			<button class="<?php if($nofenlei != '1'): ?>xd-cla<?php endif; ?> max-btn btn-red" type="button"   onclick="order(<?php echo ($info["id"]); ?>);return false;">确定</button>
		</div>
	</div>

</div>
</form>
		<?php if($GoodsDataList): ?><script type="text/javascript">

</script><?php endif; ?>
<script type="text/javascript">
	function trySubmit() {
		if (this.submitFlag == 1) {
			alert('数据已经提交，请勿再次提交。');
			return false;
		}else {
			this.submitFlag = 1;
			return true;
		}
	}
</script>

<script>
	

	function showT(){
		$(".alert-bg").show();
		$(".xq-xq-alert").show();
	}

	$("#shuliang").click(function(){
		showT();
		$('.buttons-button').show();
		$('.buttons-submit >  div').hide();
	})
	$(".xd-purchase-2").click(function(){
		showT();
		$('.buttons-button').hide();
		$('.buttons-submit >  div').hide();
		$('.buttons-submit >  div').eq(0).show();
	})
	$(".xd-purchase-3").click(function(){
		showT();
		$('.buttons-button').hide();
		$('.buttons-submit >  div').hide();
		$('.buttons-submit >  div').eq(1).show();
	})
	$(".close").click(function(){
		$(".alert-bg").hide();
		$(".xq-xq-alert").hide();
	})
</script>

</body>
<script>
	$("#xd-xq-tab .xd-tab-ul li").click(function(){
		$("#xd-xq-tab .xd-tab-div").hide();
		$("#xd-xq-tab .xd-tab-ul li").removeClass("active");
		$(this).addClass("active");
		$("#xd-xq-tab .xd-tab-div").eq($(this).index()).show();
	})

	$(function() {
		var elm = $('.xd-tab-ul');
		var startPos = $(elm).offset().top;
		$.event.add(window, "scroll", function() {
			var p = $(window).scrollTop();
			$(elm).css('position',((p) > startPos) ? 'fixed' : 'static');
			$(elm).css('top',((p) > startPos) ? '0px' : '');
		});
	});


</script>
<script>



		//属性选择器 判断是否选择完全
	function value_checked(){
		var this_nub	=	 0;
		var this_value	=	 '';
		var z_length	=	 $('.xg-gouwu-ul li')?$('.xg-gouwu-ul li').size():0;
		$('.xg-gouwu-ul li').each(function(index,element){
			if($(element).find('label').hasClass('hover'))
			{
				this_nub++;

			}
		})

		$('.xg-gouwu-ul li label.hover').each(function(index,element){
			if(index == 0)
			{
				this_value 	+=  	$(element).data('id');
			}
			else
			{
				this_value 	+=   '_'  +  $(element).data('id');
			}
		})


		//赋值--属性列表--隐藏域



		if(this_nub != z_length)
		{
			return false;
		}
		else
		{
			return true;
		}

	}



	var swiper = new Swiper('.swiper-container', {
		pagination: '.swiper-pagination',
		direction : 'horizontal',
		autoplay : 3000,
		speed:300,
		paginationClickable: true
	});

	//收藏
	function favor() {
		var uexist="<?php echo get_username();?>";
		if(uexist){
			var favorid='<?php echo ($info["id"]); ?>';
			$.ajax({
				type:'post', //传送的方式,get/post
				url:'<?php echo U("User/favor");?>', //发送数据的地址
				data:{id:favorid},
				dataType: "json",
				success:function(data){
					//alert(data.msg)
					if(data.msg == "已收藏"){
						$('.xd-product-right').hide();
						$('.xd-product-color').show();
					}
				},
				error:function (event, XMLHttpRequest, ajaxOptions, thrownError) {
					alert(XMLHttpRequest+thrownError);
				}
			})
		}else{
			window.location.href = "<?php echo U('User/login');?>";
		}
	}


	//立即购买
	function order(i){

		//判断是否选择产品属性
		if(!value_checked())
		{
			alert('您还没有选择商品种类呦(*^__^*) ');
			return false;//判断属性是否选择完全
		}

		var uexist="<?php echo get_username();?>";
		if(uexist){
			var parameters = "";
			$("input[type=radio]").each(function(){
				if($(this).parent().hasClass("hover")){
					parameters += "," + $(this).val();
				}
			});

			var id = <?php echo ($info["id"]); ?>;
			var newid = id + parameters;
			$(".chanpinid").val(newid);
			document.orderform.submit();
		}else{
			window.location.href = "<?php echo U('User/login');?>";
		}
	}

	//加入购物车

	function buy(i){

		if(!value_checked())
		{
			alert('您还没有选择商品种类呦(*^__^*) ');
			return false;//判断属性是否选择完全
		}

		var uexist="<?php echo get_username();?>";


		var gid=i;
		var url='<?php echo U("Shopcart/addItem");?>';//地址
		var gnum=$("#goodnum").val();//数量
		var parameters = "";
		$("input[type=radio]").each(function(){
			if($(this).parent().hasClass("hover")){
				parameters += "," + $(this).val();
			}
		});

		var string=String(gid);
		$.ajax({
			type:'post', //传送的方式,get/post
			url:'<?php echo U("Shopcart/addItem");?>', //发送数据的地址
			data:{
				id:gid,num:gnum,i:parameters
			},
			dataType: "json",
			success:function(data)
			{
				console.log(data);
				$(".alert-bg").hide();
				$(".xq-xq-alert").hide();
				$('#totalnum').text(data.sum);
				if(data.sum){
					$('#totalnum').text(data.sum);//同步顶部购物车商品数量
				}
			},
			error:function (XMLHttpRequest, ajaxOptions, thrownError) {alert(XMLHttpRequest+thrownError); }
		})


	}
	
	
function comment(id){ //ajax分页函数
		var id = id;
		var gooid = <?php echo ($info["id"]); ?>;
		$.ajax({
			type:"post", //传送的方式,get/post
			url:"<?php echo U('article/comment');?>", //发送数据的地址
			data:{p:id,goodid:gooid},
			dataType: "html",
			success:function(data){
				$("#productcomment").replaceWith('<div id="productcomment">'+data+'</div>');
			},
			error:function (event, XMLHttpRequest, ajaxOptions, thrownError) {alert(XMLHttpRequest+thrownError); }
		})
	}


</script>
<script>
	$(".jisuan i").click(function(){
		var index = $(this).index();
		var kucun = $(this).parent().attr("data-kucun");
		var nub_vlue = index;

		var num   = parseInt($(this).parent().find("input").val());
		if(index == 0 && num > 1){
			//console.log()
			$(this).parent().find("input").val(num-1);
			nub_vlue = num - 1;
			//具体处理逻辑... value_price pic_one_price zong_price nub_price
			zong_price =(Number(nux)*Number(nub_vlue)).toFixed(2);
			$('#pic_price').text(zong_price);
			//console.log(nul);
			var m = 0;
			if(nub_vlue == 1){
				m = 1;
			}
			//$("#pic_kucun").text(kucun-nub_vlue+m);

		}else if(index == 2 && num < <?php echo ($info['stock']); ?>){
			if(num == kucun){
				alert("库存只有"+kucun+"件了");
			}else{
				$(this).parent().find("input").val(num+1);
				nub_vlue = num + 1;
				//具体处理逻辑...
				zong_price = (Number(nux)*Number(nub_vlue)).toFixed(2);
				$('#pic_price').text(zong_price);
				index_num = zong_price;
				//$("#pic_kucun").text(kucun-nub_vlue);
			}
		}

		event.stopPropagation();
	})

	
	
	
</script>
</html>