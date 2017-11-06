<?php if (!defined('THINK_PATH')) exit();?><!doctype html>

<html>
<head>
<meta charset="UTF-8">
<title><?php echo ($meta_title); ?>|微信系统管理平台</title>
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
    <?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i; if($menu["title"] != '推广'): ?><li class="<?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?>"><a href="<?php echo (get_nav_url($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
<!--     <li><a href="<?php echo get_index_url();?>" target="_blank">网站首页</a></li> -->
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
  <div id="top-alert" class="fixed alert alert-error" style="display: none;">
    <button class="close fixed" style="margin-top: 4px;">&times;</button>
    <div class="alert-content">这是内容</div>
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

		<h2>财务统计</h2>

	</div>
    <div class="cf">
        <div class="fl">
        </div>
        <!-- 高级搜索 -->
        <div class="search-form fr cf">

            <div class="sleft">
                <input type="date" name="start_date" value="<?php echo I('start_date');?>" class="search-input" />开始日期&nbsp;&nbsp;
            </div>

            <div class="sleft">
                <input type="date" name="end_date" value="<?php echo I('end_date');?>" class="search-input" />结束日期&nbsp;&nbsp;
            </div>
            <a href="javascript:;" id="search" url="<?php echo U('tongji');?>" class="btn sch-btn">搜索</a>
        </div>
    </div>
	<div class="data-table table-striped">

        <table id="datatable">
            <thead>
                <tr>
                    <th></th>
                    <th>总销量(元)</th>
                    <th>总奖金额</th>
                    <th>已兑奖金额</th>
                    <th>未兑奖金额</th>
                    <th>利润</th>
                </tr>
            </thead>
            <tbody>
            <?php if($sstallprice != ''): ?><tr>
                <th><?php echo I('start_date');?>至<?php echo I('end_date');?></th>
                <td><?php echo ((isset($sstallprice) && ($sstallprice !== ""))?($sstallprice):"0"); ?></td>
                <td><?php echo ($sstdhprice+$sstwdhprice); ?></td>
                <td><?php echo ((isset($sstdhprice) && ($sstdhprice !== ""))?($sstdhprice):"0"); ?></td>
                <td><?php echo ((isset($sstwdhprice) && ($sstwdhprice !== ""))?($sstwdhprice):"0"); ?></td>
                <td><?php echo ($sstallprice-$sstwdhprice-$sstdhprice); ?>元</td>
            </tr>
            <tr>
                <th></th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr><?php endif; ?>
            <tr>
                    <th>总计</th>
                    <td><?php echo ((isset($allprice) && ($allprice !== ""))?($allprice):"0"); ?></td>
                    <td><?php echo ($dhprice+$wdhprice); ?></td>
                    <td><?php echo ((isset($dhprice) && ($dhprice !== ""))?($dhprice):"0"); ?></td>
                    <td><?php echo ((isset($wdhprice) && ($wdhprice !== ""))?($wdhprice):"0"); ?></td>
                    <td><?php echo ($allprice-$wdhprice-$dhprice); ?>元</td>
                </tr>

                <tr>
                    <th>今日</th>
                    <td><?php echo ((isset($tallprice) && ($tallprice !== ""))?($tallprice):"0"); ?></td>
                    <td><?php echo ($tdhprice+$twdhprice); ?></td>
                    <td><?php echo ((isset($tdhprice) && ($tdhprice !== ""))?($tdhprice):"0"); ?></td>
                    <td><?php echo ((isset($twdhprice) && ($twdhprice !== ""))?($twdhprice):"0"); ?></td>
                    <td><?php echo ($tallprice-$twdhprice-$tdhprice); ?>元</td>
                </tr>

                <tr>
                    <th>昨日</th>
                    <td><?php echo ((isset($tallprice3) && ($tallprice3 !== ""))?($tallprice3):"0"); ?></td>
                    <td><?php echo ($tdhprice3+$twdhprice3); ?></td>
                    <td><?php echo ((isset($tdhprice3) && ($tdhprice3 !== ""))?($tdhprice3):"0"); ?></td>
                    <td><?php echo ((isset($twdhprice3) && ($twdhprice3 !== ""))?($twdhprice3):"0"); ?></td>
                    <td><?php echo ($tallprice3-$twdhprice3-$tdhprice3); ?>元</td>
                </tr>

                <tr>
                    <th>前天</th>
                    <td><?php echo ((isset($tallprice4) && ($tallprice4 !== ""))?($tallprice4):"0"); ?></td>
                    <td><?php echo ($tdhprice4+$twdhprice4); ?></td>
                    <td><?php echo ((isset($tdhprice4) && ($tdhprice4 !== ""))?($tdhprice4):"0"); ?></td>
                    <td><?php echo ((isset($twdhprice4) && ($twdhprice4 !== ""))?($twdhprice4):"0"); ?></td>
                    <td><?php echo ($tallprice4-$twdhprice4-$tdhprice4); ?>元</td>
                </tr>

            </tbody>
        </table>
	</div>


  </div>
  <div class="cont-ft">
    <div class="copyright">
      <div class="fl">感谢使用微信管理系统</div>
      <div class="fr">V<?php echo (ONETHINK_VERSION); ?></div>
    </div>
  </div>
</div>

<!-- /内容区 --> 

<script type="text/javascript">

    (function(){

        var ThinkPHP = window.Think = {

            "ROOT"   : "", //当前网站地址

            "APP"    : "/control.php?s=", //当前项目地址

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


 <script type="text/javascript">
     //搜索功能
     $("#search").click(function () {
         var url   = $(this).attr('url');
         var query = $('.search-form').find('input').serialize();
         query     = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g , '');
         query     = query.replace(/^&/g , '');
         if (url.indexOf('?') > 0) {
             url += '&' + query;
         } else {
             url += '?' + query;
         }
         window.location.href = url;
     });

     $("#export").click(function () {
         var url   = $(this).attr('url');
         var query = $('.search-form').find('input').serialize();
         query     = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g , '');
         query     = query.replace(/^&/g , '');
         if (url.indexOf('?') > 0) {
             url += '&' + query;
         } else {
             url += '?' + query;
         }
         window.location.href = url;
     });

     //回车搜索
     $(".search-input").keyup(function (e) {
         if (e.keyCode === 13) {
             $("#search").click();
             return false;
         }
     });
            //导航高亮

            highlight_subnav("<?php echo U('tongji');?>");

    </script>


</body>
</html>