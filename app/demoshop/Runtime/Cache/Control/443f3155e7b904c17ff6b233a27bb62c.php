<?php if (!defined('THINK_PATH')) exit();?><!doctype html>

<html>
<head>
<meta charset="UTF-8">
<title><?php echo ($meta_title); ?>|一网电商管理平台</title>
<link href="/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">
<link rel="stylesheet" type="text/css" href="/Public/Control/css/base.css" media="all">
<link rel="stylesheet" type="text/css" href="/Public/Control/css/common.css" media="all">
<link rel="stylesheet" type="text/css" href="/Public/Control/css/module.css">
<link rel="stylesheet" type="text/css" href="/Public/Control/css/style.css" media="all">
<link rel="stylesheet" type="text/css" href="/Public/Control/css/<?php echo (C("COLOR_STYLE")); ?>.css" media="all">

<!--[if lt IE 9]>

    <script type="text/javascript" src="/Public/static/jquery-1.10.2.min.js"></script>

    <![endif]--><!--[if gte IE 9]><!-->

<script type="text/javascript" src="/Public/static/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="/Public/Control/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="/Public/Control/js/highcharts.js"></script>
<script type="text/javascript" src="/Public/Control/js/exporting.js"></script>
<script type="text/javascript" src="/Public/Control/js/data.js"></script>

<!--<![endif]-->


</head>

<body>

<!-- 头部 -->

<div class="header"> 

<div class="header_cen">
  
  <!-- Logo --> 
  
  <span class="logo"><img src="/Public/Control/images/logo.png"></span> 
  
  <!-- /Logo --> 
  
  <!-- 主导航 -->
  
  <ul class="main-nav">
    <?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li class="<?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?>"><a href="<?php echo (get_nav_url($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
    <li><a href="<?php echo get_index_url();?>" target="_blank">网站首页</a></li>
  </ul>
  
  <!-- /主导航 --> 
  
  <!-- 用户栏 -->
  
  <div class="user-bar"> <a href="javascript:;" class="user-entrance"><i class="icon-user"></i></a>
    <ul class="nav-list user-menu hidden">
      <li class="manager">你好，<em title="<?php echo session('user_auth.username');?>"><?php echo session('user_auth.username');?></em></li>
      <li><a href="<?php echo U('Cache/delcache');?>">清除缓存</a></li>
      <li><a href="<?php echo U('Admin/updatePassword');?>">修改密码</a></li>
      <li><a href="<?php echo U('Admin/updateNickname');?>">修改昵称</a></li>
      <li><a href="<?php echo U('Public/logout');?>">退出</a></li>
    </ul>
  </div>
  <div class="cls"></div>
</div>  
  
</div>

<!-- /头部 --> 

<!-- 边栏 -->

<div class="sidebar"> 
  
  <!-- 子导航 -->
  
  
    <div id="subnav" class="subnav">
      <?php if(!empty($_extra_menu)): ?>
        
        <?php echo extra_menu($_extra_menu,$__MENU__); endif; ?>
      <?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i;?><!-- 子导航 -->
        
        <?php if(!empty($sub_menu)): if(!empty($key)): ?><h3><i class="icon icon-unfold"></i><?php echo ($key); ?></h3><?php endif; ?>
          <ul class="side-sub-menu">
            <?php if(is_array($sub_menu)): $i = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li> <a class="item" href="<?php echo (U($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a> </li><?php endforeach; endif; else: echo "" ;endif; ?>
          </ul><?php endif; ?>
        
        <!-- /子导航 --><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
  
  
  <!-- /子导航 --> 
  
</div>

<!-- /边栏 --> 

<!-- 内容区 -->

<div id="main-content">
  <div id="top-alert" class="fixed alert alert-error" style="display:none;">
    <button class="close fixed" style="margin-top: 4px;">&times;</button>
    <div class="alert-content">表单验证提示内容！</div>
  </div>
  <div id="main" class="main">
     
      <!-- nav -->
      <?php if(!empty($_show_nav)): ?><div class="breadcrumb"> <span>您的位置:</span>
          <?php $i = '1'; ?>
          <?php if(is_array($_nav)): foreach($_nav as $k=>$v): if($i == count($_nav)): ?><span><?php echo ($v); ?></span>
              <?php else: ?>
              <span><a href="<?php echo ($k); ?>"><?php echo ($v); ?></a>&gt;</span><?php endif; ?>
            <?php $i = $i+1; endforeach; endif; ?>
        </div><?php endif; ?>
      <!-- nav --> 
    
    

	<div class="main-title">

		<h2>

			<?php echo ($info['id']?'编辑':'新增'); ?>订单

		

		</h2>

	</div>

	<ul class="tab-nav nav">

	<li data-tab="tab1" class="current"><a href="javascript:void(0);">订单修改</a></li>

	<li data-tab="tab2" ><a href="javascript:void(0);">订单详情</a>

	

	</li></ul>

<div class="tab-content">						



	<div id="tab1" class="tab-pane in tab1">

	<form action="<?php echo U();?>" method="post" class="form-horizontal">

	<input type="hidden" class="text input-large" name="tag" value="<?php echo ($tag); ?>">
    <input type="hidden" class="text input-large" name="orderid" value="<?php echo ($tag); ?>">

		<div class="form-item">

			<label class="item-label">会员账号<span class="check-tips">（必填）</span></label>

			<div class="controls">
				<input type="text" class="text input-large" name="vname" value="<?php echo ($vname); ?>">
			</div>
            

		</div>

		<div class="form-item">

			<label class="item-label">金额<span class="check-tips">（商品金额）</span></label>

			<div class="controls">

				<input type="text" class="text input-large" name="total" value="<?php echo ($info["total"]); ?>">

			</div>

		</div>





<div class="form-item">

			<label class="item-label">总额<span class="check-tips">（总额）</span></label>

			<div class="controls">

				<input type="text" class="text input-large" name="pricetotal" value="<?php echo ($info["pricetotal"]); ?>">

			</div>

		</div>

		<div class="form-item">

			<label class="item-label">会员备注<span class="check-tips">（备注）</span></label>

			<div class="controls">

				<input type="text" class="text input-large" name="info" value="<?php echo ($info["info"]); ?>">

			</div>

		</div>
<div class="form-item">



			<label class="item-label">快递名称<span class="check-tips">（发货的快递名称）</span></label>



			<div class="controls">



<select name="tool"><?php if(is_array($kuaidilist)): $i = 0; $__LIST__ = $kuaidilist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["type"]); ?>" <?php if($vo['type'] == $info['tool']): ?>selected="selected"<?php endif; ?>><?php echo ($vo["type"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select>



			</div>



		</div>



<div class="form-item">



			<label class="item-label">快递单号<span class="check-tips">（发货的快递单号）</span></label>



			<div class="controls">



				<input type="text" class="text input-large" name="toolid" value="<?php echo ($info["toolid"]); ?>">



			</div>



</div>
<div class="form-item">

			<label class="item-label">运费<span class="check-tips">（发货的快递单号）</span></label>

			<div class="controls">

				<input type="text" class="text input-large" name="shipprice" value="<?php echo ($info["shipprice"]); ?>">

			</div>

		</div>
        
<div class="form-item">

			<label class="item-label">详细地址<span class="check-tips">（必填）</span></label>

			<div class="controls">

				<input type="text" class="text input-large" name="address" value="<?php echo ($info["address"]); ?>">

			</div>

</div>
<div class="form-item">

			<label class="item-label">邮政编码<span class="check-tips"></span></label>

			<div class="controls">

				<input type="text" class="text input-large" name="youbian" value="<?php echo ($info["youbian"]); ?>">

			</div>

</div>
<div class="form-item">

			<label class="item-label">联系电话<span class="check-tips">（必填）</span></label>

			<div class="controls">

				<input type="text" class="text input-large" name="phone" value="<?php echo ($info["phone"]); ?>">

			</div>

</div>
<div class="form-item">

			<label class="item-label">收货人姓名<span class="check-tips">（必填）</span></label>

			<div class="controls">

				<input type="text" class="text input-large" name="realname" value="<?php echo ($info["realname"]); ?>">

			</div>

</div>

		<div class="form-item">

			<label class="item-label">会员留言<span class="check-tips"></span></label>

			<div class="controls">

				<input type="text" class="text input-large" name="message" value="<?php echo ($info["message"]); ?>" readonly>

			</div>

		</div>

<div class="form-item">

			<label class="item-label">订单状态<span class="check-tips">（必填）</span></label>

			<div class="controls">

<select name="status">
	<option value="-1"  <?php if(($info["status"] == -1)): ?>selected="selected"<?php endif; ?> >待付款(货到付款不用选择此项)</option>
    <option value="1" <?php if(($info["status"] == 1)): ?>selected="selected"<?php endif; ?> >待发货</option>
    <option value="2" <?php if(($info["status"] == 2)): ?>selected="selected"<?php endif; ?> >待收货</option>
    <option value="3" <?php if(($info["status"] == 3)): ?>selected="selected"<?php endif; ?> >已完成</option>
    <option value="4" <?php if(($info["status"] == 4)): ?>selected="selected"<?php endif; ?> >已取消</option>					
</select>


			</div>
</div>
<div class="form-item">

			<label class="item-label">支付方式<span class="check-tips">（必填）</span></label>
			<div class="controls">
                <label><input type="radio" name="ispay" value="<?php echo ($info["ispay"]); ?>" <?php if(($info["ispay"] != -1)): ?>checked="checked"<?php endif; ?> />在线支付</label>  
                <label><input type="radio" name="ispay" value="-1" <?php if(($info["ispay"] == -1)): ?>checked="checked"<?php endif; ?> />货到付款</label>
			</div>
    </div>



      <div class="form-item">

			<label class="item-label">操作人<span class="check-tips"></span></label>

			<div class="controls">

				<input type="text" class="text input-large" name="assistant" value="<?php echo ((isset($info["assistant"]) && ($info["assistant"] !== ""))?($info["assistant"]):'暂无'); ?>">

			</div>

		</div>

		<div class="form-item">

			<input type="hidden" name="id" value="<?php echo ((isset($info["id"]) && ($info["id"] !== ""))?($info["id"]):''); ?>">

			<button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-horizontal">确 定</button>

			<button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>

		</div>

	</form></div>

	

	<div id="tab2" class="tab-pane  tab2">

	<table  id="table" class="gridtable" width="100%">

        <thead>

            <tr>

			 <th >商品编号</th>

                <th >商品名</th>

				<th >规格</th>

                <th >价格</th>

                <th >数量</th>

                

            </tr>

        </thead>  </tbody>  <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>

		 <td align="center"><?php echo ($vo["goodid"]); ?></td>

                <td><A href="<?php echo U('Home/Article/detail?id='.$vo['goodid']);?>" > <img src="<?php echo (get_cover(get_cover_id($vo["goodid"]),'path')); ?>"  width="40" height="40"/><?php echo ($vo["goodname"]); ?></A></td>

               <td align="center"> <span class="weight"><?php echo ((isset($vo["parameters"]) && ($vo["parameters"] !== ""))?($vo["parameters"]):"无"); ?></span></td>

                <td align="center"><?php echo ($vo["price"]); ?></td>

                 <td align="center"><?php echo ($vo["num"]); ?></td>

                

            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
          <style type="text/css">
          	.table-cell tr th{ background:#ededed;}
			.table-cell tr th,.table-cell tr td{ padding:10px 10px; text-align:center;}
          </style>
          </tbody></table>  <BR/>  
<table class="table-cell" width="100%">
          	<tr>
            	<th>收件人：</th>
                <th>电话：</th>
                <th>地址：</th>
                <th>小计：</th>
                <th>运费：</th>
                <th>总金额：</th>
                <th>下单时间：</th>
            </tr>
          	<tr>
            	<td><?php echo ($info["realname"]); ?></td>
                <td><?php echo ($info["phone"]); ?></td>
                <td><?php echo ($info["address"]); ?></td>
                <td><?php echo ($info["total"]); ?>元 </td>
                <td><?php echo ($info["shipprice"]); ?>元</td>
                <td><?php echo ($info["pricetotal"]); ?>元</td>
                <td><?php echo (date('Y-m-d H:i:s',$info["create_time"])); ?></td>
            </tr>
</table>
		  </div>

	</div>


  </div>
  <div class="cont-ft">
    <div class="copyright">
      <div class="fl"></div>
      <div class="fr">感谢使用<a href="http://www.ewangtx.com" target="_blank">一网电商系统V<?php echo (EWTHINK_VERSION); ?></a></div>
    </div>
  </div>
</div>

<!-- /内容区 --> 

<script type="text/javascript">

    (function(){

        var ThinkPHP = window.Think = {

            "ROOT"   : "", //当前网站地址

            "APP"    : "/index.php?s=", //当前项目地址

            "PUBLIC" : "/Public", //项目公共目录地址

            "DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符

            "MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],

            "VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]

        }

    })();

    </script> 
<script type="text/javascript" src="/Public/static/think.js"></script> 
<script type="text/javascript" src="/Public/Control/js/common.js"></script> 
<script type="text/javascript">

        +function(){

            var $window = $(window), $subnav = $("#subnav"), url;

            $window.resize(function(){

                $("#main").css("min-height", $window.height() - 130);

            }).resize();



            /* 左边菜单高亮 */

            url = window.location.pathname + window.location.search;

            url = url.replace(/(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)|(\/(group)\/\d+)|(&group=\d+)/, "");

			

            $subnav.find("a[href='" + url + "']").parent().addClass("current") ;



            /* 左边菜单显示收起 */

            $("#subnav").on("click", "h3", function(){

                var $this = $(this);

                $this.find(".icon").toggleClass("icon-fold");

                $this.next().slideToggle("fast").siblings(".side-sub-menu:visible").

                      prev("h3").find("i").addClass("icon-fold").end().end().hide();

            });



            $("#subnav h3 a").click(function(e){e.stopPropagation()});



            /* 头部管理员菜单 */

            $(".user-bar").mouseenter(function(){

                var userMenu = $(this).children(".user-menu ");

                userMenu.removeClass("hidden");

                clearTimeout(userMenu.data("timeout"));

            }).mouseleave(function(){

                var userMenu = $(this).children(".user-menu");

                userMenu.data("timeout") && clearTimeout(userMenu.data("timeout"));

                userMenu.data("timeout", setTimeout(function(){userMenu.addClass("hidden")}, 100));

            });



	        /* 表单获取焦点变色 */

	        $("form").on("focus", "input", function(){

		        $(this).addClass('focus');

	        }).on("blur","input",function(){

				        $(this).removeClass('focus');

			        });

		    $("form").on("focus", "textarea", function(){

			    $(this).closest('label').addClass('focus');

		    }).on("blur","textarea",function(){

			    $(this).closest('label').removeClass('focus');

		    });



            // 导航栏超出窗口高度后的模拟滚动条

            var sHeight = $(".sidebar").height();

            var subHeight  = $(".subnav").height();

            var diff = subHeight - sHeight; //250

            var sub = $(".subnav");

            if(diff > 0){

                $(window).mousewheel(function(event, delta){

                    if(delta>0){

                        if(parseInt(sub.css('marginTop'))>-10){

                            sub.css('marginTop','0px');

                        }else{

                            sub.css('marginTop','+='+10);

                        }

                    }else{

                        if(parseInt(sub.css('marginTop'))<'-'+(diff-10)){

                            sub.css('marginTop','-'+(diff-10));

                        }else{

                            sub.css('marginTop','-='+10);

                        }

                    }

                });

            }

        }();

    </script>


<script type="text/javascript" charset="utf-8">

	//导航高亮

	highlight_subnav('<?php echo U('index');?>');

	if($('ul.tab-nav').length){

		//当有tab时，返回按钮不显示

		$('.btn-return').hide();

	}

	$(function(){

		//支持tab

		showTab();

	})



</script>


</body>
</html>