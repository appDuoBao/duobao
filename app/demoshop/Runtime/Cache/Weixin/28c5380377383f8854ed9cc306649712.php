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
	<link rel="stylesheet" href="/Public/Weixin/css/font1/iconfont.css" />
	<link rel="stylesheet" href="/Public/Weixin/css/style.css" />
	<script type="text/javascript" src="/Public/Weixin/js/jquery.min.js" ></script>
	<script type="text/javascript" src="/Public/Weixin/js/common.js" ></script>
</head>
<body>
	<div id="body">
	  <form action="<?php echo U('Shopcart/createorder');?>" name="form" id="form" method="post">
	<input type="hidden" name="sender" value="<?php echo ($nowadr["id"]); ?>">
	<input type="hidden" name="tag"  id="orderid" value="<?php echo ($tag); ?>">
	<input type="hidden" name="PayType" value="2">
	<input type="hidden" name="yhqid" value="">
		<div class="tj-header">
		
			<div>
			
<?php if($nowadr): ?><p>收件人：<?php echo ($nowadr["realname"]); ?>                                     <span><?php echo ($nowadr["cellphone"]); ?></span></p>
				<i><?php echo ($nowadr["name"]); ?> <?php echo ($nowadr["address"]); ?></i><?php else: ?><div class="empty_adr">请选择您的收货地址！</div><?php endif; ?>
			</div>
			<button type="button" class="iconfont">&#xe662;</button>
		</div>
		<div class="tj-title">
			<span class="icon">&#xe615;</span>购物清单
		</div>
		<div class="tj-wrap">
 <?php if(is_array($prolist)): $i = 0; $__LIST__ = $prolist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="tj-cp">
				<div class="img">
					<img src="<?php echo (get_cover(get_cover_id($vo["goodid"]),'path')); ?>"/>
				</div>
				<div class="txt">
					<a href="<?php echo U('Goods/detail?id='.$vo['goodid']);?>"><p><?php echo ($vo["goodname"]); ?></p></a>
					<?php if($vo.shuxing): if(is_array($vo["shuxing"])): $i = 0; $__LIST__ = $vo["shuxing"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vos): $mod = ($i % 2 );++$i; echo ($vos["title"]); ?>&nbsp;<?php endforeach; endif; else: echo "" ;endif; endif; ?>
					<i>数量  <?php echo ($vo["num"]); ?></i>
					<span>￥<?php echo ($vo["total"]); ?></span>
				</div>
			</div><?php endforeach; endif; else: echo "" ;endif; ?>
			<div class="tj-txt-box border-bot">
				<div class="tj-txt1">
					<span>运送方式</span>
				<?php if($trans): ?><div>运费  <?php echo ($trans); ?></div>
				<?php else: ?>
				<div>包邮  免运费</div><?php endif; ?>
				</div>
				<div class="tj-txt1">
					<span class="color-hui">给卖家留言：</span>
					<div>
						<input type="text" name="message" />
					</div>
				</div>
				<div class="tj-txt1">
					<span></span>
					<div><i>共<?php echo ($num); ?>件商品</i>合计：<em class="color-red">¥ <?php echo ($total); ?></em></div>
				</div>
			</div>
			<div class="tj-txt-box border-tb">
				<div class="tj-txt1">
					<span>支付方式</span>
					<div id="zhifuxingshi"><span>微信支付</span><em class="iconfont">&#xe662;</em></div>
				</div>
			</div>
			<div class="tj-txt-box border-tb">
				<div class="tj-txt1">
					<span>优惠券</span>
					<div id="youhuijuan" <?php if($yhqlist): ?>onclick="youhuijuan()"<?php endif; ?>>
						
						<?php if($yhqlist): ?><span class="color-red">选择我的优惠券</span>
						

						<?php else: ?>
						<i class="color-hui">无可用</i><?php endif; ?>
						<em class="iconfont">&#xe662;</em>
					</div>
				</div>
			</div>
			<div class="tj-txt-box border-tb">
				<div class="tj-txt2">
					<span>商品金额</span>
					<div>¥<?php echo ($total); ?></div>
				</div>
				<div class="tj-txt2">
					<span>运费</span>
					<div>＋¥<?php echo ($trans); ?></div>
				</div>
				<div class="tj-txt2">
					<span>优惠券</span>
					<div>－¥<font id="yhj_price">0</font></div>
				</div>
				<?php if($mjprice): ?><div class="tj-txt2">

					<span>满减促销</span>

					<div>－¥<font id="yhj_price"><?php echo ($mjprice); ?></font></div>

				</div><?php endif; ?>
			</div>
		</div>
		<div class="tj-footer border-top">
			<span>实付：<i>¥ <font id="sf_price"><?php echo ($all); ?></font></i></span>
			<button class="max-btn btn-red" type="submit">提交订单</button>
		</div>
	</form>	
		<div class="alert-bg" style="display: none;"></div>
		<div class="select" style="display: none;">
		<div class="title"><i>支付方式</i><span class="icon" id = "bodyclose">&#xe652;</span></div>
		<div class="content">
			<div class="tj-zhifu tj-box" name = "支付方式">
				<div class="zhifu-wrap zhifufangshi">
					<img class="logo" src="/Public/Weixin/images/zfb.png"/>
					<span>支付宝支付</span>
					<button class="iconfont">&#xe662;</button>
				</div>
				<div class="zhifu-wrap zhifufangshi">
					<img class="logo" src="/Public/Weixin/images/wx.png"/>
					<span>微信支付</span>
					<button class="iconfont">&#xe662;</button>
				</div>
				<div class="zhifu-wrap zhifufangshi">
					<img class="logo" src="/Public/Weixin/images/yl.png"/>
					<span>银联支付</span>
					<button class="iconfont">&#xe662;</button>
				</div>
			</div>

			
			
			<script>
				function checked_adr(one){
						_this_ = $(one);
						var txt = _this_.html();
						console.log(_this_);
						txt = txt.replace(/<em>.*<\/em>/,"");

						txt = txt.replace(/<p>/,"<p>收件人: ");

						//sender -- 赋给input 默认地址隐藏域 val值
						$('[name="sender"]').val(_this_.attr('oid'));	

						$("#body .tj-header div").html(txt);

						$("#body .alert-bg").hide();

						$("#body .select").hide();
				}
			</script>
			
		
			<div class="tj-dizhi tj-box" name = "选择收货地址">
				<div class="dizhi-wrap" style="height: 14.2rem;overflow: auto;">
					<?php if(is_array($addresslist)): $i = 0; $__LIST__ = $addresslist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="dizhi-top" oid = "<?php echo ($vo["id"]); ?>" onclick="checked_adr(this)">
							 
							<p><?php echo ($vo["realname"]); ?><span><?php echo ($vo["cellphone"]); ?></span><em>点击选择</em></p>
							<i><?php echo ($vo["name"]); ?> <?php echo ($vo["address"]); ?></i>
						</div>
						<div class="dizhi-bottom">
							<div class="dizhi-bianji" onclick="bianjidizhi(<?php echo ($vo["id"]); ?>)">
								<a href="javascript:void(0);" rel="<?php echo ($vo["id"]); ?>"><span class="iconfont">&#xe645;</span> 编辑</a>
							</div>
							<div class="delete" onclick="del_adr(<?php echo ($vo["id"]); ?>)">
								<a href="javascript:void(0);"><span class="iconfont">&#xe6a5;</span> 删除</a>
							</div>
						</div><?php endforeach; endif; else: echo "" ;endif; ?>
				</div>
				<div class="dizhi-wrap-btn" <?php if($addnum > 4): ?>style="display:none"<?php endif; ?>>
					<button class="max-btn btn-red"  type="button" id = "body-xinzen">新增收货地址</button>
				</div>
			</div>
			
			<div class="tj-youhui tj-box" name = "优惠卷">
			
				<div class="zhifu-wrap" onclick="yh_check('')">
					<span>不使用优惠券</span>
					<label>
						<input type="radio" name="cyhqid" value="0" />
						<i class="icon">&#xe61e;</i>
					</label>
				</div>
				<?php if(is_array($yhqlist)): $k = 0; $__LIST__ = $yhqlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><div class="zhifu-wrap" onclick="yh_check('<?php echo ($vo["sn"]); ?>')">
					<span><?php echo ($vo["name"]); ?></span>
					<label>
						<input type="radio" name="cyhqid" value="<?php echo ($vo["sn"]); ?>"/>
						<i class="icon">&#xe61e;</i>
					</label>
				</div><?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
			
			<div class="tj-zhifu1 tj-box" name = "支付形式">
				<div class="zhifu-wrap zhifufangshi" onclick="pay_check(2)">
					<span>微信支付</span>
					<label>
						<input type="radio" name="yhj"/>
						<i class="icon">&#xe61e;</i>
					</label>
				</div>
				<div class="zhifu-wrap" id = "huodaofukuan"  onclick="pay_check(1)">
					<span>货到付款</span>
					<label>
						<input type="radio" name="yhj"/>
						<i class="icon">&#xe61e;</i>
					</label>
				</div>
			</div>
		</div>
	</div>
	</div>
	
	<!--编辑地址、新增地址-->
	<div id = "newDiZhi" oid = "">
			<input type="hidden" id="addressid">
		<div class="dz-input-wrap">
			<input type="text" placeholder="请填写姓名" id="realname"/>
		</div>
		<div class="dz-input-wrap">
			<input type="text" placeholder="请填写联系电话" id="phone"/>
		</div>
		<div class="dz-input-wrap" id = "xuandizhi">
			<span>请选择省、市、区</span>
			<input type="hidden" name="areaid"  id="areaid" value="">
			<div class="iconfont">&#xe662;</div>
		</div>
		<div class="dz-input-wrap">
			<input type="text" placeholder="详细地址"  id="address"/>
		</div>
		<!--<div class="dz-input-wrap">
			<input type="text" placeholder="邮政编码(选填)"/>
		</div>-->
		<div class="dz-input-wrap mrt-20 border-top">
			<span>设为默认收货地址</span>
			<div>
				<label>
					<input id="isdefault" type="checkbox" checked="checked"  name="default"/>
					<i class="icon color-che">&#x3551;</i>
				</label>
			</div>
		</div>
		<button type="button" class="max-btn mrt-40 dz-btn" onclick="new_add_address()">保存</button>
		<button class="max-btn mrt-40" type="button" id="newdizhi-back">返回</button>
		
		<div class="alert-bg" style="display: none;"></div>
		<div class="select" style="display: none;">
			<div class="title"><i>配送至</i><span class="icon" id = "dizhiclose">&#xe652;</span></div>
			<div class="content">
				<div class="select-dz">
					<div class="dizhi-zizhi">
						<div oid = "0" class="hover">省份</div>
						<div oid = "1">市</div>
						<div oid = "2">区</div>
					</div>
					<div class="select-dz-wrap">
						<ul oid = "0">
							 <?php if(is_array($arealist)): $i = 0; $__LIST__ = $arealist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li ovalue="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
						</ul>
						<ul oid = "1"></ul>
						<ul oid = "2"></ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	
	<style type="text/css">
		html,body{
			width: 100%;
		}
		/*新地址*/
		#newDiZhi{
			position: fixed;
			top: 0;
			width: 100%;
			height: 100%;
			right: 0;
			bottom: 0;
			background: #fbf9fe;
			z-index: 1;
			left: 100%;
			transition: left .5s;
		}
		
		
	</style>
	
	
	<script>

	
		
		
			//支付方式弹出框 ajax  微信支付 2  货到付款为 1
			function pay_check(one){
				$('input[name="PayType"]').val(one);
			}
			
	
	
			//优惠券ajax 
			function yh_check(Value){
				$('input[name="yhqid"]').val(Value);
				var total = <?php echo ($all); ?>;
				$.ajax({
						type:'post', //传送的方式,get/post
						url:'<?php echo U("User/checkcode");?>', //发送数据的地址
						data:{couponid:Value,orderprice:total},
						dataType: "json",
						success:function(data){
							if(data.msg == 'yes')
							{
								$('#yhj_price').text(data.yhqprice);
								$('#sf_price').text(data.nowprice);
							}
							else{
								$('#yhj_price').text(0);
								$('#sf_price').text(total);
							}
						},
						error:function (event, XMLHttpRequest, ajaxOptions, thrownError)
						{
							alert("表单错误，"+XMLHttpRequest+thrownError); 
						
						}
					}
				)
			}
			
	
	
	
			//删除地址ajax
			function del_adr(id){
				
				$.ajax({

					type:'post', //传送的方式,get/post

					url:'<?php echo U("Shopcart/deladr");?>', //发送数据的地址

					data:{id:id},

					dataType: "json",

					success:function(data){
						if(data.status == 1)
						{
							
							$('.dizhi-top[oid="' + id + '"]').next('.dizhi-bottom').remove();
							$('.dizhi-top[oid="' + id + '"]').remove();
							
							if($('input[name="sender"]').val()==id)
							{
								$('input[name="sender"]').val('');
								$('.tj-header div').html('<div class="empty_adr">请选择您的收货地址！</div>');
							}
							
							//判断新增地址数量小于5 处理
							
							var adr_length = $('.dizhi-top').size();
							if(adr_length < 5)
							{
								$('.dizhi-wrap-btn').show();
							}
							
							
							
						}
					},

					error:function (event, XMLHttpRequest, ajaxOptions, thrownError)
					{
						alert("表单错误，"+XMLHttpRequest+thrownError); }
					}
					
					
				)
			
				

				
				
			}
	
	
	
	
		
		//新增地址 ajaax
		function new_add_address(){
			
			//判断是否是默认地址
		
	 
		
		if (document.getElementById("isdefault").checked==true) {

		   var info="yes";

		} else {

		   var info="no";

		}

		var addressid=$("#addressid").val();

		var realname=$("#realname").val();

		var areaid=$("#areaid").val();//地区id

		var address=$("#address").val();

		var phone=$("#phone").val();

		if(realname==''){

			alert("收货人不能为空");

			return false;

		}
		
			
			$.ajax({

			type:'post', //传送的方式,get/post

			url:'<?php echo U("Shopcart/savemsg");?>', //发送数据的地址

			data:{addressid:addressid,areaid:areaid,address:address,phone:phone,realname:realname,msg:info},

			dataType: "json",

			success:function(data){
				var text = '<div><p>收件人：' + data.realname + ' <span>' + data.cellphone + '</span></p><i>' + data.adrname + data.address + '</i></div>';
				$('input[name="sender"]').val(data.addressid);
				$('.tj-header').html(text);
				newDizhi.hide();
				if(data.msg=='yes'){
					//if 是点击编辑 地址  将弹出框内容替换成更改后的内容;
					var new_text = '<p>' + data.realname + '<span>' + data.cellphone + '</span><em>点击选择</em></p><i>' + data.adrname + data.address + '</i>';
					$('.dizhi-top[oid="' + data.addressid + '"]').html(new_text);
				}else if(data.msg=='no'){
					//弹出框 添加地址
					var new_text = '<div onclick="checked_adr(this)" class="dizhi-top" oid = "' + data.addressid + '"><p>' + data.realname + '<span>' + data.cellphone + '</span><em>点击选择</em></p><i>' + data.adrname + data.address + '</i></div><div class="dizhi-bottom"><div class="dizhi-bianji" onclick="bianjidizhi(' + data.addressid + ')"><a href="javascript:void(0);" rel="' + data.addressid + '"><span class="iconfont">&#xe645;</span> 编辑</a></div><div class="delete" onclick="del_adr(' + data.addressid + ')"><a href="javascript:void(0);"><span class="iconfont">&#xe6a5;</span> 删除</a></div></div>';
					$('.dizhi-wrap').prepend(new_text);
					
					//判断新增地址数量小于5 处理
					var adr_length = $('.dizhi-top').size();
					if(adr_length >= 5)
					{
						$('.dizhi-wrap-btn').hide();
					}
							
					
					
				}
			},

			error:function (event, XMLHttpRequest, ajaxOptions, thrownError)
			{alert("表单错误，"+XMLHttpRequest+thrownError); }})
		}
	
	
	
		function bianjidizhi(id){
			newDizhi.show(id);
			hideSelect();
		}
		
		function hideSelect(){
			$(".select").hide();
			$(".alert-bg").hide();
		}
		
		function youhuijuan(){
			$("#body .alert-bg").show();
			$("#body .select").show();
			$("#body .select .title i").html($("#body .tj-youhui").attr("name"))
			$("#body .tj-box").hide();
			$("#body .tj-youhui").show();
		}
		
		var newDizhi = {
			show : function(id){
				$("#newDiZhi").css("left","0%").attr("oid",id);
				$("#newDiZhi input").val("");
				$("#xuandizhi span").html("请选择省、市、区");
				
				if(id){
					getAddr(id);
				}
				
			},
			hide : function(){
				$("#newDiZhi").css("left","100%");
			}
		}
	
	
		$(function(){
			
			// #body
			
			$("#body-xinzen").click(function(){
				bianjidizhi()
			})
			
			$("#body label").change(function(){
				$("label .icon").html("&#xe61e;").removeClass("color-che");
				$(".icon",this).html("&#x3551;").addClass("color-che");
			})
			
			$("#zaixianzhifu").click(function(){
					$("#body .tj-box").hide();
					$("#body .tj-zhifu").show();
					$("#body .select .title i").html($("#body .tj-zhifu").attr("name"))
			})
			
			$(".tj-header").click(function(){
				$("#body .alert-bg").show();
				$("#body .select").show();
				$("#body .select .title i").html($("#body .tj-dizhi").attr("name"))
				
				$("#body .tj-box").hide();
				$("#body .tj-dizhi").show();
			})
			
			$("#body .zhifufangshi,#huodaofukuan").click(function(){
				$("#zhifuxingshi span").html($(this).find("span").html());
				bodyclose()
			})
			
			$("#zhifuxingshi").click(function(){
				$("#body .alert-bg").show();
				$("#body .select").show();
				$("#body .select .title i").html($("#body .tj-zhifu1").attr("name"))
				$("#body .tj-box").hide();
				$("#body .tj-zhifu1").show();
			})
			
			$("#bodyclose").click(function(){
				bodyclose();
			})
			
			function bodyclose(){
				$("#body .alert-bg").hide();
				$("#body .select").hide();
			}
			
			

			
			$("#body .tj-youhui .zhifu-wrap").click(function(){
				$("#youhuijuan span").html($(this).find("span").html());
				bodyclose();
			})
			
			// #newDizhi
			
			$("#newDiZhi label").change(function(){
				if($(this).find("input").is(":checked")){
					$(".icon",this).html("&#x3551;");
					$(".icon",this).addClass("color-che");
				}else{
					$(".icon",this).html("&#xe61e;");
					$(".icon",this).removeClass("color-che");
				}
			})
			
			$("#xuandizhi").click(function(){
				$("#newDiZhi .alert-bg").show();
				$("#newDiZhi .select").show();
				showUl(0);
			})
			
			$("#dizhiclose").click(function(){
				dizhiclose();
			})
			
			$("#newdizhi-back").click(function(){
				newDizhi.hide();
			})
			
			$("#newDiZhi .select-dz-wrap ul").eq(0).find("li").click(function(){
				
				$("#newDiZhi .select-dz-wrap ul").eq(0).find("li").removeClass("hover");
				$(this).addClass("hover");
				
				var oid = $(this).attr("ovalue");
				
				if(oid <= 5 || oid == 33 || oid == 34 || oid == 35 || oid == 3358){
					var li = document.createElement("li");
					li.ovalue = oid;
					li.innerHTML = this.innerHTML;
					li.onclick = function(){
						$(this).parent().find("li").removeClass("hover");
						$(this).addClass("hover");
						changearea(this.ovalue , 2);
					}
					$("#newDiZhi .select-dz-wrap ul").eq(1).html("").append(li);
					showUl(1);
				}else{
					changearea(oid , 1);
				}
				
				
			})
			
		})
		
		function showUl(oid,cityid,fn){
			$("#newDiZhi .select-dz-wrap ul").hide();
			$("#newDiZhi .dizhi-zizhi div").removeClass("hover").eq(oid).addClass("hover");
			$("#newDiZhi .select-dz-wrap ul").each(function(){
				if($(this).attr("oid") == oid){
					$(this).show();
				}
			})
		}
		function dizhiclose(){
			var txt = "" , num = 0;
			var citys = [];
			$("#newDiZhi .select-dz-wrap ul").each(function(){
				$(this).find("li").each(function(){
					if($(this).attr("class") == "hover"){
						num++;
						citys.push(this.getAttribute("ovalue") || this.ovalue);
						txt += $(this).html() + " ";
					}
				})
			})
			
			
			
			if(num < 3){
				alert("请完整的选择配送地址!")
				return false;
			}else{
				$("#newDiZhi .alert-bg").hide();
				$("#newDiZhi .select").hide();
			}
			// 请选择省、市、区
			if(txt){
				$("#pro").val(citys[0]);
				$("#city").val(citys[1]);
				$("#area").val(citys[2]);
				$("#areaid").val(citys[2]);
				$("#xuandizhi span").html(txt);
			}else{
				$("#xuandizhi span").html("请选择省、市、区");
			}
		}
		function changearea(oid , indexx){
		
			$.ajax({
		
				type:'post', //传送的方式,get/post
		
				url:'<?php echo U("Shopcart/changearea");?>', //发送数据的地址
		
				data:{pid:oid},
		
				dataType: "json",
		
				success:function(data){
					
					var citys = data.list;
					
					if(data.msg == "yes"){
						
						var ul = document.querySelectorAll("#newDiZhi .select-dz-wrap ul")[indexx];
						ul.innerHTML = "";
						var doms = document.createDocumentFragment();
						for(var i = 0 , len = citys.length ; i < len; i++){
							
							var li = document.createElement("li");
							li.onclick = function(){
								
								$(this).parent().find("li").removeClass("hover");
								$(this).addClass("hover");
								if($(this).parent().attr("oid") < 2){
									changearea(this.ovalue , 2);
								}else{
									dizhiclose();
								}
								
							}
							li.ovalue = citys[i]["id"];
							li.innerHTML = citys[i]["name"];
							li.pid = citys[i]["pid"];
							doms.appendChild(li);
						}
						ul.appendChild(doms);
						showUl(indexx);
						
					}
					
		
				},
		
				error:function (event, XMLHttpRequest, ajaxOptions, thrownError) {alert("表1单错误，"+XMLHttpRequest+thrownError); }
		
			})
		
		}		

		function getAddr(id){
			$.ajax({
	
				type:"post", //传送的方式,get/post
	
				url:'<?php echo U("Shopcart/getaddress");?>', //发送数据的地址
	
				data:{addressid:id},
	
				dataType:"json",
	
				success:function(data){
					
					
					$("#realname").val(data.realname);
	
					$("#phone").val(data.cellphone);
	
					$("#address").val(data.address);
	
					$("#addressid").val(id);
	
					$("#areaid").val(data.area);
	
					$("#areaids").nextAll().remove();//地区下拉框置空
	
					$('.addresslist').hide();
	
					$('#formsender').show();	

					$("#xuandizhi span").html(data.name);
					
					$("#areaid").val(data.addid);

				},
	
				error:function (event, XMLHttpRequest, ajaxOptions, thrownError) {
					alert("表单错误，"+XMLHttpRequest+thrownError); 
				}
	
			})		
		}
		
		function getDizhi(thisvalue,curid){
			$.ajax({

				type:'post', //传送的方式,get/post
		
				url:'<?php echo U("Shopcart/changearea");?>', //发送数据的地址
		
				data:{pid:thisvalue},
		
				dataType: "json",
		
				success:function(data){
					
					console.log(data);
		
				},
		
				error:function (event, XMLHttpRequest, ajaxOptions, thrownError) {alert("表单错误，"+XMLHttpRequest+thrownError); }
		
			})
		}

		
		function checkcode(sn)  //检查优惠券是否可以

		{

			var orderprice = <?php echo ($total); ?>;

			if(sn!==""){

				$.ajax({

					type:'post',

					url:'<?php echo U("User/checkcode");?>', //发送数据的地址//

					data:{couponid:sn,orderprice:orderprice},

					dataType: "json",

					success:function(data){

						if(data.msg=="yes"){

							$("#yhqid").val(sn);

							$("#tyhqid").val('');//清除填写的优惠券信息

							$("#yhqprice").html(data.yhqprice);//优惠券金额

							$("#TotalNeedPay").html(data.nowprice);//最总价格

							$("#TotalNeedPayp").html(data.nowprice);//最总价格

						}else{

							alert(data.info);

							$("#yhqid").val('');

							$("#yhqprice").empty();

							$("#TotalNeedPay").html(orderprice);//最总价格

						}

					} 

				});//ajax结束

			}//if结束

		}		
		
	</script>
</body>
</html>