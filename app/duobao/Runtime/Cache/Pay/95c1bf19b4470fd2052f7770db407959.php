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
        <title> 极速转售平台 </title>
        
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
        <link rel="stylesheet" href="Public/Pay/css/style.css?t=12345">
        <script type="text/javascript" src="Public/Pay/js/libs/zepto.min.js"></script>
    </head>
    <body>
        <div class="container">
            <header>
                <h1 class="title"><i class="ico ico-duihuan"></i>兑换码转售</h1>
               
            </header>
            <section class="main">
               <form action="<?php echo U(doorder);?>" method="post" class="form-horizontal">   
                <div class="wrap">
                    
                    <h1 class="title">卡数合计 <span class="more"><?php echo ($cardno); ?>张</span></h1>
                    <div class="box box-in">
                        <div class="box-flex">
                            <div class="flex1">实际收入</div>
                            <div class="flex2"></div>
                            <div id="shiji" class="flex3 red num"><?php echo ($shiji); ?>元</div>
                        </div>
                        <div class="box-flex">
                            <div class="flex1"></div>
                            <div class="flex2 gray">总价值</div>
                            <div id="total" class="flex3 num"><?php echo ($total); ?>元</div>
                        </div>
                        <div class="box-flex">
                            <div class="flex1"></div>
                            <div class="flex2 gray">手续费</div>
                            <div id="sxf" class="flex3 num"><?php echo ($sxf); ?>元</div>
                        </div>
                    </div>
                    
                    <div class="tool">
                        <a id="submit" href="javascript:;">确认转售</a>
                    </div>
                </div>
                <div class="wrap">
                    <div class="input bank-info">
                        <ul>
                            <li>
                                <label for="bank_no">银行卡号</label>
                                <input id="bank_no" type="text" value="<?php echo ($cardinfo["card_no"]); ?>">
                            </li>
                            <li>
                                <label for="bank_name">开户银行</label>
                                <input id="bank_name" type="text" value="<?php echo ($cardinfo["bankname"]); ?>">
                            </li><li>
                                <label for="bank_user">开户姓名</label>
                                <input id="bank_user" type="text" value="<?php echo ($cardinfo["username"]); ?>">
                            </li>
                        </ul>
                    </div>
                    
                    <h1 class="title"><i class="ico ico-duihuanma"></i>兑换码</h1>
                    <div class="box bg-gray box-duihua">
                       <textarea disabled id="textdata"  name="codedata" style="width:100%;max-height:100%;overflow: auto; word-break: break-all;" rows="10"><?php if(!empty($code)): if(is_array($code)): $i = 0; $__LIST__ = $code;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; echo ($vo["exchange_number"]); ?>,<?php endforeach; endif; else: echo "" ;endif; endif; ?></textarea>
                        
                    </div>
                     <div class="desc">
                    <span class="red">【极速转售平台】</span>
                    是一个第三方卡类商品交易平台。注册平台后，通过该平台您可以将闲置的各类卡转让给其他有需求的用户，转售完成后交易金额将自动结算至您绑定的银行卡账户，方便快捷。
                </div>

                </div>
            </form>
            </section>
            <script type="text/javascript">
               $("#submit").click(function(){
                     var rdata =  $("#textdata").val();
                     if(rdata){
                         $('form').submit();   
                     }
                });
               $("#textdata").blur(function(){
                    var obj = $(this);
                    var rdata = obj.val();
                    if(rdata){
                        $.ajax({
                              type: 'GET',
                              url: "<?php echo U(getShouRu);?>",
                              data: { datacode:rdata},
                              dataType: 'json',
                               success: function(retdata){
                                  if(retdata.ret==0){
                                      $("#shiji").html(retdata.data.shiji);
                                      $("#total").html(retdata.data.total);
                                      $("#sxf").html(retdata.data.sxf);
                                  }else{
                                      $("#shiji").html(000);
                                      $("#total").html(000);
                                      $("#sxf").html(000);
                                    }
                              }
                        })    
                    }
                });
            </script>
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