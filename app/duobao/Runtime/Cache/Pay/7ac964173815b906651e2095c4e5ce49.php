<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <meta name="apple-mobile-web-app-title" content="MGJH5" />
        <meta name="format-detection" content="telephone=no" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="grey" />
        <title> 转售记录 </title>
        
        <script type="text/javascript">
        (function(win,lib){var doc=win.document;var docEl=doc.documentElement;var metaEl=doc.querySelector('meta[name="viewport"]');var flexibleEl=doc.querySelector('meta[name="flexible"]');var dpr=0;var scale=0;var tid;var flexible=lib.flexible||(lib.flexible={});if(metaEl){var match=metaEl.getAttribute("content").match(/initial-scale=([d.]+)/);if(match){scale=parseFloat(match[1]);dpr=parseInt(1/scale)}}else{if(flexibleEl){var content=flexibleEl.getAttribute("content");if(content){var initialDpr=content.match(/initial-dpr=([d.]+)/);var maximumDpr=content.match(/maximum-dpr=([d.]+)/);if(initialDpr){dpr=parseFloat(initialDpr[1]);scale=parseFloat((1/dpr).toFixed(2))}if(maximumDpr){dpr=parseFloat(maximumDpr[1]);scale=parseFloat((1/dpr).toFixed(2))}}}}if(!dpr&&!scale){var isAndroid=win.navigator.appVersion.match(/android/gi);var isIPhone=win.navigator.appVersion.match(/iphone/gi);var devicePixelRatio=win.devicePixelRatio;if(isIPhone){if(devicePixelRatio>=3&&(!dpr||dpr>=3)){dpr=3}else{if(devicePixelRatio>=2&&(!dpr||dpr>=2)){dpr=2}else{dpr=1}}}else{dpr=1}scale=1/dpr}docEl.setAttribute("data-dpr",dpr);if(!metaEl){metaEl=doc.createElement("mdeta");metaEl.setAttribute("name","viewport");metaEl.setAttribute("content","initial-scale="+scale+", maximum-scale="+scale+", minimum-scale="+scale+", user-scalable=no");if(docEl.firstElementChild){docEl.firstElementChild.appendChild(metaEl)}else{var wrap=doc.createElement("div");wrap.appendChild(metaEl);doc.write(wrap.innerHTML)}}function refreshRem(){var width=docEl.getBoundingClientRect().width;if(width/dpr>640){width=640*dpr}var rem=width/6.4;docEl.style.fontSize=rem+"px";flexible.rem=win.rem=rem}win.addEventListener("pageshow",function(e){if(e.persisted){clearTimeout(tid);tid=setTimeout(refreshRem,300)}},false);if(doc.readyState==="complete"){doc.body.style.fontSize=12*dpr+"px"}else{doc.addEventListener("DOMContentLoaded",function(e){doc.body.style.fontSize=12*dpr+"px"},false)}refreshRem();flexible.dpr=win.dpr=dpr;flexible.refreshRem=refreshRem;flexible.rem2px=function(d){var val=parseFloat(d)*this.rem;if(typeof d==="string"&&d.match(/rem$/)){val+="px"}return val};flexible.px2rem=function(d){var val=parseFloat(d)/this.rem;if(typeof d==="string"&&d.match(/px$/)){val+="rem"}return val}})(window,window["lib"]||(window["lib"]={}));
          
          (function() {
            var win = window,
                doc = win.document,
                isAndroid = win.navigator.appVersion.match(/android/gi),
                isIPhone = win.navigator.appVersion.match(/iphone/gi),
                platform = isIPhone ? 'iphone' : (isAndroid ? 'android' : 'other');
            if (doc.readyState === 'complete') {
              doc.body.setAttribute('data-platform', platform);
            } else {
              doc.addEventListener('DOMContentLoaded', function(e) {
                doc.body.setAttribute('data-platform', platform);
              }, false);
            }
          })();
        </script>
        <link rel="stylesheet" href="Public/Pay/css/style.css?t=aaabc">
        <script type="text/javascript" src="Public/Pay/js/libs/zepto.min.js"></script>
    </head>
    <body>
        <div class="container record">
             <header>
                <h1 class="title"><i class="ico ico-duihuan"></i>兑换码转售</h1>
            </header>
            <section class="tips">
              <p>银行晚间21:30-22:00系统维护</p>
            </section>
            <section class="main ">
               <div class="wrap record-wrap">
                   <h1 class="title">转售记录 <?php date('Y-m-d H:i:s'); ?></h1>
                   <div class="box record-con">
                       <ul>
                         
                           <?php if(!empty($list)): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                               <label class="tit">转售单号</label>
                               <span class="con"><?php echo ($vo["pay_id"]); ?></span>
                           </li><?php endforeach; endif; else: echo "" ;endif; endif; ?> 
                          

                       </ul>
                   </div>
               </div>
            </section>
             <footer>
                <ul class="footer-link">
                    <li>
                      <a href="<?php echo U('index');?>"> 
                        <?php if($sytlei == 1): ?><i  class="ico1 ico-index"></i> 
                          <?php else: ?>
                            <i  class="ico1 ico-indexn"></i><?php endif; ?> 
                         </a>
                        <span>首页</span>
                    </li>
                    <li>
                       <a href="<?php echo U('cardlist');?>"> <?php echo ($style); ?>
                         <?php if($sytlec == 1): ?><i class="ico1 ico-bank">
                            <?php else: ?>
                                 <i class="ico1 ico-bankn"><?php endif; ?>  
                            </i> </a>
                        <span>银行卡</span>
                    </li>
                    <li>
                      <a href="<?php echo U('recordlist');?>"> 
                          <?php if($sytler == 1): ?><i class="ico1 ico-record"></i>
                          <?php else: ?>
                             <i class="ico1 ico-recordn"></i><?php endif; ?>
                           </a>
                        <span>转售记录</span>
                    </li>
                </ul>
            </footer>
        </div>
         <script type="text/javascript" >
               function test(){
                    alert('aaabb');
                }
            </script>

    </body>
</html>
</html>