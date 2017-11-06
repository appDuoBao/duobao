<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com  All rights reserved.
// +----------------------------------------------------------------------
// |  Author: ewangtx <www@ewangtx.com> 
// +----------------------------------------------------------------------
namespace Weixin\Controller;
use Think\Controller;
/*****个人中心
***************/
class RecordController extends HomeController {

	/*报名记录*/
	public  function index() {  
		if(!is_login()){
			$this->error( "您还没有登陆",U("User/login") );		
		}
		/* 数据分页*/
		$Member=D("member");
		$uid=$Member->uid();
		
		$registration=M("registration_record");
		$count=$registration->where(" uid='$uid'")->count();
		$this->assign('count', $count);
		$Page= new \Think\Page($count,8);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show();
		$list=$registration->where("uid='$uid'")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $key => $val){
			$institution_id=$val['institution_id'];
			$list[$key]['institution_name']=M('member')->where("uid='$institution_id'")->getField("institution_name");
			$list[$key]['logo']=M('institution')->where("uid='$institution_id'")->getField("logo");


		}
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);//	

		$this->meta_title = get_username().'的报名记录';
		$this->display();	

	}
	
	/*咨询记录*/
	public  function consult_record() {  
		if(!is_login()){
			$this->error( "您还没有登陆",U("User/login"));		
		}
		/* 数据分页*/
		$Member=D("member");
		$uid=$Member->uid();
		
		$consult=M("consult_record");
		$count=$consult->where(" uid='$uid'")->count();
		$this->assign('count', $count);
		$Page= new \Think\Page($count,8);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show();
		$list=$consult->where("uid='$uid'")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $key => $val){
			$institution_id=$val['institution_id'];
			$list[$key]['institution_name']=M('member')->where("uid='$institution_id'")->getField("institution_name");
			$list[$key]['logo']=M('institution')->where("uid='$institution_id'")->getField("logo");
			$list[$key]['location']=M('institution')->where("uid='$institution_id'")->getField("location");
			$list[$key]['tel_banke']=M('institution')->where("uid='$institution_id'")->getField("tel_banke");
			$list[$key]['tel_yiduiyi']=M('institution')->where("uid='$institution_id'")->getField("tel_yiduiyi");
			$list[$key]['comment_nums']=M('comment_record')->where("institution_id='$institution_id'")->count();
		}
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);//	

		$this->meta_title = get_username().'的咨询记录';
		$this->display();	

	}
	
	
	/* 待评价*/
	public  function uncomment() {  
		if(!is_login()){
			$this->error( "您还没有登陆",U("User/login") );		
		}
		/* 数据分页*/
		$Member=D("member");
		$uid=$Member->uid();
		
		$registration=M("registration_record");
		$count=$registration->where(" status=0 and uid='$uid'")->count();
		$this->assign('count', $count);
		$Page= new \Think\Page($count,8);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show();
		$list=$registration->where("status=0 and uid='$uid'")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $key => $val){
			$institution_id=$val['institution_id'];
			$list[$key]['institution_name']=M('member')->where("uid='$institution_id'")->getField("institution_name");
			$list[$key]['logo']=M('institution')->where("uid='$institution_id'")->getField("logo");


		}
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);//	

		$this->meta_title = get_username().'的待评价';
		$this->display();	

	}

	
	/* 发表评论*/
	public  function comment_publish() {  
		if(!is_login()){
			$this->error( "您还没有登陆",U("User/login") );		
		}
		/* 数据分页*/
		$Member=D("member");
		$uid=$Member->uid();	
		if (IS_POST) {
			$registration_id=$_POST['registration_id'];//报名记录id
			$data['uid']=$uid;
			$data['create_time']=time();
			$data['institution_id']=$_POST['institution_id'];
			$data['registration_id']=$registration_id;
			$data['content']=$_POST['content'];
			$result = M('comment_record')->add($data);
			if(false !== $result) {
				M('registration_record')->where("id = '$registration_id'")->setField('status','1');
				M('institution')->where("uid='$_POST[institution_id]'")->setInc('comment_nums');	//增加机构评论数			
				$this->success('评价成功！',U("Record/uncomment"));
			}else{
				$this->error('评价失败',U("Record/uncomment"));
			}
		}else{
			$this->error('评价失败',U("Record/uncomment"));
		}

	}

	/* 已评价*/
	public  function commented() {  
		if(!is_login()){
			$this->error( "您还没有登陆",U("User/login") );		
		}
		/* 数据分页*/
		$Member=D("member");
		$uid=$Member->uid();
		
		//待评价数
		$registration=M("registration_record");
		$uncount=$registration->where(" status=0 and uid='$uid'")->count();
		$this->assign('uncount', $uncount);
		
		$comment_record=M("comment_record");
		$count=$comment_record->where("uid='$uid'")->count();
		$this->assign('count', $count);
		$Page= new \Think\Page($count,8);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show();
		$list=$comment_record->where("uid='$uid'")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $key => $val){
			$institution_id=$val['institution_id'];
			$list[$key]['institution_name']=M('member')->where("uid='$institution_id'")->getField("institution_name");
		}
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);//	

		$this->meta_title = get_username().'的待评价';
		$this->display();

	}

/*****用户签到
***************/
 public  function enter() {
	if(!is_login()){
	$this->error( "您还没有登陆",U("User/login") );	
		}
       $user=session('user_auth');
	   $uid=$user["uid"]; 
	   $iswork=D("iswork");
	   $qtime=NOW_TIME;$d=date('H:i:s',$qtime);
	   $time=$iswork->where("uid='$uid'")->order("id desc")->limit(1)->getfield('create_time');
	    $member=D("member"); // 实例化对象
	   if($time){/*签过到*/
	   $a=date('Ymd',$qtime);/*格式时间戳为 20141024*/
	   $b=date('Ymd',$time);
	   $c=date('H:i:s',$time);
		   if($a!==$b){/*比较*/
		   /*新增签到*/
		    $data['uid'] = $uid;
             $data['status'] = "1";
		     $data['create_time']=NOW_TIME;
            if($iswork->add($data))
				{
               $member->where("uid='$uid'")->setInc('score',10); // 用户的积分加10
               $data['score']=$member->where("uid='$uid'")->getfield('score');
		      $c=date('y-m-d:h:i:s',$time);
			  $data['msg'] = "已签到，积分+10";
			  $data['status'] = "1";
	          $this->ajaxreturn($data); 
		     }
         
			}
			 else{/*签过到*/
              $data['status'] = "0";
			 $data['msg'] = "今天".$c."已签过到";
			  $data['score']=$member->where("uid='$uid'")->getfield('score');
	         $this->ajaxreturn($data); 
			
			 
	        }

	   }
	   else{/*首次签到*/
	    $data['uid'] = $uid;
        $data['status'] = "1";
		 $data['create_time']=NOW_TIME;
       $member->where("uid='$uid'")->setInc('score',10); // 用户的积分加10

         if($iswork->add($data))
		  {  $data['score']=$member->where("uid='$uid'")->getfield('score');
			 $data['msg'] = "首次签到，已签到，积分+10,签到时间：".$d;
	     $this->ajaxreturn($data);
	     }
	  
	  
	  }

		 
}
/***站内信***/
 public  function envelope() {  
	 if(!is_login()){
	$this->error( "您还没有登陆",U("User/login") );	
		}
	 /* 购物车调用*/
		$cart=R("shopcart/usercart");
		$this->assign('usercart',$cart);
		if(!session('user_auth'))
		{
		$usercart=$_SESSION['cart'];
		$this->assign('usercart',$usercart);
		}
		/* 底部分类调用*/
		$menulist=R('Service/AllMenu');
		$this->assign('footermenu',$menulist);
		/* 热词调用*/
		$hotsearch=R("Index/getHotsearch");
		$this->assign('hotsearch',$hotsearch);
		$menu=R('index/menulist');
		$this->assign('categoryq', $menu);
		$table=D("personenvelope");
		$Member=D("member");
		$uid=$Member->uid();
		$condition['uid'] = $uid;
		$condition['group'] ="2";
		$condition['username'] =get_regname($uid);
		$condition['_logic'] = 'OR';
		$count=$table->where($condition)->count();
		$Page= new \Think\Page($count,10);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show();
		$list=$table->where($condition)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page',  $show);
		$this->meta_title = '我的收藏';
		$this->display();
		}
/***站内信读取***/
public  function msg() {
		
		if(!is_login()){
	$this->error( "您还没有登陆",U("User/login") );	
		}
		/* 购物车调用*/
		$cart=R("shopcart/usercart");
		$this->assign('usercart',$cart);
		if(!session('user_auth'))
		{
		$usercart=$_SESSION['cart'];
		$this->assign('usercart',$usercart);
		}
		/* 底部分类调用*/
		$menulist=R('Service/AllMenu');
		$this->assign('footermenu',$menulist);
		/* 热词调用*/
		$hotsearch=R("Index/getHotsearch");
		$this->assign('hotsearch',$hotsearch);
		$menu=R('index/menulist');
		$this->assign('categoryq', $menu);
		$envelope= M("personenvelope");
		$uid=D("member")->uid();
		$id=I("get.id");
		/* 更新浏览数 */
		$map = array('id' => $id);
		$envelope->where($map)->setInc('view');
		$list=$envelope->find($id);
		$envelope->where($map)->setField("status",2);
		$this->assign("list",$list);
		$this->meta_title = '查看站内信';
		$this->display();
		}
/*****获取用户uid
***************/
 public  function uid() {
       $user=session('user_auth');
	   $uid=$user["uid"]; 
	   return $uid;
		 
}
/*****全部订单
***************/
public  function allorder(){
	  if(!is_login()){
	$this->error( "您还没有登陆",U("User/login") );	
		}
		/* 购物车调用*/
     $cart=R("shopcart/usercart");
    $this->assign('usercart',$cart);
      if(!session('user_auth'))
		{ 
		 $usercart=$_SESSION['cart'];
       $this->assign('usercart',$usercart); 
	    }
		   /* 底部分类调用*/
   $menulist=R('Service/AllMenu');
   $this->assign('footermenu',$menulist);
  /* 热词调用*/
    $hotsearch=R("Index/getHotsearch");
    $this->assign('hotsearch',$hotsearch);
	  /* 分类调用*/
	  $menu=R('index/menulist');
	   $this->assign('categoryq', $menu);
	     /* 数据分页*/
	   $Member=D("member");
	   $uid=$Member->uid();
       $order=M("order");
	   $detail=M("shoplist");
	   $count=$order->where(" uid='$uid'  and total!=''")->count();
	 $Page= new \Think\Page($count,5);
	   $Page->setConfig('prev','上一页');
      $Page->setConfig('next','下一页');
      $Page->setConfig('first','第一页');
      $Page->setConfig('last','尾页');
      $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
      $show= $Page->show();    
      $list=$order->where("uid='$uid'  and total!=''")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
	    foreach($list as $n=> $val){
       $list[$n]['id']=$detail->where('orderid=\''.$val['id'].'\'')->select();}
	   
     $this->assign('allorder',$list);// 赋值数据集 
	  $this->assign('page',$show);//   
	     $this->meta_title = '我的所有订单'; $this->display();
    }
 /* 待支付订单*/
public  function needpay(){
	if(!is_login()){
	$this->error( "您还没有登陆",U("User/login") );	
		}
		/* 购物车调用*/
     $cart=R("shopcart/usercart");
    $this->assign('usercart',$cart);
      if(!session('user_auth'))
		{ 
		 $usercart=$_SESSION['cart'];
       $this->assign('usercart',$usercart); 
	    }
		   /* 底部分类调用*/
   $menulist=R('Service/AllMenu');
   $this->assign('footermenu',$menulist);
  /* 热词调用*/
    $hotsearch=R("Index/getHotsearch");
    $this->assign('hotsearch',$hotsearch);
	  /* 分类调用*/
	  $menu=R('index/menulist');
	   $this->assign('categoryq', $menu);
	     /* 数据分页*/
	   $Member=D("member");
	   $uid=$Member->uid();
       $order=M("order");
	   $detail=M("shoplist");
	   $count=$order->where("uid='$uid' and status='-1' and ispay='1'")->count();
	 $Page= new \Think\Page($count,5);
	   $Page->setConfig('prev','上一页');
      $Page->setConfig('next','下一页');
      $Page->setConfig('first','第一页');
      $Page->setConfig('last','尾页');
      $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
      $show= $Page->show();    
      $list=$order->where("uid='$uid' and status='-1' and ispay='1'")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
	    foreach($list as $n=> $val){
       $list[$n]['id']=$detail->where('orderid=\''.$val['id'].'\'')->select();}
     $this->assign('needpay',$list);// 赋值数据集 
	  $this->assign('page',$show);//   
	     $this->meta_title = '待支付订单'; 
		 $this->display();
    }

      
/* 待发货订单*/
public  function tobeshipped(){
	 if(!is_login()){
	$this->error( "您还没有登陆",U("User/login") );	
		}
		/* 购物车调用*/
     $cart=R("shopcart/usercart");
    $this->assign('usercart',$cart);
      if(!session('user_auth'))
		{ 
		 $usercart=$_SESSION['cart'];
       $this->assign('usercart',$usercart); 
	    }
		   /* 底部分类调用*/
   $menulist=R('Service/AllMenu');
   $this->assign('footermenu',$menulist);
  /* 热词调用*/
    $hotsearch=R("Index/getHotsearch");
    $this->assign('hotsearch',$hotsearch);
	  /* 分类调用*/
	  $menu=R('index/menulist');
	   $this->assign('categoryq', $menu);
	     /* 数据分页*/
	   $Member=D("member");
	   $uid=$Member->uid();
       $order=M("order");
	   $detail=M("shoplist");
	   $count=$order->where("uid='$uid' and status='1' ")->count();
	 $Page= new \Think\Page($count,5);
	   $Page->setConfig('prev','上一页');
      $Page->setConfig('next','下一页');
      $Page->setConfig('first','第一页');
      $Page->setConfig('last','尾页');
      $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
      $show= $Page->show();    
      $list=$order->where("uid='$uid' and status='1' ")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
	    foreach($list as $n=> $val){
       $list[$n]['id']=$detail->where('orderid=\''.$val['id'].'\'')->select();}
     $this->assign('tobeshipped',$list);// 赋值数据集 
	  $this->assign('page',$show);//   
	     $this->meta_title = '待发货订单'; 
		 $this->display();
    }
/* 待发货订单*/
public  function tobeconfirmed(){
	 if(!is_login()){
	$this->error( "您还没有登陆",U("User/login") );	
		}
		/* 购物车调用*/
     $cart=R("shopcart/usercart");
    $this->assign('usercart',$cart);
      if(!session('user_auth'))
		{ 
		 $usercart=$_SESSION['cart'];
       $this->assign('usercart',$usercart); 
	    }
		   /* 底部分类调用*/
   $menulist=R('Service/AllMenu');
   $this->assign('footermenu',$menulist);
  /* 热词调用*/
    $hotsearch=R("Index/getHotsearch");
    $this->assign('hotsearch',$hotsearch);
	  /* 分类调用*/
	  $menu=R('index/menulist');
	   $this->assign('categoryq', $menu);
	     /* 数据分页*/
	   $Member=D("member");
	   $uid=$Member->uid();
       $order=M("order");
	   $detail=M("shoplist");
	   $count=$order->where("uid='$uid' and status='2' ")->count();
	 $Page= new \Think\Page($count,5);
	   $Page->setConfig('prev','上一页');
      $Page->setConfig('next','下一页');
      $Page->setConfig('first','第一页');
      $Page->setConfig('last','尾页');
      $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
      $show= $Page->show();    
      $list=$order->where("uid='$uid' and status='2' ")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
	    foreach($list as $n=> $val){
       $list[$n]['id']=$detail->where('orderid=\''.$val['id'].'\'')->select();}
     $this->assign('tobeconfirmed',$list);// 赋值数据集 
	  $this->assign('page',$show);//   
	     $this->meta_title = '待发货订单'; 
		 $this->display();
    }

/*****购物车
***************/
public  function shopcart() {

	     /* 底部分类调用*/
   $menulist=R('Service/AllMenu');
   $this->assign('footermenu',$menulist);
        $uid=$this->uid();
		$cart=D("shopcart");
	    $cartlist=$cart->getcart();
		return $cartlist; 
}
/*****收藏夹
***************/
public  function collect() {  
	
	if(!is_login()){
	$this->error( "您还没有登陆",U("User/login") );	
		}
	/* 购物车调用*/
     $cart=R("shopcart/usercart");
    $this->assign('usercart',$cart);
      if(!session('user_auth'))
		{ 
		 $usercart=$_SESSION['cart'];
       $this->assign('usercart',$usercart); 
	    }
		   /* 底部分类调用*/
   $menulist=R('Service/AllMenu');
   $this->assign('footermenu',$menulist);
  /* 热词调用*/
    $hotsearch=R("Index/getHotsearch");
    $this->assign('hotsearch',$hotsearch);
	$menu=R('index/menulist');
	$this->assign('categoryq', $menu);
	$table=D("favortable");  $Member=D("member");
	   $uid=$Member->uid();
	  $count=$table->where(" uid='$uid' ")->count();
	 $Page= new \Think\Page($count,10);
	   $Page->setConfig('prev','上一页');
      $Page->setConfig('next','下一页');
      $Page->setConfig('first','第一页');
      $Page->setConfig('last','尾页');
      $Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
      $show= $Page->show();    
      $favorlist=$table->where("uid='$uid' ")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
    $this->assign('favorlist', $favorlist);
	$this->assign('page',  $show);
	    $this->meta_title = '我的收藏';
	$this->display();
}
public  function coupon() {   
	if(!is_login()){
	$this->error( "您还没有登陆",U("User/login") );	
		}
		/* 购物车调用*/
     $cart=R("shopcart/usercart");
    $this->assign('usercart',$cart);
      if(!session('user_auth'))
		{ 
		 $usercart=$_SESSION['cart'];
       $this->assign('usercart',$usercart); 
	    }
  /* 热词调用*/
    $hotsearch=R("Index/getHotsearch");
    $this->assign('hotsearch',$hotsearch);
/* 菜单调用*/
	$menu=R('index/menulist');
	$this->assign('categoryq', $menu);
	/* 会员调用*/
		$member=D("member");
		$uid=$member->uid();
	//总金额调用
	$account =   M('Member')->getFieldByUid($uid,'account');
	$this->assign('account', $account);
	/* 优惠券调用*/
	$coupon=M("usercoupon")->where("uid='$uid' ")->select();
    $this->assign('couponlist', $coupon);
		$fcoupon=M("fcoupon")->where("display='1' and status='1' ")->select();;
    $this->assign('fcouponlist', $fcoupon);
	    $this->meta_title = '我的优惠券';$this->display();
}

/*****个人资料
***************/
public  function information() {   /* 购物车调用*/
    if(!is_login()){
	$this->error( "您还没有登陆",U("User/login") );	
		}
		$cart=R("shopcart/usercart");
    $this->assign('usercart',$cart);
      if(!session('user_auth'))
		{ 
		 $usercart=$_SESSION['cart'];
       $this->assign('usercart',$usercart); 
	    }
		   /* 底部分类调用*/
   $menulist=R('Service/AllMenu');
   $this->assign('footermenu',$menulist);
  /* 热词调用*/
    $hotsearch=R("Index/getHotsearch");
    $this->assign('hotsearch',$hotsearch);
	$menu=R('index/menulist');
	   $this->assign('categoryq', $menu);
        $uid=$this->uid();
		$order=D("member");
 $faceid=M('ucenter_member')->where("id='$uid'")->getField("face");
$this->assign('faceid', $faceid);

	    $ucenter=$order->where("uid='$uid'")->select();
		    $this->meta_title = get_username().'个人中心';
			$this->assign('information', $ucenter);
	  $this->display();
}


public  function comment() {  
	if(!is_login()){
		$this->error( "您还没有登陆",U("User/login") );	
	}
	
	/* 购物车调用*/
	$cart=R("shopcart/usercart");
	$this->assign('usercart',$cart);
	if(!session('user_auth')){ 
		$usercart=$_SESSION['cart'];
		$this->assign('usercart',$usercart); 
	}
	/* 底部分类调用*/
	$menulist=R('Service/AllMenu');
	$this->assign('footermenu',$menulist);
	/* 热词调用*/
	$hotsearch=R("Index/getHotsearch");
	$this->assign('hotsearch',$hotsearch);
	$menu=R('index/menulist');
	$this->assign('categoryq', $menu);
	$comment=D("comment");  
	$Member=D("member");
	$uid=$Member->uid();
	$count=$comment->where(" uid='$uid' ")->count();
	$Page= new \Think\Page($count,10);
	$Page->setConfig('prev','上一页');
	$Page->setConfig('next','下一页');
	$Page->setConfig('first','第一页');
	$Page->setConfig('last','尾页');
	$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
	$show= $Page->show();    
	$commentlist=$comment->where("uid='$uid' ")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();

	$this->assign('comment', $commentlist);
	$this->assign('page',  $show);
	$this->meta_title = get_username().'的评论管理';
	/* 差评*/
	$bad = M("comment")->where("status='1' and uid='$uid' and score='1'")->count();
	$common = M("comment")->where("status='1' and uid='$uid' and score='2'")->count();   
	$best = M("comment")->where("status='1' and uid='$uid' and score='3'")->count();   
	$this->assign('bad', $bad);
	$this->assign('common',$common);
	$this->assign('best',$best);
	$this->display();
}

/*删除评论*/
public  function delcomment() {
	$delcomment_id= I('comment_id');
	if(M('comment')->where("id='$delcomment_id'")->delete()){
		$this->success('删除评论成功！',U("center/comment"));	
	}else{
		$this->error('删除评论失败！');
	}

}

public  function consult() {  
	if(!is_login()){
		$this->error( "您还没有登陆",U("User/login") );	
		}
		$member=D(member);
		$uid=$member->uid();
		$consult=M(consult);
		$teacher=$consult->where("uid='$uid' AND status='1'")->select();
		$this->assign('list',$teacher);
		$this->display();
}

public  function update() {
 if(!is_login()){
	$this->error( "您还没有登陆",U("User/login") );	
		}
		$m=D("member");
		$uid=$m->uid();
		$member=M("ucenter_member");
	    $data = $member->create(); 
		$result =$member->where("id='$uid'")->save();
        if($result) {
            $this->success('修改成功！',U("center/information"));
        }else{
            $this->error('修改失败！');
        }
  
	    
}
public  function address() {
   if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
	}
	$cart=R("shopcart/usercart");
	$this->assign('usercart',$cart);
	  if(!session('user_auth'))
	{ 
		$usercart=$_SESSION['cart'];
		$this->assign('usercart',$usercart); 
	}
		
	$comparea = M('Comparea');
	$map['pid'] = 0;
	$arealist = $comparea->where($map)->select();
	$this->assign('arealist',$arealist);//地区列表
	
	/* 底部分类调用*/
	$menulist=R('Service/AllMenu');
	$this->assign('footermenu',$menulist);

	$menu=R('index/menulist');
	$this->assign('categoryq', $menu);
	$m=D("member");
	$uid=$m->uid();

	$address=M("transport"); 
	$list=$address->where("uid='$uid'")->select();
	foreach($list as $k=>$v){
		$list[$k]['address'] = $this->getAllarea($v['area']).$v['address'];
	}
	$this->assign('addnum', count($list));
	$this->assign('list', $list);
	$this->meta_title = get_username().'的地址管理'; 

	$ucmember=M("ucenter_member")->where("id='$uid'")->limit(1)->find();
	$member=M("member")->where("uid='$uid'")->limit(1)->find();
	
	$userinfo = array_merge($ucmember, $member); 
	$this->meta_title = get_username().'个人中心';
	$this->assign('userinfo', $userinfo);
	$this->display();	

    
}


	//根据areaid获取完整城市信息
	public function getAllarea($areaid) {
		//城市地区
		$area = M('Comparea');
		$map['id'] = $areaid;
		$areainfo = $area->where($map)->find();
		if($areainfo['pid'] && $areainfo['pid']!=0){
			$arealists .= $this->getAllarea($areainfo['pid']).$areainfo['name'];
		}else{
			$arealists = $areainfo['name'];
		}			
		return $arealists;
	}
public  function shezhi() {
	if(!is_login()){
		$this->error( "您还没有登陆",U("User/login") );	
	}
	if(IS_AJAX){
		$m=D("member");
		$uid=$m->uid();
		$Transport = M("transport"); // 实例化transport对象
		$data['status'] = 0;
		$Transport->where("uid='$uid'")->save($data);
		$id=$_POST["id"];
		$result=$Transport->where("uid='$uid' and id='$id'")->setField("status",1);
		if($result){
			$msg = "设置成功";
			$this->ajaxreturn($msg);
		}else{
			$msg = "设置失败";
			$this->ajaxreturn($msg);
		}
	}
}

// 增加地址
public  function save() {
	if(!is_login()){
		$this->error( "您还没有登陆",U("User/login") );	
	}
	$Transport = M("transport"); // 实例化transport对象
	$data['address'] = $_POST["posi"];
	$data['cellphone'] = $_POST["pho"];
	$data['realname'] = $_POST["rel"];
	$Member=D("member");
	$uid=$Member->uid();
	$data['uid'] = $uid;
	$data['status'] = 0;
	$data['time']=NOW_TIME;
	if($Transport->add($data)){
		$id=$Transport->where("uid='$uid'")->limit(1)->order("id desc")->getField("id");
		$this->ajaxreturn($id);
	}else{
		$this->ajaxreturn($data);
	}
}
// 删除地址
public  function deleteAddress() {
	if ( !is_login() ) {
		$this->error( "您还没有登陆",U("User/login") );
	}
	$Transport = M("transport"); // 实例化transport对象
	$Member=D("member");
	$uid=$Member->uid();
	$id=$_POST["id"];
	if($Transport->where("uid='$uid' and id='$id'")->delete()){
		 $data['msg'] = "删除成功";
		 $data['status'] = 1;
	 	 $this->ajaxreturn($data);
	}else{ 
		 $data['msg'] = "删除失败";
		 $data['status'] = 0;
		 $this->ajaxreturn($data);
	}
}

//删除地址
public  function delAddress() {
	if ( !is_login() ) {
		$this->error( "您还没有登陆",U("User/login") );
	}
	$Transport = M("transport"); // 实例化transport对象
	$Member=D("member");
	$uid=$Member->uid();
	$id=$_GET["id"];
	if($Transport->where("uid='$uid' and id='$id'")->delete()){
		 $this->success("删除成功",U('center/address'));
		 $data['status'] = 1;
		 $Transport->where("uid='$uid' and id='$id'")->setfield($data);
	}else{ 
		 $this->success("失败",U('center/address'));
		 $data['status'] = 0;
		 $Transport->where("uid='$uid' and id='$id'")->setfield($data);
	}
}



//会员分销列表
public function disbutlist() {
	if ( !is_login() ) {
		$this->error( "您还没有登陆",U("User/login") );
	}
	//$cid = D('Category')->getChildrenId('148');
	//$where['category_id']=array("in",$cid);
	$where['status']=1;
	$count=M('Document')->where($where)->count();
	$Page= new \Think\Page($count,10);
	$Page->setConfig('prev','上一页');
	$Page->setConfig('next','下一页');
	$Page->setConfig('first','第一页');
	$Page->setConfig('last','尾页');
	$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
	$show= $Page->show();
//	$Member=D("member");
//	$uid=$Member->uid();
//	$zid=getdisuser($uid);
//	$where['id']=$zid;
	$list= M('Document')->where($where)->order($listsort)->limit($Page->firstRow.','.$Page->listRows)->select();
	$this->assign('list',$list);// 赋值数据集
	$this->assign('page',$show);//
	$this->meta_title = get_username().'分享产品列表'; 
	$this->display();
}

//会员分销产品详情
public function disbutinfo($id = 0) {
	if ( !is_login() ) {
		$this->error( "您还没有登陆",U("User/login") );
	}

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
		$shareurl = 'http://'.$domainurl.U('Article/detail?id='.$id.'&pd='.$userinfo['uid']);//分享url
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
	$this->meta_title = get_username().'的专属分享链接'; 
	$this->display();
}



	//下级分销会员列表
	public function disbutuserlist() {
		if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}
		$Member=D("member");
		$uid=$Member->uid();
		$ids = getdisuserids($uid);
		$where['uid']=array('in',$ids);
		$where['status']=1;
		$count=$Member->where($where)->count();
		$Page= new \Think\Page($count,7);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show();
		$list= $Member->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $k=>$v){
			$fujiid = M('Member')->getFieldByUid($v['uid'],'puid');
			if($fujiid==$uid){
				$list[$k]['pd'] = 1;
			}else{
				$list[$k]['pd'] = 0;
			}
		}
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);//
		$this->meta_title = get_username().'分销会员列表'; 
		$this->display();
	}
	//下级分销金额记录
	public function disbutmoneyshow() {
		if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}
		$downid=  I('get.downid', 0);//获取分类的英文名称
		if($downid<1){
			$this->error( "不存在此人",U("center/disbutuserlist") );
		}
		$Member=D("member");
		$uid=$Member->uid();
		$pid = M('Member')->getFieldByUid($downid,'puid');
		if($uid!=$pid){
			$this->error( "只能查看自己的分销会员",U("center/disbutuserlist") );
		}
		$downname = M('Member')->getFieldByUid($downid,'nickname ');
		$map['pid|uid'] = $downid;
		$count=M('Disbut')->where($map)->count();
		$Page= new \Think\Page($count,7);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show();
		$list= M('Disbut')->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $k=>$v){
			if($v['pid']==$downid){
				$uname = $Member->getFieldByUid($v['uid'],'nickname');
				$list[$k]['upmember'] = $downname;
				$list[$k]['moneypeople'] = $uname;
			}elseif($v['uid']==$downid){
				$list[$k]['upmember'] = '';
				$list[$k]['moneypeople'] = $downname;
			}
		}
		$this->assign('list',$list);// 赋值数据集
		$this->assign('downname',$downname);// 分销会员姓名
		$this->assign('page',$show);//
		$this->meta_title = '来自'.$downname.'分销会员金额记录列表'; 
		$this->display();
	}	
	
	//分销商下级分销金额记录
	public function membermnshow() {
		if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}
		$downid=  I('get.downid', 0);//获取分类的英文名称
		if($downid<1){
			$this->error( "不存在此人",U("/Home/center/disbutuserlist") );
		}
		$Member=D("member");
		$uid=$Member->uid();
		$pid = getuserrootid($downid);
		if($uid!=$pid){
			$this->error( "只能查看自己的下级分销会员",U("/Home/center/disbutuserlist") );
		}
		$downname = M('Member')->getFieldByUid($downid,'nickname ');
		$map = array();
		$map['pid'] = $downid;
		$count=M('Disbut')->where($map)->count();
		$Page= new \Think\Page($count,7);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show();
		$list= M('Disbut')->order('createtime desc')->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $k=>$v){
			
				$uname = $Member->getFieldByUid($v['uid'],'nickname');

		
				$list[$k]['moneypeople'] = $uname;
			
		}
		$this->assign('list',$list);// 赋值数据集
		$this->assign('downname',$downname);// 分销会员姓名
		$this->assign('page',$show);//
		$this->meta_title = '来自'.$downname.'分销会员金额记录列表'; 
		$this->display();
	}	

	//分销金额列表
	public function disbutorderlist() {
		if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}
		$Member=D("member");
		$uid=$Member->uid();
		$map['pid|ppid'] = $uid;
		$count=M('Disbut')->where($map)->count();
		$Page= new \Think\Page($count,7);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show();
		$list= M('Disbut')->order('createtime desc')->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
		$alldisbutmoney = 0;
		foreach($list as $k => $v){
			if($v['ppid']!=$uid){
				$list[$k]['fromname'] = M('Member')->getFieldByUid($v['uid'],'nickname');
			}else{
				$list[$k]['fromname'] = M('Member')->getFieldByUid($v['pid'],'nickname');
			}
		}
		$alllist=M('Disbut')->where($map)->select();
		foreach($alllist as $ak=>$av){
			$alldisbutmoney += $av['disbutmoney'];
		}
		$this->assign('alldisbutmoney',$alldisbutmoney);// 获得的佣金总数
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);//
		$this->meta_title = get_username().'分销金额列表'; 
		$this->display();
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
//注册公司地区联动菜单
	public function changearea(){
		$comparea = M('Comparea');
		$pid = htmlspecialchars($_POST["pid"]);
		if($pid){	
			$map['pid'] = $pid;
			$result = $comparea->where($map)->select();
			if($result){
				$data['msg'] = 'yes';	
				$data['list'] = $result;
			}else{
				$data['msg'] = 'no';	

			}
			$this->ajaxReturn($data);
		}else{
			$data['msg'] = 'other';	
			$this->ajaxReturn($data);
		}
    }	
		//地区联动菜单
	public function getregion(){
		$region = M('Linkage');
		$pid = htmlspecialchars($_POST["pid"]);
		if($pid){	
			$map['parentid'] = $pid;
			$result = $region->where($map)->select();
			if($result){
				$map1['parentid'] = $result[0]['linkageid'];
				$result1= $region->where($map1)->select();
				$data['flag'] = $result1;
				
				$data['msg'] = 'yes';	
				$data['list'] = $result;
			}else{
				$data['msg'] = 'no';	
				$data['list'] = null;
			}
			$this->ajaxReturn($data);
		}else{
			$data['msg'] = 'no';	
			$data['list'] = null;
			$this->ajaxReturn($data);
		}
    }	

// 删除关注
public  function deletefavor() {
	if (!is_login()){
		$this->error( "您还没有登陆",U("User/login") );
	}
	$favortable = M("favortable"); // 实例化transport对象
	$Member=D("member");
	$uid=$Member->uid();
	$id=$_POST["id"];
	if($favortable->where("uid='$uid' and id='$id'")->delete()){
		$data['msg'] = "删除成功";
		$this->ajaxreturn($data);
	}else{ 
		$data['msg'] = "删除失败";
		$this->ajaxreturn($data);
	}
}  

//分销商查看所有子集分销会员
	public function downmemberlist() {
		if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}
		$Member=D("member");
		$uid=$Member->uid();
		$isdis = $Member->getFieldByUid($uid,'isdis');
		if(!$isdis){
			$this->error( "您没有权限查看分销商信息",U("/Home/center/disbutuserlist") );
		}
		$map=array();
		$map['puid'] = array('gt',0);
		
		$allmemberlist = M('Member')->where($map)->select();
		$newmemberlist = '';
	
		foreach($allmemberlist as $k=>$v){
				if(getuserrootid($v['uid'])==$uid){
					if($newmemberlist==''){
						$newmemberlist = $v['uid'];
					}else{
						$newmemberlist.=','.$v['uid'];
					}
					
				}
		}
		$map=array();
		$map['uid'] = array('in',$newmemberlist);
		
		$count=M('Member')->where($map)->count();
		$Page= new \Think\Page($count,7);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show();
		$list= M('Member')->order('puid asc, num desc')->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();

		


		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);//
		$this->meta_title = get_username().'所有下级分销会员列表'; 
		$this->display();
	}

//分销商修改下级分销金额百分比
	public function editdisnum() {
		if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}
		$Member=D("member");
		$uid=$Member->uid();
		
		if (IS_POST){
			$data = array();
			$map = array();
			$map['uid'] = $_POST["uid"];
			if($map['uid']<1){
			$this->error( "不存在此人",U("/Home/center/disbutuserlist") );
		}

			$pid = getuserrootid($map['uid']);
			if($uid!=$pid){
			$this->error( "只能修改自己的下级分销会员",U("/Home/center/disbutuserlist") );
			}
			$data['disnum']=$_POST["disnum"];
			$data['uid']=$_POST["uid"];
			

			$data = $Member->create($data); 
			$result = $Member->where($map)->save();
			if(false !== $result) {
				$this->success('修改成功！',U("center/downmemberlist"));
			}else{
				$this->error('修改失败！');
			}
		}else{
		$downid=  I('get.downid', 0);//获取uid
		
		if($downid<1){
			$this->error( "不存在此人",U("/Home/center/disbutuserlist") );
		}

		$pid = getuserrootid($downid);
		if($uid!=$pid){
			$this->error( "只能修改自己的下级分销会员",U("/Home/center/disbutuserlist") );
		}
		$downname = M('Member')->getFieldByUid($downid,'nickname ');
			
			$info = $Member->where("uid='$downid'")->find();
		}
		
		
		$this->assign('info',$info);//
		$this->meta_title = '来自'.$downname.'分销会员金额百分比'; 
		$this->display();
	}	
/**
	 * 修改头像
	 * @author ew_xiaoxiao <www@ewangtx.com>
	 */
	public  function editface() {
		if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}
		$uid=$this->uid();

			$ucmember=M("ucenter_member")->where("id='$uid'")->limit(1)->find();
			$this->assign('info',$ucmember);  	
			$this->meta_title = '修改头像';
			$this->display();	
		
	}
	
/**
	 * 修改头像
	 * @author ew_xiaoxiao <www@ewangtx.com>
	 */
	public  function changeface() {
		if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}
		$uid=$this->uid();
		//上传文件类型列表
		$uptypes=array(
			'image/jpg',
			'image/jpeg',
			'image/png',
			'image/pjpeg',
			'image/gif',
			'image/bmp',
			'image/x-png'
		);

		$max_file_size=2000000;     //上传文件大小限制, 单位BYTE
		$destination_folder="Uploads/Picture/face/"; //上传文件路径
		if (!is_uploaded_file($_FILES["face"]['tmp_name']))
		//是否存在文件
		{
			$this->error('图片不存在');
			
		}

		$file = $_FILES["face"];
		if($max_file_size < $file["size"])
		//检查文件大小
		{
			$this->error('图片太大了');
		
		}

		if(!in_array($file["type"], $uptypes))
		//检查文件类型
		{
		$this->error('文件类型不符合');
			
		}

		if(!file_exists($destination_folder))
		{
			mkdir($destination_folder);
		}		
		
		$filename=$file["tmp_name"];
		$image_size = getimagesize($filename);
		$pinfo=pathinfo($file["name"]);
		$ftype=$pinfo['extension'];
		$destination = $destination_folder.$uid.".".$ftype;
		if (file_exists($destination) && $ftype)
		{
			unlink($destination);
			
		}

		if(!move_uploaded_file ($filename, $destination))
		{
			$this->error('图片上传失败');
		}
		$destination='/'.$destination;
		$picid = M('UcenterMember')->getFieldById($uid,'face');
		$picdata['path'] = $destination;
		$picdata['status'] = 1;
		$picdata['create_time'] = NOW_TIME;
		if($picid){
			$pic['id'] = $picid;

			M('Picture')->where($pic)->save($picdata);
			$this->success('修改成功！',U("center/editface"));
		}else{
			$picid = M('Picture')->add($picdata);
			$um['face']=$picid;
			M('UcenterMember')->where("id='$uid'")->save($um);
			$this->success('头像添加成功！',U("center/editface"));
		}
		
	}	
		//####我要提现####//
		public function up_money(){
			
		if(!is_login()){
			$this->error( "您还没有登陆",U("User/login") );	
		}
		$uid=$this->uid();
		$member=M("Member");
		$row=$member->where("uid='$uid'")->getfield('bankcard');
		if(!$row){
			$this->display('buffer');
			exit();
		}else{
			
			$this->display();
		}
		
	}
	/*****更改银行卡***************/
	public  function changebank() {
		if(!is_login()){
			$this->error( "您还没有登陆",U("User/login") );	
		}
		$uid=$this->uid();
		$bankcard = M('Member')->getFieldByUid($uid,'bankcard');
		$bankcard = substr($bankcard, -4);
				$this->assign('bankcard', $bankcard);
		$this->display('up_money');
	}	
	//提交页面
	public function binding(){
		$this->display();
	}
	/*********提现***********/
	public  function cash() {
		if(!is_login()){
			$this->error( "您还没有登陆",U("User/login") );	
		}
		$uid=$this->uid();
		if (IS_POST) {
			$member=M("Member");
			$bankdata['bankname'] = $_POST['bankname'];
			$bankdata['bankcard'] = $_POST['bankcard'];
			$bankdata['bankpeople'] = $_POST['bankpeople'];
			if($uid){
				$mapuser['uid'] = $uid;
				$result = $member->where($mapuser)->save($bankdata);
				if(false !== $result) {
					$this->success('绑定成功',U("center/up_money"));
				}else{
					$this->error('绑定失败',U("center/cash"));
				}
			}
			
		}else{
			$member=M("member")->where("uid='$uid'")->limit(1)->find();	
			
			$this->meta_title = get_username().'的账户中心';
			$this->assign('userinfo', $member);
					if($member['bankcard']=='' || $member['bankname']=='' || $member['bankpeople']==''){
			$this->display('binding');	
			}else{
				
				$bankcard = substr($member['bankcard'], -4);
				$this->assign('bankcard', $bankcard);
			$this->display();	
			}			
		}
		
	}	

	
	
	/*********提交提现，进入审核状态***********/
	public  function examine() {
		if(!is_login()){
			$this->error( "您还没有登陆",U("User/login") );	
		}
		$uid=$this->uid();

		if (IS_POST) {
			$money = $_POST['money'];
			if($money<100){
				$this->success('金额最低100',U("center/up_money"));
			}
			$member=M("member")->where("uid='$uid'")->limit(1)->find();
			if($money>$member['account']){
				$this->success('提现额度超出您的账户余额',U("center/up_money"));
			}
			$examinedata['bankname'] = $member['bankname'];
			$examinedata['bankcard'] = $member['bankcard'];
			$examinedata['bankpeople'] = $member['bankpeople'];
			$examinedata['uid'] = $member['uid'];
			$examinedata['money'] = $money;
			$examinedata['create_time'] = time();
			$examinedata['status'] = 0;
			if($uid){
					$nowmoney = $member['account']-$money;
					$medata['account'] = $nowmoney;
					$mapuser['uid'] = $uid;
					$result = M('Member')->where($mapuser)->save($medata);
				if($result){
					$Examineid = M('Cash')->add($examinedata);
					if($Examineid) {
						$this->success('提交审核中，请等待',U("center/disbutcashlist"));
					}else{
						$this->error('提交失败',U("center/disbutcashlist"));
					}
				}else{
					$this->success('操作错误，请重试',U("center/disbutcashlist"));
				}

			}
			
		}else{
			$this->error('请完善您的资料',U("center/cash"));
		}
		
	}		
		/*********提现记录***********/
	public  function cashrecord() {
		if(!is_login()){
			$this->error( "您还没有登陆",U("User/login") );	
		}
		$uid=$this->uid();
		$member=M("member")->where("uid='$uid'")->limit(1)->find();

		$cash =M("Cash");
		$count=$cash ->where(" uid='$uid'")->count();
		$this->assign('anum', $count);
		$Page= new \Think\Page($count,5);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show();
		$list=$cash->where("uid='$uid'")->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $k=>$v){
			$list[$k]['bankcard'] = substr($v['bankcard'], -4);
		}
		$this->assign('allinfo',$list);// 赋值数据集
		$this->assign('page',$show);//

		$this->meta_title = get_username().'的提现记录中心';
		$this->assign('userinfo', $member);
		$this->display();
	}		
	
	public function answer(){
		if(!is_login()){
	$this->error( "您还没有登陆",U("User/login") );	
		}
		$where=I('id');
		$map['id'] = $where;
		$teacher=D("consult")->where($map)->limit(1)->find();
		$this->assign('teacher',$teacher);
		$this->display();

	}
	
	public function consultdel(){
		if(!is_login()){
	$this->error( "您还没有登陆",U("User/login") );	
		}
       if(IS_POST){
             $ids = I('post.id');
            $order = M("consult");
			
            if(is_array($ids)){
                             foreach($ids as $id){
		
                             $order->where("id='$id'")->delete();
						
                }
            }
           $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $db = M("consult");
			$data['status']='0';
            $cg = $db->where("id='$id'")->save($data);
            if ($cg){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }
	//删除订单
	public function delorder(){
		if ( !is_login() ) {
		$this->error( "您还没有登陆",U("User/login") );
	}
		$order=M("order");
		$Member=D("member");
		$where['uid']=$Member->uid();
		$where['id']=I('goodid');
		$status=I('status');
		$ispay=I('ispay');
		if($status>1 and $isplay=2){
			if($order->where($where)->delete())
			$data['msg']='删除成功';
			$data['status'] = 1;
	 	 $this->ajaxreturn($data);
		}else{
			
			$data['msg']='删除失败';
			 $data['status'] = 0;
		 $this->ajaxreturn($data);
		}
	}
	
	//积分首页
	public function integral(){
		if(!is_login()){
			$this->error( "您还没有登陆",U("User/login") );	
		}
		$map1['uid'] = D("member")->uid();	
		$memberdata = M('Member')->where($map1)->find();
		$cur_credits = $memberdata['credits'];
		$this->assign('cur_credits',$cur_credits);// 当前用户积分

		
		$CreditsLog = M('CreditsLog');//积分兑换日志表		
		
		$count=$CreditsLog->where($map1)->count();
		$Page= new \Think\Page($count,20);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show();		
		$list= $CreditsLog->where($map1)->order($listsort)->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $key => $val){
			$map1['id']=$val['gid'];
			$gooddata = M('Credits')->where($map1)->find();	
			$list[$key]['title']=$gooddata['title'];
		}
		$this->assign('list',$list);// 赋值数据集
		
		$this->display();
	}
	
	//积分商城
	public function intergral_shop(){
		if(!is_login()){
			$this->error( "您还没有登陆",U("User/login") );	
		}
		
		$map1['uid'] = D("member")->uid();	
		$memberdata = M('Member')->where($map1)->find();
		$cur_credits = $memberdata['credits'];
		$this->assign('cur_credits',$cur_credits);// 当前用户积分
		
		$Credits = M('Credits');//积分商城
		
		$count=$Credits->where()->count();
		$count=$Credits->where($map)->count();
		$Page= new \Think\Page($count,20);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show();		
		$list= $Credits->where()->order($listsort)->limit($Page->firstRow.','.$Page->listRows)->select();
		//var_dump($list);
		
		$this->assign('list',$list);// 赋值数据集
		$this->display();
	}
	
	//积分兑换
	public function intergral_detail(){
		if(!is_login()){
			$this->error( "您还没有登陆",U("User/login") );	
		}
		$id=I('get.id');
		$Credits = M('Credits');//积分商城
		$map['id']=$id;
		$info = $Credits->where($map)->find();
		$this->assign('info',$info);
		$this->display();
	}
	
	//兑换表单
	public function intergral_form(){
		if(!is_login()){
			$this->error( "您还没有登陆",U("User/login") );	
		}
		$CreditsForm = M('CreditsForm');//积分商城
		if(isset($_POST['dosubmit'])){
			$map['id']=$_POST['info']['gid'];
			$gooddata = M('Credits')->where($map)->find();
			$map1['uid'] = D("member")->uid();	
			$memberdata = M('Member')->where($map1)->find();
			//判断会员积分是否足够			
			if($memberdata['credits']>=$gooddata['points']){
				$info=I("post.info");
				$info['create_time']=NOW_TIME;
				$info['uid']=D("member")->uid();;			
				if($result=$CreditsForm->add($info)){					
					//更新用户积分	
					$cdata['credits'] = $memberdata['credits'] - $gooddata['points'];
					M("Member")->where($map1)->save($cdata);		
					
					//积分兑换日志记录更新
					$CreditsLog = M('CreditsLog');
					$data['gid']=$_POST['info']['gid'];
					$data['uid']=D("member")->uid();		
					$data['create_time']=NOW_TIME;			
					$data['reduce_credits']=$gooddata['points'];
					$CreditsLog->add($data);
					
					$this->success('提交成功！',U("intergral_shop") );	
				} else {
					$error = $Credits->getError();
					$this->error(empty($error) ? '未知错误！' : $error);
				}
			}else{
				$this->error( "积分不足，无法兑换!",U("intergral_shop") );	
			}			
		}else{
			$id=I('post.id');
			$this->assign('id',$id);//兑换商品id	
		}
		$this->display();
	}
	
	
	
	//商品待评价列表
	public function valuator(){
		 if(!is_login()){
			$this->error( "您还没有登陆",U("User/login") );	
		}
		/* 购物车调用*/
		$cart=R("shopcart/usercart");
		$this->assign('usercart',$cart);
        if(!session('user_auth')){ 
			$usercart=$_SESSION['cart'];
			$this->assign('usercart',$usercart); 
	    }
		   /* 底部分类调用*/
		$menulist=R('Service/AllMenu');
		$this->assign('footermenu',$menulist);
		/* 热词调用*/
		$hotsearch=R("Index/getHotsearch");
		$this->assign('hotsearch',$hotsearch);
		/* 分类调用*/
		$menu=R('index/menulist');
		$this->assign('categoryq', $menu);
	     /* 数据分页*/
		$Member=D("member");
		$uid=$Member->uid();
		$order=M("order");
		$detail=M("shoplist");		
		$count=$order->where("uid='$uid' and status='3' and iscomment='1'")->count();
		$Page= new \Think\Page($count,5);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show(); 
		$list=$order->where("uid='$uid' and status='3' and iscomment='1'")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		foreach($list as $n=> $val){
			$list[$n]['shoplist']=$detail->where('orderid=\''.$val['id'].'\'')->select();
		}
		$this->assign('valuator',$list);// 赋值数据集 
		$this->assign('page',$show);//   
		$this->meta_title = '评价订单'; 
		$this->display();
		
	}
	
	
	//商品评价表单
	public function commentform(){
		if(!is_login()){
			$this->error( "您还没有登陆",U("User/login") );	
		}
		$id = I('post.id');//订单id
		$this->assign('id',$id);
		$uid = D("member")->uid();
		
		$order=M("order");
		$detail=M("shoplist");		
		$count=$detail->where("uid='$uid' and status='3' and orderid='$id'")->count();
		if (isset($_POST['dosubmit'])){
			$goodids=I('post.goodid');
			$contents=I('post.content');
			$tags=I('post.tag');
			$data['uid']=D("member")->uid();
			$data['status']='1';
			$data['create_time']=NOW_TIME;
			for($i=0;$i < $count;$i++){
				$data['goodid']=$goodids[$i];
				$data['content']=$contents[$i];
				$data['tag']=$tags[$i];
				$result[] = M('comment')->add($data);

			}
			if(!in_array('',$result)){//如果返回数组中无空值，则提交成功
				$this->success('提交成功',U('valuator'));	
				M('order')->where("id='$id'")->setField("iscomment","2");				
			} else {
				$this->error('数据写入错误！');
			}				
		}else{
			$list=$detail->where("uid='$uid' and status='3' and orderid='$id'")->select();
			$this->assign('list',$list);// 赋值数据集 */
		}
		
		$this->display();
		
	}
	
	public function disbutcashlist(){
		if(!is_login()){
			$this->error( "您还没有登陆",U("User/login") );	
		}
		$id = I('post.id');//订单id
		$this->assign('id',$id);
		$uid = D("member")->uid();
		
		$order=M("order");
		$detail=M("shoplist");		
		$count=$detail->where("uid='$uid' and status='3' and orderid='$id'")->count();
		if (isset($_POST['dosubmit'])){
			$goodids=I('post.goodid');
			$contents=I('post.content');
			$tags=I('post.tag');
			$data['uid']=D("member")->uid();
			$data['status']='1';
			$data['create_time']=NOW_TIME;
			$data['create_time']=NOW_TIME;//订单id
			for($i=0;$i < $count;$i++){
				$data['goodid']=$goodids[$i];
				$data['content']=$contents[$i];
				$data['tag']=$tags[$i];
				$result[] = M('comment')->add($data);

			}
			if(!in_array('',$result)){//如果返回数组中无空值，则提交成功
				$this->success('提交成功',U('valuator'));	
				M('order')->where("id='$id'")->setField("iscomment","2");				
			} else {
				$this->error('数据写入错误！');
			}				
		}else{
			$list=$detail->where("uid='$uid' and status='3' and orderid='$id'")->select();
			$this->assign('list',$list);// 赋值数据集 */
		}
		
		$goodid = I('post.goodid');
		$this->assign('goodid',$goodid);
		$uid = D("member")->uid();
		if (isset($_POST['dosubmit'])){
			$data['goodid']=I('post.goodid');
			$data['content']=I('post.content');
			$data['uid']=D("member")->uid();
			$data['status']='1';
			$data['create_time']=NOW_TIME;
			print_r($data);
			if($result = M('comment')->add($data)){
				$this->success('提交成功',U('valuator'));					
			} else {
				$this->error('数据写入错误！');
			}						
		}else{
			/*$order=M("order");
			$detail=M("shoplist");		
			//$count=$detail->where("uid='$uid' and status='3'")->count();
		
			$list=$detail->where("uid='$uid' and status='3'")->select();
			
			foreach($list as $n=> $val){
				$list[$n]['id']=$detail->where('orderid=\''.$val['id'].'\'')->select();
			}
			$this->assign('valuator',$list);// 赋值数据集 */
		}
		

		$this->display();
		
	}
	
	//商品评价页
	public function disbutcomlist(){
			 if(!is_login()){
	$this->error( "您还没有登陆",U("User/login") );	
		}
	     $this->meta_title = '评价订单'; 
		 $this->display();
		
	}
	//添加
	public function disbuttianjialist(){
		 if(!is_login()){
	$this->error( "您还没有登陆",U("User/login") );	
		}
		
		
	}
	//退页面
	public function goodsreturn(){
		 if(!is_login()){
	$this->error( "您还没有登陆",U("User/login") );	
		}
		$Member=D("member");
		$uid=$Member->uid();
		$order=M("order");
		$detail=M("shoplist");
		$count=$order->where(" uid='$uid' and status='3'")->count();
		$this->assign('anum', $count);
		$Page= new \Think\Page($count,5);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show();
		$list=$order->where("uid='$uid' and status='3'")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $n=> $val){
			
			$backdata = M('backlist')->where("tool='$val[tag]'")->find();
			$list[$n]['status2']=$backdata['status'];			
		}
		$this->assign('done',$list);// 赋值数据集
		$this->assign('page',$show);//
		$this->meta_title = '退换货';
		$this->display();
	}
	
		//换页面
	public function backreturn(){
		 if(!is_login()){
	$this->error( "您还没有登陆",U("User/login") );	
		}
		$Member=D("member");
		$uid=$Member->uid();
		$order=M("order");
		$detail=M("shoplist");
		$count=$order->where(" uid='$uid' and status='3'")->count();
		$this->assign('anum', $count);
		$Page= new \Think\Page($count,5);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show();
		$list=$order->where("uid='$uid' and status='3'")->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $n=> $val){
			
			$backdata = M('change')->where("tool='$val[tag]'")->find();
			$list[$n]['status3']=$backdata['status'];			
		}
		$this->assign('done',$list);// 赋值数据集
		$this->assign('page',$show);
		$this->display();
	}
	
	//退货页面
	public function get_goods(){
		$id=$_GET['id'];
		$value=$_GET['value'];
		$list=M('backlist');
		$row=$list->where("tool='$id'")->select();
		if($row){
		$this->success('申请过多',U('center/index'));
		}
		if($value==1){
		$rel=M('shoplist')->where($data)->setField('status',3);//更新商品表状态
		}else{
		$rel=M('shoplist')->where($data)->setField('status',4);//更新商品表状态
		}
		$this->assign('id',$id);
		$this->display();
		
	}
	//接收退货数据
	public function get_goodsreturn(){
				$Dao = M("backlist");
				$id=$_GET['tag'];
				$goodid=$data["goodid"]=M("shoplist")->where($id)->getField('goodid');
				$data['backname']= M('document')->where($goodid)->getbyId('title');
				$data['tool']=I('tag');
				$data["reason"] = I('reason');
				$data["info"] = I('info');
				$data["create_time"] =time();
				$member=D("member");
				$uid=$member->uid();
				$data['uid']=$uid;
				if($lastInsId = $Dao->add($data)){
					$this->success('提交成功',U('center/index'));
					
				} else {
					$this->error('数据写入错误！');
				}

	}
	
//接收货数据
	public function  get_changereturn(){
		$Dao = M("change");
		$id=$_GET['tag'];
		$goodid=$data["goodid"]=M("shoplist")->where($id)->getField('goodid');
		$data['backname']= M('document')->where($goodid)->getbyId('title');
		$data['tool']=I('tag');
		$data["reason"] = I('reason');
		$data["info"] = I('info');
		$data["create_time"] =time();
		$member=D("member");
		$uid=$member->uid();
		$data['uid']=$uid;
		if($lastInsId = $Dao->add($data)){
			$this->success('提交成功',U('center/index'));
			
		} else {
			$this->error('数据写入错误！');
		}

	}

		
}

