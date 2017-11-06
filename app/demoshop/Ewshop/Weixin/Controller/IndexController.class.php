<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// |  Author: ewangtx <www@ewangtx.com> 
// +----------------------------------------------------------------------

namespace Weixin\Controller;
use OT\DataDictionary;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 $url= $_SERVER[HTTP_HOST]; //获取当前域名  
 */
class IndexController extends HomeController {
  
	/**系统首页**/
   public function index(){			
		/**首页统计代码实现**/
		if(1==C('IP_TONGJI')){
			$id="index";
			$record=IpLookup("",1,$id);
		}
		
		$datas = array();
		
		/** 幻灯片调用* */
		$slide = get_ad(14);
		
		foreach($slide as $key => $val){
			$newarr = array(
				"url" => get_nav_url($val['url']),
				"icon" => get_cover($val['icon'] , "path"),
				"title" => $val['title']
			);
			$datas['slide'][] = $newarr;
		}
		
		$this->assign('slide',$slide);
		$jinpin1=get_ad(15);
		$this->assign('jinpin1',$jinpin1);
		$jinpin2=get_ad(16);
		$this->assign('jinpin2',$jinpin2);
		$jinpin3=get_ad(17);
		$this->assign('jinpin3',$jinpin3);
		$jinpin4=get_ad(18);
		$this->assign('jinpin4',$jinpin4);
		
		$newarr = array(
			array(
				"url" => get_nav_url($jinpin1[0]['url']),
				"icon" => get_cover($jinpin1[0]['icon'] , "path"),
				"title" => $jinpin1[0]['title']
			),
			array(
				"url" => get_nav_url($jinpin2[0]['url']),
				"icon" => get_cover($jinpin2[0]['icon'] , "path"),
				"title" => $jinpin2[0]['title']
			),
			array(
				"url" => get_nav_url($jinpin3[0]['url']),
				"icon" => get_cover($jinpin3[0]['icon'] , "path"),
				"title" => $jinpin3[0]['title']
			),
			array(
				"url" => get_nav_url($jinpin4[0]['url']),
				"icon" => get_cover($jinpin4[0]['icon'] , "path"),
				"title" => $jinpin4[0]['title']
			)
		);
		$datas['jinpin'] = $newarr;
		//产品分类
		$cate=M('Category');
		$catlist=$cate->where('pid=0 and model=5 and status=1')->order('id asc')->select();
        $this->assign('catlist' , $catlist);
        
        foreach($catlist as $val){
        	$newarr = array(
        		"url" => "Goods/lists?category=",
        		"name" => $val['name'],
        		"icon" => get_cover($val['icon'] , "path"),
        		"title" => $val['title']
        	);
        	$datas['catlist'][] = $newarr;
        }
        
//      	$newarr = array();
        
		//获得首页推荐的商品
		$remailist = remailist();//热卖专区产品
		$this->assign('remailist',$remailist);
		
		$newslist = get_articleByCid(201,5);//动态资讯
		$this->assign('newslist',$newslist);
		
		
		//$tree=get_subcpbycid(148) ;//微信商城产品分类
		$map['display'] = 1;
		$map['status'] = 1;
		$map['ismenu'] = 1;
		$map['pid'] = 0;
		$categorylist = M('Category')->where($map)->select();
		foreach($categorylist as $k=>$v){
			$childids = getalltreeid($v['id']);
			$maps['category_id'] = array('in',$childids);
			$maps['position'] = array('egt',4);
			$maps['status'] = 1;
			$categorylist[$k]['goods'] = M('Document')->where($maps)->order('level DESC ,update_time DESC , create_time DESC')->find();
		}
		
		$this->assign ( 'category', $categorylist);


		$cate=M('Category');
		$catelist=$this->menulist() ;
		$this->assign('categoryq', $catelist);

		$this->assign('page',D('Document')->page);//分页
		$user=M('category');
		$id=$user->where('display=1 and pid=0')->getField('id',true);
		$this->assign('arrr',$id);


		/**购物车调用**/
	//	$cart=D("shopcart")->getcart();
		$this->assign('usercart',$cart);
		if(!session('user_auth')){
			$usercart=$_SESSION['cart'];
			$this->assign('usercart',$usercart);
		}

		$this->meta_title = '首页';
		
		if(@$_GET['ajax'] == "true"){
			echo json_encode($datas);
		}else{
			$this->display();
		}
	}

   /**微信宣传页面首页**/
   public function introduce(){
	
		/**首页统计代码实现**/
		if(1==C('IP_TONGJI')){
			$id="introduce";
			$record=IpLookup("",1,$id);
		}

		$this->meta_title = '宣传页';
		$this->display('Index/introduce');
	}
	
	/**微信大转盘**/
	public function zhuanp(){
	if(1==C('IP_TONGJI')){
			$id="zhuanp";
			$record=IpLookup("",1,$id);
		}
	/* uid调用*/
	$uid=D('member')->uid();
	$score=get_score($uid);
	$this->assign('uid', $uid);

	$map['uid'] = $uid;

	$this->meta_title = '宣传页';
	$this->display('Index/zhuanp');
	}
	
	/**微信大转盘**/
	public function jiangxiang(){

		//if(!is_login()){
		//$this->error( "您还没有登陆",U("User/login") );	
		//}
		/* uid调用*/
		$uid=D('member')->uid();
		$score=get_score($uid);
		$this->assign('uid', $uid);

		
		$nowTime=time();
		$starttime = M('promotionDzp')->where('id=9')->getField('start_time');
		$endtime = M('promotionDzp')->where('id=9')->getField('end_time');
		$star=strtotime($starttime);
		     $over=strtotime($endtime);
			 $over = $over+86399;
				  
		$dzpjp=M('promotionDzpprize');//奖项表
		$dzpjplist=$dzpjp->where('status=1')->order('id ASC')->select();//查询所有奖项
		$jiaodu = 30;
		$number=0;
		foreach ($dzpjplist as $key => $val) { 
			$dzpjplist[$val['id']]['jiaodu'] = $jiaodu; //最小指针角度
			$jiaodu = $jiaodu+60;
			$number += $val['num'];
			
		} 
		foreach ($dzpjplist as $key => $val) { 
			$arr[$val['id']] = $val['bilv']; 
		} 
		$rid = $this->getRand($arr); //根据概率获取奖项id 17
		$name=$dzpjp->where("id='$rid'")->order('level ASC')->find();
		$this->assign('name', $name);
		$res = $dzpjplist[$rid]; //中奖项 
		$prizeid=$name['prize_id'];
		$level=$name['level'];
		$jxname=$name['name'];
		$yhj_num=$name['num'];
		//$yhj_num=$yhj_num-1;
		if ($name['num']>0){
		$shuju = $dzpjp->where("id='$rid'")->setDec('num');	
		}
		 
		$type=$name['type'];
		//$dat=array();
		//$i = 0;
		if($prizeid!=0){
		//for ($j = 0; $j < $yhj_num; $j++)
		//{
		$dat['yhj_id']= $prizeid;
		$dat['sn']= time().str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);//标识+时间戳+4位随机数
        $dat['user_id']= $uid;
		
        //if($i%100 == 0){//每1000条数据执行一次插入。防止数据过大
		if($dat){
				$result = M('PromotionYhjdata')->add($dat);
		}
		//$dat = array();
		//$i = 0;
		}							
		$date['level']= $level;
		$date['name']= $jxname;
		$date['type']= $type;	
        $date['uid']= $uid;	
        $date['create_time']= time();	
         if($date){
				$resultt = M('PromotionDzpdata')->add($date);
		}		
		if ($star <= $nowTime && $over >= $nowTime && $number>0){
			$data['status'] = 1;
		$data['jiaodu'] = $dzpjplist[$rid]['jiaodu'];	//最终指针角度
		$data['title'] = $name['name'];
		}
		if($number==0){
			$data['status'] = 2;
			$data['title'] = '抽奖活动结束';
		}
		if($nowTime < $star){
			$data['status'] = 3;
		$data['title'] = '抽奖活动还未开始';
		}
		if($nowTime > $over){
			$data['status'] = 4;
		$data['title'] = '抽奖活动结束';
		}
	
				
		
		$this->ajaxReturn($data);
	}


	/****************抽奖代码***********************************/	
	public function getRand($proArr) { 
		$result = ''; 
		//概率数组的总概率精度 
		$proSum = array_sum($proArr);//100 
		//概率数组循环 
		foreach ($proArr as $key => $proCur) { 
			$randNum = mt_rand(1, $proSum); 
			if ($randNum <= $proCur) { 
				$result = $key; 
				break; 
			} else { 
				$proSum -= $proCur; 
			} 
		} 
		unset ($proArr); 
	 
		return $result; 
	} 
	
	/**无限极分类菜单调用**/
	 public function menulist(){
		$field = 'id,name,pid,title';
		$categoryq = D('Category')->field($field)->order('sort desc')->where('display="1" and ismenu="1" ')->select();
		$catelist = $this->unlimitedForLevel($categoryq);
		return $catelist;
	}
		
	/**限时抢购**/
	 public function timelist(){

		$time=M('document_product')->order('id desc')->where('mark="2"')->limit("6")->select();
		return $time;

	}
		/**热销产品**/
		public function Carousel(){
			$Carousel=M('document')->where('position="4"')->select();
			return $Carousel;

		}

		/**最新上架**/
	  public function bytime(){
	
			$bytime=M('document_product')->order('id desc')->limit("6")->select();
			return $bytime;

		}
	/**热卖商品**/
	public function totalsales(){
		$totalsales=M('document')->order('sale desc')->limit("6")->select();
		return $totalsales;
	}
	public function unlimitedForLevel($cate,$name = 'child',$pid = 0){
		$arr = array();
		foreach ($cate as $key => $v) {
		//判断，如果$v['pid'] == $pid的则压入数组Child
		if ($v['pid'] == $pid) {
		//递归执行
		$v[$name] = self::unlimitedForLevel($cate,$name,$v['id']);
		$arr[] = $v;
		}
		}
		return $arr;
	}

		/**分类商品**/
	 public function goodlist(){
	
			$str=M('brand')->where('status="1"')->order('ypid')->select();
			return $str;

		}


		/**热门搜索热词**/
		 public function getHotsearch(){
				$arr = array();
				$str=M('config')->where('id="40"')->getField("value");
				$hotsearch=explode(",",$str);
				return $hotsearch;

		}
		/***二级，三级分类调用*/
		 public function getarticle(){
				$category=D("category");
				$list=$category->getparent();
				$detail=M("document");
				foreach($list as $n=> $val){
				$list[$n]['id']=$detail->where('category_id=\''.$val['id'].'\'')->select();
		
				}
				$pa=$category->parent();
				foreach($pa as $n=> $val){
				$list[$n]['id']=$detail=M("document")->where('category_id=\''.$val['id'].'\'')->select();
				array_push($arr[$i],$detail);}
		
		
				return $list;
		}
 		public function makeTree($pid){
			$category = D ( 'Category' )->getTree ($pid);
			foreach ( $category as $k => $v ) {
				$cid=array();
				array_push($cid,$v['id']);
				foreach ( $v ['_'] as $ks => $vs ) {
					array_push($cid,$vs['id']);
					foreach ( $vs ['_'] as $kgs => $vgs ) {
						array_push($cid,$vgs['id']);
					}
				}
				$category [$k] ['doc'] = array ();
				$map['category_id']=array("in",$cid);
				$map['status']=1;
				$category [$k] ['doc'] = M('Document')->where($map)->order("id desc")->limit(10)->select();
			}
			return $category;
		}
		
		/**服务首页**/
   public function service(){
		/**首页统计代码实现**/
		if(1==C('IP_TONGJI')){
		$id="service";
		$record=IpLookup("",1,$id);
		}
		$cate=M('Category');
		$catelist=$this->menulist() ;
		$this->assign('categoryq', $catelist);
		
		$ad=get_ad(7);//服务页面通栏广告
		$this->assign('ad',$ad);
		
		/**购物车调用**/
		$cart=D("shopcart")->getcart();
		$this->assign('usercart',$cart);
		if(!session('user_auth')){
			$usercart=$_SESSION['cart'];
			$this->assign('usercart',$usercart);
		}
		/** 底部分类调用**/
		$menulist=R('Service/AllMenu');
		$this->assign('footermenu',$menulist);

		$this->meta_title = '服务';
		$this->display('Index/service');
	}
	
	//测试
	public function test() {
		/* 获取详细信息 */
		$Document = D('Document');
		$info = $Document->detail($id);
		$this->assign('info', $info);
		$domainurl = $_SERVER['SERVER_NAME'];  
		$this->assign('domainurl', $domainurl);

		$userinfo = session('user_auth');
		$this->assign('userinfo', $userinfo);

		$config = M ( "Wxsetting" )->where ( array ("id" => "1" ) )->find ();

		if($config){
			$shareurl = 'http://'.$domainurl.U('Index/index?share=1&id='.$userinfo['uid']);//分享url
			$fxurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$config['appid']."&redirect_uri=".$shareurl."&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
			//**	应用授权作用域，snsapi_base （不弹出授权页面，直接跳转，只能获取用户openid），snsapi_userinfo （弹出授权页面，可通过openid拿到昵称、性别、所在地。并且，即使在未关注的情况下，只要用户授权，也能获取其信息）**/
			
			$this->assign('shareurl', $shareurl);		
			$this->assign('fxurl', $fxurl);		
				
			$timestamp = time();
			$wxnonceStr = $this->createNonceStr();
			
			// 注意 URL 一定要动态获取，不能 hardcode.
			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
			$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";//当前网页url
			
			$wxticket = $this->wx_get_jsapi_ticket();
			$wxOri = sprintf("jsapi_ticket=%s&noncestr=%s&timestamp=%s&url=%s",
			$wxticket, $wxnonceStr, $timestamp,$url
			);
			$wxSha1 = sha1($wxOri);	
			
			$signPackage = array(
			  "appId"     => $config['appid'],
			  "nonceStr"  => $wxnonceStr,
			  "timestamp" => $timestamp,
			  "url"       => $url,
			  "signature" => $wxSha1,
			  "jsapi_ticket" => $wxticket
			);
			
			$this->assign('signPackage', $signPackage);
		}
		$this->meta_title = '中小学O2O学习服务平台'; 
		$this->display();
	}
		
	function wx_get_jsapi_ticket(){
		$ticket = "";
		do{
			$ticket = S('wx_ticket');
			if (!empty($ticket)) {
				break;
			}
			$token = S('access_token');
			if (empty($token)){
				$this->wx_get_token();
			}
			$token = S('access_token');
			if (empty($token)) {
			   // logErr("get access token error.");
				break;
			}
			$url2 = sprintf("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi", $token);
			$res = file_get_contents($url2);
			$res = json_decode($res, true);
			$ticket = $res['ticket'];
			// 注意：这里需要将获取到的ticket缓存起来（或写到数据库中）
			// ticket和token一样，不能频繁的访问接口来获取，在每次获取后，我们把它保存起来。
			S('wx_ticket', $ticket, 3600);
		}while(0);
		return $ticket;
	}	
	
	function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
		  $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
	
	function wx_get_token() {
		$token = S('access_token');
		if (!$token) {
			$config = M ( "Wxsetting" )->where ( array ("id" => "1" ) )->find ();
			if($config){
				$res = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$config['appid'].'&secret='.$config['appsecret']);
				$res = json_decode($res, true);
				$token = $res['access_token'];
				// 注意：这里需要将获取到的token缓存起来（或写到数据库中）
				// 不能频繁的访问https://api.weixin.qq.com/cgi-bin/token，每日有次数限制
				// 通过此接口返回的token的有效期目前为2小时。令牌失效后，JS-SDK也就不能用了。
				// 因此，这里将token值缓存1小时，比2小时小。缓存失效后，再从接口获取新的token，这样
				// 就可以避免token失效。
				// S()是ThinkPhp的缓存函数，如果使用的是不ThinkPhp框架，可以使用你的缓存函数，或使用数据库来保存。
				S('access_token', $token, 3600);
			}
		}
		return $token;
	}

	
	
}