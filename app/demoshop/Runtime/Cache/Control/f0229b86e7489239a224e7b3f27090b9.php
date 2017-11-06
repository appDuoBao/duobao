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
		<h2><?php echo isset($info['id'])?'编辑':'新增';?>大转盘</h2>
	</div>

	<div class="tab-wrap">

		<div class="tab-content">

			<form action="<?php echo U();?>" method="post" class="form-horizontal">

				<!-- 基础 -->
				<div id="tab1" class="tab-pane in tab1">
					<div class="form-item">
						<label class="item-label">
							大转盘名称<span class="check-tips">（名称不能为空）</span>
						</label>
						<div class="controls">
							<input type="text" name="name" class="text input-large" value="<?php echo ((isset($info["name"]) && ($info["name"] !== ""))?($info["name"]):''); ?>">
						</div>
					</div>                                 
					<div class="form-item">
						<label class="item-label">
							使用起始时间<span class="check-tips"></span>
						</label>
						<div class="controls">
							<input type="text" name="start_time" class="text time" value="<?php echo ((isset($info["start_time"]) && ($info["start_time"] !== ""))?($info["start_time"]):''); ?>">
						</div>
					</div>
					<div class="form-item">
						<label class="item-label">
							使用结束时间<span class="check-tips"></span>
						</label>
						<div class="controls">
							<input type="text" name="end_time" class="text time" value="<?php echo ((isset($info["end_time"]) && ($info["end_time"] !== ""))?($info["end_time"]):''); ?>">
						</div>
					</div>
 					<div class="form-item">
						<label class="item-label">
							抽奖资格<span class="check-tips"></span>
						</label>
                        <div class="controls">
                            <select name="zige" id="zige">
                                <option value="1" <?php if($info['zige']==1): ?>selected<?php endif; ?> >所有平台用户</option>
                            </select>                          
                        </div>
					</div>
 					<div class="form-item">
						<label class="item-label">
							抽奖方式<span class="check-tips"></span>
						</label>
                        <div class="controls">
                            <select name="type" id="type">
                                <option value="1" <?php if($info['type']==1): ?>selected<?php endif; ?> >仅一次</option>
                                <option value="2" <?php if($info['type']==2): ?>selected<?php endif; ?> >每天一次</option>
                            </select>                          
                        </div>
					</div>                    
 					<div class="form-item">
						<label class="item-label">
							抽奖次数<span class="check-tips"></span>
						</label>
						<div class="controls">
							<input type="text" name="times" class="text input-large" value="<?php echo ((isset($info["times"]) && ($info["times"] !== ""))?($info["times"]):'1'); ?>">
						</div>
					</div>  
                    <div class="form-item">
						<label class="item-label">描述</label>
						<div class="controls">
                            <label class="textarea input-large">
                                <textarea name="content"><?php echo ((isset($info["content"]) && ($info["content"] !== ""))?($info["content"]):''); ?></textarea>
                            </label>
						</div>
					</div>                                        
 					<div class="form-item">
						<label class="item-label">
							状态<span class="check-tips"></span>
						</label>
                        <div class="controls">
                            <select name="type" id="type">
                                <option value="1" <?php if($info['status']==1): ?>selected<?php endif; ?> >正常</option>
                                <option value="2" <?php if($info['status']==2): ?>selected<?php endif; ?> >禁用</option>
                            </select>                          
                        </div>
					</div>  
				</div>

				<div class="form-item">
					<button type="submit" id="submit" class="btn submit-btn ajax-post" target-form="form-horizontal">确 定</button>
					<button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
				</div>

			</form>

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


<link href="/Public/static/datetimepicker/css/datetimepicker.css" rel="stylesheet" type="text/css">

<?php if(C('COLOR_STYLE')=='blue_color') echo '<link href="/Public/static/datetimepicker/css/datetimepicker_blue.css" rel="stylesheet" type="text/css">'; ?>

<link href="/Public/static/datetimepicker/css/dropdown.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="/Public/static/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript" src="/Public/static/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>

<script type="text/javascript">
$(function(){

    $('.date').datetimepicker({
        format: 'yyyy-mm-dd',
        language:"zh-CN",
        minView:2,
        autoclose:true,
		todayBtn:true,
		todayHighlight:true
    });

    $('.time').datetimepicker({
        format: 'yyyy-mm-dd hh:ii:ss',
        language:"zh-CN",
        minView:0,
        autoclose:true,
		todayBtn:true,
		todayHighlight:true
    });
});	
</script>
<script type="text/javascript">
	//导航高亮
	highlight_subnav('<?php echo U('PromotionDzp/index');?>');
</script>


</body>
</html>