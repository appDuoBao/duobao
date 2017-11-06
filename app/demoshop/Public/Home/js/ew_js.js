// JavaScript Document
//网站头部'下拉特效
jQuery(function(){
	
//我的订单和帮助中心下拉特效
jQuery('.ew_li_dingdan').hover(
function(){
	jQuery(this).children('.ttop_li_cont').show();
	jQuery(this).children('.ew_ri').addClass('ewhover');
	},
function(){
	jQuery(this).children('.ttop_li_cont').hide();
	jQuery(this).children('.ew_ri').removeClass('ewhover');;
	}
)

//菜单导航，特效
var scrollTop = 0;
$(window).scroll(function(even){
	var scrolTop	=	parseInt($(document).scrollTop());
	var menuOfset 	=	parseInt($('.ew_menu').offset().top);
	scrollTop			=	scrolTop > menuOfset ?  (scrolTop - menuOfset)-38 : 0;
	
	$('.ew_menu_box_li_main').css({
		marginTop	:	scrollTop
	})
	
})


jQuery('.ew_menu_box_li').hover(
	function(){
		
		$('.ew_menu_box_li_main').css({
			marginTop	:	scrollTop
		})
		
		jQuery(this).addClass('et_hover').children('.ew_menu_box_li_main').stop(true,true).fadeIn(0);
		},
	function(){
		jQuery(this).removeClass('et_hover').children('.ew_menu_box_li_main').stop(true,true).fadeOut(0);
	
	
	}
)



//内页菜单鼠标悬浮弹出效果，首页不弹出
jQuery('.ew_menu_cen_caidan').hover(function(){
			if(jQuery(this).children('.ew_menu_box').hasClass('ew_neibox_block'))
			{
			jQuery(this).children('.ew_menu_box').toggle();
			}
})
})





















