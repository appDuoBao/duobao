<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
	<?php if($meta_title == '首页'): ?><title><?php echo C('WEB_SITE_TITLE');?></title>
	<?php else: ?>
		<title><?php echo ($meta_title); ?>_<?php echo C('WEB_SITE_TITLE');?></title><?php endif; ?>
	<meta name="keywords" content="<?php echo C('WEB_SITE_KEYWORD');?>"/>
	<meta name="description" content="<?php echo C('WEB_SITE_DESCRIPTION');?>" />
	<link rel="stylesheet" href="/Public/Weixin/css/font/iconfont.css" />
	<link rel="stylesheet" type="text/css" href="/Public/Weixin/css/font1/iconfont.css"/>
	<link rel="stylesheet" href="/Public/Weixin/css/style.css" />
	<script type="text/javascript" src="/Public/Weixin/js/jquery.min.js" ></script>
	<script type="text/javascript" src="/Public/Weixin/js/common.js" ></script>
	<script type="text/javascript" src="/Public/Weixin/js/touch.js" ></script>
	<script type="text/javascript" src="/Public/Weixin/js/EUI.js" ></script>
	<script type="text/javascript" src="/Public/Weixin/js/js/touch.js" ></script>
</head>
<body>
<?php if($usercart): ?><form action='<?php echo U("Shopcart/order");?>'method="post" name="myform" id="form">
		<div class="gwc-wrap-header">
			<label>
				<input type="checkbox" checked="checked"/>
				<i class="icon color-che">&#x3551;</i>
				<span>全选</span>
			</label>
			<div>
				<button type="button" onclick="delGoodsAll();"><span class="iconfont">&#xe6a5;</span> 删除</button>
			</div>
		</div>

		<div style="padding-bottom: 140px; width:100%; overflow-x:hidden;">
			
				<!--登录用户购物车-->
				<?php if(!empty($usercart)): if(is_array($usercart)): $i = 0; $__LIST__ = $usercart;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="gwc-box" id="goods_<?php echo ($vo["sort"]); ?>">
							<div class="gwc-wrap">
								<label class="label_checkbox">
									<input type="checkbox" data-id="goods_<?php echo ($vo["sort"]); ?>" data-dj="<?php echo ($vo["price"]); ?>" data-sl="<?php echo ($vo["num"]); ?>" checked="checked" name="id[]" value="<?php echo ($vo["sort"]); ?>" />
									<i class="icon color-che">&#x3551;</i>
								</label>
								<div class="div">
									<div class="img">
										<a href="<?php echo U('Goods/detail?id='.$vo['goodid']);?>"><img src="<?php echo (get_cover(get_cover_id($vo["goodid"]),'path')); ?>"/></a>
									</div>
									<div class="txt">
										<a href="<?php echo U('Goods/detail?id='.$vo['goodid']);?>"><p><?php echo (get_good_name($vo["goodid"])); ?></p></a>

										<div class="order-o-value">
											<?php if($vo.parameters): if(is_array($vo["parameters"])): $i = 0; $__LIST__ = $vo["parameters"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vos): $mod = ($i % 2 );++$i;?><em><?php echo ($vos["extend_name"]); ?></em><?php endforeach; endif; else: echo "" ;endif; endif; ?>
										</div>

										<div class="order-o-price">￥<span id="danjia_<?php echo ($vo['sort']); ?>"><?php echo ($vo["price"]); ?></span></div>
										<div class="cls"></div>
										<div class="jisuan">
											<i class="icon">&#xe603;</i>
											<input  type="text" id="<?php echo ($vo["sort"]); ?>" name="num[]" value="<?php echo ($vo["num"]); ?>"  readonly="true" />
											<i class="icon">&#xe629;</i>
										</div>
									</div>
								</div>
							</div>
							<button type="button" class="delete" onclick="delGoods('<?php echo ($vo["sort"]); ?>');">删除</button>
						</div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
	
		</div>

		<div class="gwc-footer">
			<div class="left">
				<p>总计：¥<span id="xg-zongjia"><?php echo ($price); ?></span></p>
				<p id="goodsNum">(共<?php echo ((isset($userCartNum) && ($userCartNum !== ""))?($userCartNum):"0"); ?>件，不含运费)</p>
			</div>
			<div class="r">
				<button type="button" class="max-btn btn-red" onclick="checklogin();return false">去结算</button>
			</div>
		</div>
	</form>
<?php else: ?>
	<div class="xd-There">
		<img src="/Public/Weixin/images/xd/xd-2.png"/>
	</div><?php endif; ?>

<script>
	var uexist="<?php echo get_username();?>";
	//全选的实现 定义加、减、删的发送路径
	if(uexist){
		var inc='<?php echo U("Shopcart/incNumByuid");?>';
		var dec='<?php echo U("Shopcart/decNumByuid");?>';
		var del='<?php echo U("Shopcart/delItemByuid");?>';
		var dels='<?php echo U("Shopcart/delsItemByuid");?>';
	}else{
		var inc='<?php echo U("Shopcart/incNum");?>';
		var dec='<?php echo U("Shopcart/decNum");?>';
		var del='<?php echo U("Shopcart/delItem");?>';
		var dels='<?php echo U("Shopcart/delsItem");?>';
	}
	/*删除单个商品*/
	function delGoods(string){
		alert("确定要删除该商品吗？",2,function(i){
			if(i == 1){
				//删除数据
				$.ajax({
					type:'post', //传送的方式,get/post
					url:del, //发送数据的地址
					data:{sort:string},
					dataType: "json",
					success:function(data)
					{
						$("#goods_"+string).remove();
						var a=data.sum;
						if(a=="0"){
							window.location.reload();
						}else{
							$("#xg-zongjia").text(data.price);
							$('#goodsNum').text('(共'+data.sum+'件，不含运费)');//同步购物车商品数量
						}
					},
					error:function (event, XMLHttpRequest, ajaxOptions, thrownError) {
						alert(XMLHttpRequest+thrownError); }
				})

			}
		})
	}
	/*删除多个被选中商品*/
	function delGoodsAll(){
		alert("确定要删除这些商品吗？",2,function(i){
			if(i == 1){
				var ids = "";
				$("input:checkbox[name='id[]']:checked").each(function() { // 遍历name=ids的多选框
					if(ids==''){
						ids = $(this).val();  // 每一个被选中项的值
					}else{
						ids = ids +","+ $(this).val();  // 每一个被选中项的值
					}
				});
				//删除数据
				$.ajax({
					type:'post', //传送的方式,get/post
					url:dels, //发送数据的地址
					data:{ids:ids},
					dataType: "json",
					success:function(data)
					{
						var a=data.sum;
						if(a=="0"){
							window.location.reload();
						}else{
							$("#xg-zongjia").text(data.price);
							$('#goodsNum').text('(共'+data.sum+'件，不含运费)');//同步购物车商品数量
/*							$("input:checkbox[name='id']:checked").each(function() { // 遍历name=ids的多选框
								var delGoodsId = $(this).val();
								$("#goods_"+delGoodsId).remove();
							});*/
							
							//删除 所选商品
							$('.color-che').parents('.gwc-box').each(function(index,element){
								$(element).remove();
							})
							
							
							thiZL = $('input[name="id[]"]:checked').size();//获取被选中商品的总数
							
							
							
						}
					},
					error:function (event, XMLHttpRequest, ajaxOptions, thrownError) {
						alert(XMLHttpRequest+thrownError); }
				})
			}
		})
	}
	
	
	thiZL = $('input[name="id[]"]:checked').size();//获取被选中商品的总数
	function jisuan(){
		var thisZJ	=	0;
		thiZL = $('input[name="id[]"]:checked').size();//获取被选中商品的总数
		
		$('input[name="id[]"]:checked').each(function(index,element){
			thisZJ += ($(element).data('dj') * 100) * parseInt($(element).parents('.gwc-box').find('input[name="num[]"]').val());
		})

		$('#xg-zongjia').text((thisZJ/100).toFixed(2));
	}
	$(function(){
		$("label.label_checkbox input").click(function(){

			if($(this).is(":checked")){
				$(this).next().html("&#x3551;");
				$(this).next().addClass("color-che");
				$(this).prop('checked',true);
			}else{
				$(this).next().html("&#xe61e;");
				$(this).next().removeClass("color-che");
				$(this).prop('checked',false);

			}

			jisuan();

		})

		$(".gwc-wrap-header label").change(function(){

			if($(this).find("input").is(":checked")){
				$("label .icon").html("&#x3551;");
				$("label input").prop("checked","checked");
				$("label .icon").addClass("color-che");
			}else{
				$("label .icon").html("&#xe61e;");
				$("label input").prop("checked",false);
				$("label .icon").removeClass("color-che");
			}
			jisuan();
		})
		$(".jisuan i").click(function(){
			var index = $(this).index();
			var num = parseInt($(this).parent().find("input").val());//商品数量
			var gid = $(this).parent().find("input").attr('id');//商品id
			if(index == 0 && num > 1){
				$(this).parent().find("input").val(num-1);
				$(this).parents('.gwc-box').find('input[name="id[]"]').attr('data-sl',num-1);
				jisuan();

				//减少数据
				$.ajax({
					type:'post', //传送的方式,get/post
					url:dec, //发送数据的地址
					data:{sort:gid},
					dataType: "json",
					success:function(data){

					},
					error:function (event, XMLHttpRequest, ajaxOptions, thrownError) {
						alert(XMLHttpRequest+thrownError); }
				})
			}else if(index == 2){
				//增加数据
				$(this).parent().find("input").val(num+1);
				$(this).parents('.gwc-box').find('input[name="id[]"]').attr('data-sl',num+1);
				jisuan();

				$.ajax({
					type:'post', //传送的方式,get/post
					url:inc, //发送数据的地址
					data:{sort:gid},
					dataType: "json",
					success:function(data){

					},
					error:function (event, XMLHttpRequest, ajaxOptions, thrownError) {
						alert(XMLHttpRequest+thrownError); }
				})
			}

			event.stopPropagation();
		})

		var w = $("body").width();
		var btnw = $(".gwc-box .delete").width();
		$(".gwc-wrap").css("width",w + "px");
		$(".gwc-box").css("width",w + btnw + 10 + "px");
		$(".gwc-box .delete").css("opacity","1");

		$(".gwc-box").each(function(){
			var _this = this;
			touch.on(_this, 'swiperight', function(ev){
				$(_this).css("margin-left","0px");
			});
			touch.on(_this, 'swipeleft', function(ev,x){
				$(_this).css("margin-left",-btnw+"px");
			});
		})
	})
</script>



<script type="text/javascript">
	//登录后刷新页面，载入数据
	$("#login_btn").click(function(){

		var yourname=$('#inputusername').val();
		var yourword=$('#inputpassword').val();

		$.ajax({
			type:'post', //传送的方式,get/post
			url:'<?php echo U("User/loginfromdialog");?>', //发送数据的地址
			data:{username:yourname,password:yourword},
			dataType: "json",
			success:function(data){
				if(data.status=="1"){
					//$(".tips").html(data.info);
					window.location.reload();
					$("#uid").val(data.uid);
				}else{
					$(".tips").html(data.info);
				}
			},
			error:function (event, XMLHttpRequest, ajaxOptions, thrownError) {
				alert(XMLHttpRequest+thrownError); }
		});});
	//全选的实现
	$(".check-all").click(function(){
		$(".ids").prop("checked", this.checked);
	});
	$(".ids").click(function(){
		var option = $(".ids");
		option.each(function(i){
			if(!this.checked){
				$(".check-all").prop("checked", false);
				return false;
			}else{
				$(".check-all").prop("checked", true);
			}
		});
	});

	function checklogin() {
		var uexist="<?php echo get_username();?>";
		if(uexist){
			if(typeof thiZL == "undefined" || thiZL == 0)
			{
				alert('请选择要结算的商品！');	
			}
			else{
				document.myform.submit();
			}
		}else{
			window.location.href = "<?php echo U('User/login');?>";
		}
		
		
	}

</script>
</body>
</html>