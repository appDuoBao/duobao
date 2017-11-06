<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com  All rights reserved.
// +----------------------------------------------------------------------
// | Author: ewangtx <www@ewangtx.com> 
// +----------------------------------------------------------------------
namespace Weixin\Controller;
use Common\WxpayAPI\example\JsApiPay;
/*****购物车的类
 功能：添加商品、添加/删除/查看某一个商品的数量、查看商品的总类/总数量、清空购物车、计算购物车总价格、返回购物车商品列表
***************/
class ShopcartController extends HomeController {

    /*
    添加商品
    param int $id 商品主键
          string $name 商品名称
          float $price 商品价格
          int $num 购物数量
    */
	public  function addItem($id) {
		$num=htmlspecialchars($_POST['num']);//购买数量
		$id=htmlspecialchars($_POST['id']);//产品id
		$parameters=htmlspecialchars($_POST['i']);//商品属性
		if($parameters){
			$lei = array_filter(explode(",",$parameters));
			asort($lei);

			$sort = implode(",",$lei);
			$sort = $id.",".$sort;
		}else{
			$sort = $id;
		}
		$falg = false;
		if(!isset($_SESSION['cart'])){
			$_SESSION['cart'] = array();
		}else{//判断是否已加入购物车
			foreach($_SESSION['cart'] as $k=>$val){ 
				if($val[$sort]["sort"]==$sort){//购物车中已存在该商品（同商品、属性相同、价格相同）
					$falg = true;
				}
			}
		}
      	
		//如果该商品已存在则直接加其数量
		if($falg) {
			$_SESSION['cart'][$sort]['num'] += $num;
		}else{
			$item['id'] = $id;
			$item['num'] = $num;
			$item['sort'] = $sort;
			$_SESSION['cart'][$sort] = $item;
		}
		//登录用户，处理详情页ajaxt提交的数据保存到数据库
		if(is_login()){
			$table=D("shopcart");
			$data['goodid']=$id;
			$member=D("member");
			$uid=$member->uid();
			$data['uid']=$uid;
			$data['sort']=$sort;
			$datanum=M("shopcart")->where("goodid='$id'and uid='$uid' and sort='$sort'")->getField("num");
			if($datanum){
				$data['num']=$datanum+$num;
				$table->where("goodid='$id'and uid='$uid' and sort='$sort'")->save($data);
			}else{
				$data['num']=$num;
				$table->add($data);
			}
			$data['status'] =1;
			$data['num'] = M("shopcart")->where("goodid='$id'and uid='$uid' and sort='$sort'")->getField("num");
			$data['msg'] = '已添加到购物车';
			$data['sum']=D("shopcart")->getNumByuid();
			$data['fee']=$table->getPriceByuid(); /* 购物车中商品的总金额*/
			$this->ajaxReturn($data);
		}else{    
			$data['fee']=$this->getPrice(); /* 购物车中商品的总金额*/
			$data['status'] = 1;
			$itemid= $this->getItem($sort);
			$data['num']=$itemid['num'];
			$data['sum'] =$this->getNum();
			$data['msg'] = '已添加到购物车';
			$this->ajaxReturn($data);
		}
	}
	
     /*
    添加商品  添加商品
    param int $id 商品主键
       
          int $num 购物数量
    */
    public  function addgood($id) {
            $tag=htmlspecialchars($_POST['tag']);
			$num=1;
            $id=htmlspecialchars($_POST['id']);
            $price=get_good_price($id); 
	  
	   if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = array();
        }
	    $item = array();
	    //如果该商品已存在则直接加其数量
        if (isset($_SESSION['cart'][$id])) {
             $_SESSION['cart'][$id]['num'] += $num;
         
         $item['id'] = $id;
        $item['price'] = $price;
        $item['num'] = $_SESSION['cart'][$id]['num'];
        $_SESSION['cart'][$id] = $item;
		$exsit="1";
		
		}
		else{
			 $item['id'] = $id;
        $item['price'] = $price;
        $item['num'] = $num;
        $_SESSION['cart'][$id] = $item;
		$exsit="0";
			
			}
 $data['status'] = 1;
$data['price']=get_good_price($id);
$coverid=get_cover_id($id);
$data['src']=get_good_img($coverid);
$data['title']=get_good_name($id);

//登录用户，处理详情页ajaxt提交的数据保存到数据库
if(is_login()){ 
 $table=D("shopcart");
 $data['goodid']=$id;
 $data['num']=$num;
$member=D("member");
$uid=$member->uid();
 $data['uid']=$uid;
$pnum=M("shopcart")->where("goodid='$id'and uid='$uid'")->getField("num");
if($pnum)
	 {$exsit="1";
	$data['num']=$pnum+$num;
$table->where("goodid='$id'and uid='$uid'")->save($data);
}
else{
$data['num']=$num;
$table->add($data);
$exsit="0";
}
$data['sql'] ='sql';
	
	$data['num'] = M("shopcart")->where("goodid='$id'and uid='$uid'")->getField("num");
       $data['msg'] = '添加成功';
	     $data['exsit'] = $exsit;
      $this->ajaxReturn($data);
  }
 //非登陆用户
else{	 $data['exsit'] = $exsit;
		
		 $data['num'] = $item['num'];
       $data['msg'] = '添加成功';
      $this->ajaxReturn($data);
	  }
		  
			
    }
    /*
    修改购物车中的商品数量
    int $id 商品主键
    int $num 某商品修改后的数量，即直接把某商品
    的数量改为$num
    */
    public function modNum($id,$num=1) {
        if (!isset($_SESSION['cart'][$id])) {
            return false;
        }
	    $_SESSION['cart'][$id]['num'] = $num;
    }
 
    /*
    商品数量+1
    */
    public function incNum($sort,$num=1) {
        if (isset($_SESSION['cart'][$sort])) {
            $_SESSION['cart'][$sort]['num'] += $num;
			   
        }
		 $count=$this->getCnt(); /*查询购物车中商品的种类 */
     $sum= $this->getNum();/* 查询购物车中商品的个数*/
     $price=$this->getPrice(); /* 购物车中商品的总金额*/
	  $data['count'] =$count;
	 	 $data['price'] =$price;
		 $data['sum'] =  $sum;
       $data['status'] = 1;
       $this->ajaxReturn($data);
		
		
    }
 
    /*
    商品数量-1
    */
    public function decNum($sort,$num=1) { 
		
        if (isset($_SESSION['cart'][$sort])) {
            $_SESSION['cart'][$sort]['num'] -= $num;
        }
 
        //如果减少后，数量为0，则把这个商品删掉
        if ($_SESSION['cart'][$sort]['num'] <1) {
            unset($_SESSION['cart'][$sort]);
        }
		 $count=$this->getCnt(); /*查询购物车中商品的种类 */
     $sum= $this->getNum();/* 查询购物车中商品的个数*/
     $price=$this->getPrice(); /* 购物车中商品的总金额*/
	  $data['count'] =$count;
	 	 $data['price'] =$price;
		 $data['sum'] =  $sum;
       $data['status'] = 1;
       $this->ajaxReturn($data);
   
   
    }
 
    /*
    订单明细
    */
    public function detail() {
        $count=$this->getCnt(); /*查询购物车中商品的种类 */
        $sum= $this->getNum();/* 查询购物车中商品的个数*/
        $money=$this->getPrice(); /* 购物车中商品的总金额*/	 
        $this->assign('sum', $sum);
		$this->assign('money',  $money);
        $this->assign('list',$_SESSION['cart']); 
		
		$this->display();
    }
 public function index() {
		$document = M('Document');
		$goodsdata = M('GoodsData');
		/*查询购物车*/
		$count=count($_SESSION['cart']); 
		if(is_login()) { 
			
			$cart=D("shopcart");
			$usercart= $cart->getcart();
			$count=$cart->getCntByuid(); /*查询购物车中商品的种类 */
			$sum= $cart->getNumByuid();/* 查询购物车中商品的个数*/
			$price=$cart->getPriceByuid(); /* 购物车中商品的总金额*/
			$member=D("member");
			$uid=$member->uid();
		 }else{
			$uid="";
			$count=$this->getCnt(); /*查询购物车中商品的种类 */
			$sum= $this->getNum();/* 查询购物车中商品的个数*/
			$price=$this->getPrice(); /* 购物车中商品的总金额*/
			$usercart=$_SESSION['cart'];

			if($usercart){
				$buyaode = array();
				foreach($usercart as $k=>$v){
					$usercart[$k]['goodid'] = $v['id'];
					$par = explode(',',$v['sort']);
					$parprice = 0;

					if(count($par)>1){
						$goodsid = $par[0];
						unset($par[0]);
						$skuarr = M('GoodsData')->field('sku')->where(array("goods_id"=>$goodsid))->select();
						$skustr = '';
						foreach($skuarr as $kk=>$vv){
							if($skustr){
								$skustr .= ",'".$vv['sku']."'";
							}else{
								$skustr = "'".$vv['sku']."'";
							}
						}
						$sql = "SELECT sku FROM `ewshop_goods_sku` WHERE cid IN (".$v['sort'].") AND sku IN (".$skustr.") GROUP BY sku HAVING COUNT(sku)=".count($par);
						$proinfo = M()->query($sql);
						if($proinfo){

							$parprice = M('GoodsData')->getFieldBySku($proinfo[0]['sku'],'total');
							$ii = 0;
							foreach($par as $k1=>$v1){
								$cartlist[$k]['parameters'][$ii]['extend_name'] = M('GoodsExtendClass')->getFieldById($v1,'name');
								$ii++;
							}
						}else{
							M('Shopcart')->where(array('sort'=>$v['sort']))->delete();
							$buyaode[] = $k;
						}
					}else{
						$usercart[$k]['parameters'] = '';
					} 
					$usercart[$k]['price'] =  $document->getFieldById($v['id'],'price')+$parprice;
				}
			}	
		}

		$this->assign('usercart',$usercart);
		$this->assign('uid',$uid);
		$address=get_address($uid);
		$this->assign('address',$address);
		$this->assign('count',$count);
		$this->assign('sum', $sum);
		$this->assign('price',$price); $this->meta_title = '我的购物车';
		$this->display();

    }

	public function delItem() {
		$sort=htmlspecialchars($_POST['sort']);
		unset($_SESSION['cart'][$sort]);
		$count=$this->getCnt(); /*查询购物车中商品的种类 */
		$sum= $this->getNum();/* 查询购物车中商品的个数*/
		$price=$this->getPrice(); /* 购物车中商品的总金额*/
		$data['count'] =$count;
		$data['price'] =$price;
		$Item=$this->getItem($sort);
		$data['num'] =$_SESSION['cart'][$sort]["num"];
		$data['sum'] =  $sum;
		$data['status'] = 1;
		$this->ajaxReturn($data);
	}


	//批量删除购物车商品(未登录状态)
	public function delsItem() {
		$ids=htmlspecialchars($_POST['ids']);//产品id
		$idsarr = explode(",",$ids);
		foreach ($idsarr as $sort) {
			unset($_SESSION['cart'][$sort]);
		}

		$count=$this->getCnt(); /*查询购物车中商品的种类 */
		$sum= $this->getNum();/* 查询购物车中商品的个数*/
		$price=$this->getPrice(); /* 购物车中商品的总金额*/
		$data['count'] =$count;
		$data['price'] =$price;
		$Item=$this->getItem($sort);
		$data['num'] =$_SESSION['cart'][$sort]["num"];
		$data['sum'] =  $sum;
		$data['status'] = 1;
		$this->ajaxReturn($data);
	}

    /*
    获取单个商品
    */
    public function getItem($sort) {
        return $_SESSION['cart'][$sort];
    }
 
    /*
    查询购物车中商品的种类
    */
    public function getCnt() {
        return count($_SESSION['cart']);
    }
     
    /*
    查询购物车中商品的个数
    */
		/*
		查询购物车中商品的个数
		*/
		public function getNum(){
		if ($this->getCnt() == 0) {
		//种数为0，个数也为0
		return 0;
		}
		$sum = 0;
		$data = $_SESSION['cart'];
		foreach ($data as $item) {		
		$sum += $item['num'];
		}
		return $sum;
		}
 
    /*
    购物车中商品的总金额
    */
    public function getPrice() {
        //数量为0，价钱为0
	
		$document = M('Document');
		$goodsdata = M('GoodsData');
        if ($this->getCnt() == 0) {
            return 0;
        }
        $total = 0.00;
		$parprice = 0;
        $data = $_SESSION['cart'];
		
        foreach ($data as $item) {
			$par = explode(',',$item['sort']);
			if(count($par)>1){
				$id = $par[0];
				unset($par[0]);
				$skuarr = M('GoodsData')->field('sku')->where(array("goods_id"=>$id))->select();
				$skustr = '';
				foreach($skuarr as $kk=>$vv){
					if($skustr){
						$skustr .= ",'".$vv['sku']."'";
					}else{
						$skustr = "'".$vv['sku']."'";
					}
				}
				$sql = "SELECT sku FROM `ewshop_goods_sku` WHERE cid IN (".$item['sort'].") AND sku IN (".$skustr.") GROUP BY sku HAVING COUNT(sku)=".count($par);
				$proinfo = M()->query($sql);
				if($proinfo){

					$parprice = M('GoodsData')->getFieldBySku($proinfo[0]['sku'],'total');

				}else{
					$parprice = 0;
				}
			}  
			$price = $document->getFieldById($item['id'],'price')+$parprice;
            $total += $item['num'] * $price;
        }
        return sprintf("%01.2f", $total);
    }
 
    /*
    清空购物车
    */
   public function clear() {
        $_SESSION['cart'] = array();
    }



	public function order() {
		if(!is_login()){
			$this->error( "您还没有登陆",U("User/login") );
		}


		/* uid调用*/
		$uid=D('member')->uid();

		$map['uid'] = $uid;
		$score=get_score($uid);//获取用户积分
		/* 地址调用*/
		$Transport = M("transport"); // 实例化transport对象
		$addresslist = $Transport->where($map)->select();
		foreach($addresslist as $key => $val){
			$addresslist[$key]['name'] = $this->getAllareaname($addresslist[$key]['area']);
			if($addresslist[$key]['status']){
				$nowadr = $addresslist[$key];
			}
		}
		if(!$nowadr && $addresslist){
			$nowadr = $addresslist[0];
		}

		$this->assign('nowadr',$nowadr);
		$this->assign('addresslist',$addresslist);
		$this->assign('addnum',count($addresslist));
		/* 积分兑换*/
		$ratio= $score/C('RATIO');
		$this->assign('ratio', $ratio);
		$this->assign('uid', $uid);
		/* 创建订单*/
		if(IS_POST){
			$goodlist=M("shoplist");
			$order=M("order");

			$comparea = M('area');
			$map['pid'] = 0;
			$arealist = $comparea->where($map)->select();
			$this->assign('arealist',$arealist);//地区列表

			$allokorder = $order->where("uid='$uid'")->field('tag')->select();//已成功的订单
			$okordertag=array();
			foreach($allokorder as $k=>$v){
				$okordertag[$k]=$v['tag'];
			}
			$okordertag = array_flip(array_flip($okordertag));//删除重复的值
			//$okordertag = array_unique($okordertag);//删除重复的值 标准写法 但是效率没有上面那个高 2个选择一个

			$allordertag = $goodlist->where("uid='$uid'")->field('tag')->select();
			$rubbishtag = array();
			foreach($allordertag as $k=>$v){
				if(in_array($v['tag'],$okordertag)){

				}else{
					$rubbishtag[$k]=$v['tag'];
				}
			}

			$rubbishtag = array_flip(array_flip($rubbishtag));//删除重复的值 (高效)
			//$rubbishtag = array_unique($rubbishtag);//删除重复的值 标准写法 但是效率没有上面那个高 2个选择一个即可
			$rubbishtag= implode(',',$rubbishtag);//取得所有的此会员 订单产品关联的订单号

			$map['tag']=array('in',$rubbishtag);
			$goodlist->where($map)->delete();

			$tag=$this->ordersn(); //标识号
			$document = D('Document');
			$prolist = array();
			$parprice = 0;
			for($i=0;$i<count($_POST["id"]);$i++){
				$id = htmlspecialchars($_POST ["id"] [$i]);

				$flag = strpos($id,",");
				if($flag){
					$idperarr = explode(',',$id);
					$id = $idperarr[0];
					$skuarr = M('GoodsData')->field('sku')->where(array("goods_id"=>$id))->select();
					$skustr = '';
					foreach($skuarr as $k=>$v){
						if($skustr){
							$skustr .= ",'".$v['sku']."'";
						}else{
							$skustr = "'".$v['sku']."'";
						}
					}

					array_shift($idperarr);
					asort($idperarr);


					$sort = implode(",",$idperarr);

					$sql = "SELECT sku FROM `ewshop_goods_sku` WHERE cid IN (".$sort.") AND sku IN (".$skustr.") GROUP BY sku HAVING COUNT(sku)=".count($idperarr);
					$proinfo = M()->query($sql);
					if($proinfo){

						$parprice = M('GoodsData')->getFieldBySku($proinfo[0]['sku'],'total');
						$ii = 0;
						foreach($idperarr as $k=>$v){
							$prolist[$i]['shuxing'][$ii]['title'] = M('GoodsExtendClass')->getFieldById($v,'name');
							$ii++;
						}
					}else{
						$this->error( "选择产品属性有误",U("/Weixin/Goods/detail/id/".$id) );
					}

					$sort = $id.','.$sort;

				}else{
					$ishave = M('GoodsData')->where(array('goods_id'=>$id))->select();
					if($ishave){
						$this->error( "选择产品属性有误",U("/Weixin/Goods/detail/id/".$id) );
					}else{
						$sort = $id;
					}

				}
				if(!$id){
					$this->error( "选择产品有误",U("Index/index") );
					exit;
				}
				$goodprice=$document->getFieldById($id,'price')+$parprice;
				$num = htmlspecialchars($_POST ["num"] [$i]);
				$goodtotal=$num*$goodprice;
				$goodname = $document->getFieldById($id,'title');
				$prolist[$i]['goodname'] = $goodname;
				$prolist[$i]['goodid'] = $id;
				$prolist[$i]['price'] = $goodprice;
				$prolist[$i]['total'] = $goodtotal;
				$prolist[$i]['num'] = $num;
				$goodlist->goodid = $id;
				$goodlist->goodname = $goodname;
				$goodlist->status = 1;
				$goodlist->orderid ='';
				$goodlist->sort = $sort;
				$goodlist->num = $num;
				$goodlist->uid=$uid;
				$goodlist->tag=$tag;//标识号必须相同
				$goodlist->create_time= NOW_TIME;

				$goodlist->price =$goodprice;

				$goodlist->total =$goodtotal;
				$goodlist->add();
			}

			$this->assign('prolist',$prolist);

			$defaultaddress=get_addressnum($uid);
			if($defaultaddress==0){
				$defaultaddress=='';
			}

			$this->assign('address',$defaultaddress);
			$a= M("shoplist")->where(" tag='$tag'")->select();
			$total='';$num='';
			foreach ($a as $k=>$val) {
				$total += $val['total'] ;
				$num+=$val['num'];
			}
			


			
			$nowtotal = $total;
			/*获取满减促销信息 */
			$mjprice = 0;
			$promotion_mj = M('promotionMj');//实例化满减促销
			$pmjtypes  = $promotion_mj->where('1=1')->order('mj_price desc,id desc')->select();//商品满减促销
			if($pmjtypes){
				foreach($pmjtypes as $v){
					$ids = explode(",",$v['ids']);//参与满减的商品id
					if($v['type']==1){//全部商品
						if($nowtotal>=$v['top_price']){
							$nowtotal = $nowtotal - $v['mj_price'];
							$mjprice = $v['mj_price'];
							break;//满减仅参与一次，按照符合条件的最大金额减扣
						}
					}elseif($v['type']==2){//指定分类
						$producttotal=0;
						foreach ($a as $va){
							$cid = M('Document')->getFieldById($va['goodid'],'category_id');
							if(in_array($cid,$ids)){
								$producttotal += $va['total'] ;
							}
						}
						if($producttotal>=$v['top_price']){
							$nowtotal = $nowtotal - $v['mj_price'];
							$mjprice = $v['mj_price'];
							break;//满减仅参与一次，按照符合条件的最大金额减扣
						}
					}elseif($v['type']==3){//指定商品
						$producttotal=0;
						foreach ($a as $va) {
							if(in_array($va['goodid'],$ids)){
								$producttotal += $va['total'] ;
							}
						}
						if($producttotal>=$v['top_price']){
							$nowtotal = $nowtotal - $v['mj_price'];
							$mjprice = $v['mj_price'];
							break;//满减仅参与一次，按照符合条件的最大金额减扣
						}
					}
				}
			}
			if($nowtotal<C('LOWWEST')){
				$trans=C('SHIPMONEY');
			}else{
				$trans=0.00;
			}
			
			$all=$nowtotal+$trans;
			//当前会员优惠券信息
			$table=M("promotionYhjdata");
			$prefix = C('DB_PREFIX');
			$py = $prefix."promotion_yhj";
			$pyd = $prefix."promotion_yhjdata";

			$curtime = time();
			$yhqlist=$table->join("$py as y ON $pyd.yhj_id = y.id")->where("$pyd.user_id='$uid' and $pyd.used_time=0 and UNIX_TIMESTAMP(y.start_time) <".$curtime." and UNIX_TIMESTAMP(y.end_time) >".$curtime." and y.min_price <=".$all)->order("$pyd.id desc")->field("$pyd.*,y.name,y.price,y.min_price,y.start_time,y.end_time")->select();
			
			$this->assign('yhqlist',$yhqlist);			
			
			
		/*	
			$yhqje = 0;
			if($yhqlist){
				$yhqje = get_yhq_fee($yhqlist[0]['sn'],$total);
				$all -= $yhqje;
			}
			$this->assign('yhqje', $yhqje);//默认选择第一条优惠券的价格
		*/
			$this->assign('mjprice', $mjprice);//满减促销

			$this->assign('all', $all);
		
			$this->assign('num',$num);
			$this->assign('tag',$tag);
			$this->assign('total',$total);
			$this->assign('trans',$trans);
			$this->meta_title = '订单结算';
			$this->display();

		}
	}

	
public function createorder() { 
	if(!is_login()){
		$this->error( "您还没有登陆",U("User/login") );	
	}

	$orderid = $_POST['tag'];
	$check = $this->checkgoods($orderid);
	if(!$check){
		$this->error( "订单产品有误",U("Weixin/index") );die;
	}
	/* 购物车调用*/
	$cart=R("shopcart/usercart");
	$this->assign('usercart',$cart);
	if(!session('user_auth')){ 
	$usercart=$_SESSION['cart'];
	$this->assign('usercart',$usercart); 
	}
	
	$order=D("order");
	$tag=htmlspecialchars($_POST["tag"]);
	$value=$order->where("tag='$tag'")->getField('id');
	isset($value)&& $this->error('重复提交订单');

	//获取会员uid
	$uid=D("member")->uid();
	//根据订单id获取购物清单
	$del=M("shoplist")->where("tag='$tag'")->select();
	
	//遍历购物清单，删除登录用户购物车中的货物id
	foreach($del as $k=>$val){
		//获取购物清单数据表产品id，字段goodid
		$delbyid=$val["sort"];
		//删除购物车中用户的产品id
		M("shopcart")->where("sort='$delbyid'and uid='$uid'")->delete();
	}
	
	//计算提交的订单的商品总额
	$total=$this->getPricetotal($tag);

	
	//计算提交的订单的商品总数量
	$goodsnumtotal=$this->getgoodsnumall($tag);	
	
	//计算提交的订单的商品运费
	if($total<C('LOWWEST')){
		$trans=C('SHIPMONEY');	 
	}else{
		$trans=0;
	}
	//计算提交的积分兑换
	if(htmlspecialchars($_POST["score"])){
		$score=htmlspecialchars($_POST["score"]);
		//读取配置，1000积分兑换1元
		$ratio= $score/C('RATIO');
		$data['score']=$score;
		$user=session('user_auth');
		$uid=D("member")->uid();
		M("member")->where("uid='$uid'")->setDec('score',$score);
	}else{
		$ratio=0;
	}
	$pricetotal = $total;
/*获取满减促销信息 */
			$a= M("shoplist")->where(" tag='$tag'")->select();
			$mjprice = 0;
			$promotion_mj = M('promotionMj');//实例化满减促销
			$pmjtypes  = $promotion_mj->where('1=1')->order('mj_price desc,id desc')->select();//商品满减促销
			if($pmjtypes){
				foreach($pmjtypes as $v){
					$ids = explode(",",$v['ids']);//参与满减的商品id
					if($v['type']==1){//全部商品
						if($pricetotal>=$v['top_price']){
							$pricetotal = $pricetotal - $v['mj_price'];
							$mjprice = $v['mj_price'];
							break;//满减仅参与一次，按照符合条件的最大金额减扣
						}
					}elseif($v['type']==2){//指定分类
						$producttotal=0;
						foreach ($a as $va){
							$cid = M('Document')->getFieldById($va['goodid'],'category_id');
							if(in_array($cid,$ids)){
								$producttotal += $va['total'] ;
							}
						}
						if($producttotal>=$v['top_price']){
							$pricetotal = $pricetotal - $v['mj_price'];
							$mjprice = $v['mj_price'];
							break;//满减仅参与一次，按照符合条件的最大金额减扣
						}
					}elseif($v['type']==3){//指定商品
						$producttotal=0;
						foreach ($a as $va) {
							if(in_array($va['goodid'],$ids)){
								$producttotal += $va['total'] ;
							}
						}
						if($producttotal>=$v['top_price']){
							$pricetotal = $pricetotal - $v['mj_price'];
							$mjprice = $v['mj_price'];
							break;//满减仅参与一次，按照符合条件的最大金额减扣
						}
					}
				}
			}
	
	//计算提交的订单的商品运费
	if($pricetotal<C('LOWWEST')){
		$trans=C('SHIPMONEY');	 
	}else{
		$trans=0;
	}
	//计算优惠券可使用的金额,home/common/function
//计算提交的优惠券
	$code=htmlspecialchars($_POST["yhqid"]);	
	//计算优惠券可使用的金额,home/common/function
	$decfee=get_yhq_fee($code,$pricetotal);
	$data['codeid']=$code;
	$data['codemoney']=$decfee;
	
		//设置优惠券使用状态
	$pyhjmap['used_time'] = time();
	$pyhjmap['user_id'] = $uid;
	$pyhjmap['order_id'] = $tag;
	M("PromotionYhjdata")->where("sn = '$code'")->setField($pyhjmap);
	
	$senderid=htmlspecialchars($_POST["sender"]);
	
	$adrinfo = M("transport")->find($senderid);
	$chengshi = $this->chengshi($adrinfo['area']);
	$data['message']=$_POST['message'];
	$data['address']=$chengshi.$adrinfo['address'];
	$data['realname']=$adrinfo['realname'];
	$data['phone']=$adrinfo['cellphone'];
	$data['youbian']=$adrinfo['youbian'];
	//发票信息
	//$data['invoice']=htmlspecialchars($_POST["invoice"]);
	$data['taitou_type']=htmlspecialchars($_POST["taitou_type"]);
	if($_POST["taitou_type"]=="个人"){
		$data['invoice']=htmlspecialchars($_POST["taitou1_content"]);//个人抬头
	}else{
		$data['invoice']=htmlspecialchars($_POST["taitou2_content"]);//公司抬头
	}
	$data['fapiao_content']=htmlspecialchars($_POST["fapiao_content"]);

	$data['addressid']=$senderid;
	$data['total']=$total;
	$data['disbutmoney']=$disbutmoneytotal;//订单的商品分销总额
	$data['goodsnum']=$goodsnumtotal;
	
	$data['create_time']=NOW_TIME;
	$data['shipprice']=$trans;
	//计算提交的订单的总费用
	$all=$pricetotal+$trans-$ratio-$decfee;
	$data['pricetotal']=$all;
	$data['mjprice']=$mjprice;
	$data['orderid']=$tag;
	$data['tag']=$tag;
	$data['uid']=$uid;
	//修改订单状态为用户已提交
	
	if(htmlspecialchars($_POST["PayType"])=="1"){
		$pay=M("pay");
		$pay->create();       
		$pay->money=$all;
		$pay->ratio=$ratio;
		$pay->total=$total;
		$pay->out_trade_no=$tag;
		$pay->yunfee=$trans;
		$pay->coupon=$decfee;
		$pay->uid=$uid;
		$pay->ratioscore=$score;
		$pay->couponcode=$code;
		$pay->addressid=$senderid;
		$pay->create_time=NOW_TIME;
		$pay->type=2;//货到付款
		$pay->status=1;
		$pay->add();
		$data['status']=1;	
		$data['ispay']=-1;//货到付款
		$data['backinfo']="已提交等待发货";
		//增加取消订单
		//根据订单id保存对应的费用数据
		$orderid=$order->add($data);
		M("shoplist")->where("tag='$tag'")->setField('orderid',$orderid);
		$this->assign('codeid',$tag);
		$mail=get_email($uid);//获取会员邮箱
		$title="交易提醒";
		$content="您在<a href=\"".C('DAMAIN')."\" target='_blank'>".C('SITENAME').'</a>提交了订单，订单号'.$tag;
		if( C('MAIL_PASSWORD')) {
			SendMail($mail,$title,$content);
		}        			
		$this->meta_title = '提交成功';
		$this->display('success');
	}
	if(htmlspecialchars($_POST["PayType"])=="2")	{
		//设置订单状态为用户为未能完成，不删除数据
		$data['backinfo']="等待支付";
		$data['ispay']="1";
		$data['status']="-1";//待支付
		//根据订单id保存对应的费用数据
		
		$orderid=$order->add($data);
		M("shoplist")->where("tag='$tag'")->setField('orderid',$orderid);
		$pay=M("pay");
		$pay->create();       
		$pay->money=$all;
		$pay->ratio=$ratio;
		$pay->total=$total;
		$pay->out_trade_no=$tag;
		$pay->yunfee=$trans;
		$pay->coupon=$decfee;
		$pay->uid=$uid;
		$pay->ratioscore=$score;
		$pay->couponcode=$code;
		$pay->addressid=$senderid;
		$pay->create_time=NOW_TIME;
		$pay->type=1;//在线支付
		$pay->status=1;//待支付
		$pay->add();
		$this->meta_title = '订单支付';
		
		$this->assign('codeid',$tag);
		$this->assign('goodprice',$all);
		
		//跳转至微信支付页面
		//header('Location:'.U('/Weixin/Wxpay/pay?orderid='.$tag),false);//不能使用链接参数，微信会识别链接不是安全链接（和微信配置中域名不对应）
		$_SESSION['orderinfo']['orderid'] = $tag;
		header('Location:'.U('/Weixin/Wxpay/pay'),false);
		//$this->display('Pay/index');
	}

}

	public function checkgoods($orderid){
		$oid = M("shoplist")->where(array("tag"=>$orderid))->field("sort")->select();
		$tong = 0;
		foreach($oid as $v){
			$good = explode(",",$v["sort"]);
			if(count($good) > 1){
				$sku = M("goods_data")->where(array("goods_id"=>$good[0]))->select();
				foreach($sku as $vv){
					$count = 0;
					foreach($good as $kk=>$vvv){
						if($kk == 0){
							continue;
						}
						$sql = "select * from ewshop_goods_sku where sku = '{$vv["sku"]}' and cid = {$vvv}";
						$re = M()->query($sql);
						if($re){
							$count++;
						}
					}
					$all = M()->query("select * from ewshop_goods_sku where sku = '{$vv["sku"]}'");
					if($count == count($all)){
						if($count == (count($good)-1)){
							$tong++;
						}
					}
				}
			}else{
				return 1;
			}
		}
		if($tong == count($oid)){
			return 1;
		}else{
			return 0;
		}
	}


public function buynow() {
	$user=session('user_auth');
	$uid=$user["uid"];
	
	$buy=D("order");
	$buy->create();
	$buy->uid=$uid;
	$buy->goodclass='1';
	$buy->add();
	$this->display('success');
}
function ordersn(){
    $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    $orderSn = $yCode[((intval(date('Y')) - 2015)+26)%26] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%04d%02d', rand(1000, 9999),rand(0,99));
    return $orderSn;
}


	//保存收货地址 -- 订单选择支付方式
	public function savemsg() {
		$member = D("member"); // 实例化User对象
		$uid=$member->uid();
		$Transport = M("transport"); // 实例化transport对象
		
		$id=htmlspecialchars($_POST["orderid"]);	
		
		$data['area'] = htmlspecialchars($_POST["areaid"]);
		$data['address'] = htmlspecialchars($_POST["address"]);
		$data['cellphone'] = htmlspecialchars($_POST["phone"]);
		$data['realname'] =htmlspecialchars($_POST["realname"]);
		$data['uid'] = $uid;
		$data['create_time']=NOW_TIME;
		$data['orderid'] = $id;			
		if(htmlspecialchars($_POST["msg"])=="yes"){//设为默认
			//默认地址更新会员
			if($Transport->where("uid='$uid' and status='1'")->getField("id"))
			{  
				$odata['status'] = 0;
				$Transport->where("uid='$uid'")->save($odata);      
			}
			//地址库有默认地址，有则保存
			$data['status'] = 1;
		}else{
			$data['status'] = 0;
		}
		$addressid = htmlspecialchars($_POST["addressid"]);//判断新增或者编辑	
		if($addressid!=''){
		   $Transport->where("id='$addressid'")->save($data);  
		   $data['addressid']=$addressid;
		   $data['msg'] = 'yes'; 
		}else{
			$data['addressid'] = $Transport->add($data); 
			$data['msg'] = 'no';
		}
		$data['adrname'] = $this->getAllareaname($data['area']);	
		$this->ajaxReturn($data);
	}

	//保存收货地址 -- 会员中心 返回地址列表使用
	public function saveaddress() {
		$member = D("member"); // 实例化User对象
		$uid=$member->uid();
		$Transport = M("transport"); // 实例化transport对象
		
		$data['area'] = htmlspecialchars($_POST["areaid"]);
		$data['address'] = htmlspecialchars($_POST["address"]);
		$data['cellphone'] = htmlspecialchars($_POST["phone"]);
		$data['realname'] =htmlspecialchars($_POST["realname"]);
		$data['uid'] = $uid;
		$data['create_time']=NOW_TIME;
		
		if(htmlspecialchars($_POST["msg"])=="yes"){//设为默认
			//默认地址更新会员
			if($Transport->where("uid='$uid' and status='1'")->getField("id"))
			{  
				$odata['status'] = 0;
				$Transport->where("uid='$uid'")->save($odata);      
			}
			//地址库有默认地址，有则保存
			$data['status'] = 1;
		}else{
			$data['status'] = 0;
		}
		
		$addressid = htmlspecialchars($_POST["addressid"]);//判断新增或者编辑	
		if($addressid!=''){
		    $Transport->where("id='$addressid'")->save($data);  
		}else{
			$Transport->add($data); 
		}
		
		$map['uid']=$uid;
		//$map['id']=$addressid;
		
		$addresslist = $Transport->where($map)->select();
		foreach($addresslist as $k=>$v){
			$addresslist[$k]['address'] = $this->getAllareaname($v['area']).$v['address'];
		}
		
		$this->ajaxReturn($addresslist);		
	}
		
	
	//根据areaid获取完整城市信息
	public function getAllareaname($areaid) {
		//城市地区
		$area = M('Area');
		$map['id'] = $areaid;
		$areainfo = $area->where($map)->find();
		if($areainfo['pid'] && $areainfo['pid']!=0){
			$arealists .= $this->getAllareaname($areainfo['pid']).' '.$areainfo['name'];
		}else{
			if($areaid<=5 || $areaid == 33 || $areaid == 34 || $areaid == 35 || $areaid == 3358){
				$arealists = $areainfo['name'].' '.$areainfo['name'];
			}else{
				$arealists = $areainfo['name'];
			}
			
		}			
		return $arealists;
	}		

		
	//根据id获取收货地址
	public function getaddress() {
		$addressid=htmlspecialchars($_POST["addressid"]);
		$address = M("transport")->where("id='".$addressid."'")->find();
		$addid = $address['area'];
		if($address['area']){
			$areas = $this->getAllarea($address['area']);
			if($areas){
			   $area_arr=explode(',',$areas); 
			}
			$address['areas'] = $area_arr;
		}
		$address['name'] = $this->getAllareaname($addid);
		$address['addid'] = $addid;
		$this->ajaxReturn($address);
	}
	
	//根据areaid获取完整城市信息
	public function getAllarea($areaid) {
		//城市地区
		$area = M('Area');
		$map['id'] = $areaid;
		$areainfo = $area->where($map)->find();
		if($areainfo['pid'] && $areainfo['pid']!=0){
			$arealists .= $this->getAllarea($areainfo['pid']).','.$areaid;
		}else{
			$arealists = $areaid;
		}			
		return $arealists;
	}
		
	//获取收货地址列表
	public function getaddresslist() {
		$member = D("member"); // 实例化User对象
		$map['uid']=$member->uid();
		$addresslist = M("transport")->where($map)->select();
		$this->ajaxReturn($addresslist);
	}
	
	public function delorder() 
		{
		if(is_login())
			{		
		$map["tag"]=array("in",$tag);
		$map["uid"]=D("member")->uid();
		$map["status"]=array("gt",2);
		M("order")->where($map)->delete();		
		$data=M("shoplist")->where($map)->delete();	
		if($data) 
			{ $this->success('删除成功！');
		}
		else{
		$this->error('删除失败！订单未完成');
		}
	}
}
public function usercart(){
		
		$cart=D("shopcart");
		$result= $cart->getcart();
 return $result;
}
public function incNumByuid(){
		if(!is_login()){
	$this->error( "您还没有登陆",U("User/login") );	
		}
		$sort=htmlspecialchars($_POST['sort']);
		$cart=D("Shopcart");
        $result= $cart->inc($sort);
       $count=$cart->getCntByuid(); /*查询购物车中商品的种类 */
        $sum= $cart->getNumByuid();/* 查询购物车中商品的个数*/
        $price=$cart->getPriceByuid(); /* 购物车中商品的总金额*/

		if($result){
			$data['new'] ='新个数'.$result;
			$data['count'] = $count;
			$data['status'] = 1;
			$data['price'] =$price;
		 $data['sum'] = $sum;
        $data['msg'] = '处理成功';
		 $this->ajaxReturn($data);
		}
 
}
public function decNumByuid(){
		if(!is_login()){
		$this->error( "您还没有登陆",U("User/login") );	
		}
		$cart=D("shopcart");
		$sort=htmlspecialchars($_POST['sort']);
		$result= $cart->dec($sort);
		$count=$cart->getCntByuid(); /*查询购物车中商品的种类 */
        $sum= $cart->getNumByuid();/* 查询购物车中商品的个数*/
        $price=$cart->getPriceByuid(); /* 购物车中商品的总金额*/
    
		if($result){$data['new'] ='新个数'.$result;
			$data['count'] = $count;
			$data['status'] = 1;
			$data['price'] =$price;
		 $data['sum'] = $sum;
       $data['msg'] = '处理成功';
		 $this->ajaxReturn($data);
		}

}
//删除单个购物车商品（登录状态）
	public function delItemByuid(){
		if(!is_login()){
			$this->error( "您还没有登陆",U("User/login") );
		}
		$cart=D("shopcart");
		$sort=htmlspecialchars($_POST['sort']);
		$user=D("member");
		$uid=$user->uid();
		if($result= $cart->where("sort='$sort'and uid='$uid'")->delete()){
			$count=$cart->getCntByuid(); /*查询购物车中商品的种类 */
			$sum= $cart->getNumByuid();/* 查询购物车中商品的个数*/
			$price=$cart->getPriceByuid(); /* 购物车中商品的总金额*/
			$data['status'] = 1;
			$data['goodid'] =$id;
			$data['price'] =$price;
			$data['count'] = $count;
			$data['num'] =  $sum;
			$data['sum'] =  $sum;
			$data['msg'] = '处理成功';
			$this->ajaxReturn($data);
		}
	}

	//批量删除购物车商品(登录状态)
	public function delsItemByuid(){//删除购物车选中多个产品
		if(!is_login()){
			$this->error( "您还没有登陆",U("User/login") );
		}
		$cart=D("shopcart");
		$ids=htmlspecialchars($_POST['ids']);
		$user=D("member");
		$uid=$user->uid();

		$idsarr = explode(",",$ids);
		foreach ($idsarr as $sort) {
			$cart->where("sort='$sort' and uid='$uid'")->delete();
		}

		$count=$cart->getCntByuid(); /*查询购物车中商品的种类 */
		$sum= $cart->getNumByuid();/* 查询购物车中商品的个数*/
		$price=$cart->getPriceByuid(); /* 购物车中商品的总金额*/
		$data['status'] = 1;
		$data['goodid'] =$id;
		$data['price'] =$price;
		$data['count'] = $count;
		$data['num'] =  $sum;
		$data['sum'] =  $sum;
		$data['msg'] = '处理成功';
		$this->ajaxReturn($data);
	}


 	public function getPricetotal($tag) {
        
        $data = M("shoplist")->where("tag='$tag'")->select();
        foreach ($data as $k=>$val) {
			$price=$val['price'];
            $total += $val['num'] * $price;
        }
        return sprintf("%01.2f", $total);
    }

	//获取订单分销总额
	public function getDisbutmoneyall($tag) {
		$data = M("shoplist")->where("tag='$tag'")->select();
		foreach ($data as $k=>$val) {
			$disbutmoney=$val['disbutmoney'];
			$disbutmoneyall +=  $disbutmoney;
		}
		return sprintf("%01.2f", $disbutmoneyall);
	}

	public function getgoodsnumall($tag) {
		$data = M("shoplist")->where("tag='$tag'")->select();
		$num = 0;
		foreach ($data as $k=>$val) {
			$num+=$val['num'];
		}
		return $num;
	}

	public function getpriceNum($id) {
		$price = 0.00;
		$data = M("shoplist")->where("tag='$id'")->select();
		foreach ($data as $k=>$item) {
			$sum += $item['num'];
		}
		return  $sum;
	}
	public function chengshi($id) {
		$name = M('Area')->getFieldById($id,'name');
		$pid = M('Area')->getFieldById($id,'pid');
		if(!$pid){
			return $name;
		}else{
			$str = $this->chengshi($pid).$name;
			return $str;
		}

	}
	//会员中心去支付，微信支付，设置session缓存orderid
	public function topay() {
		$orderid=$_POST["orderid"];
		$_SESSION['orderinfo']['orderid'] = $orderid;
		header('Location:'.U('/Weixin/Wxpay/pay'),false);
	}

	//删除购物车商品
	public function delCartGoods(){
		$ids = $_POST['ids'];
		if(!is_login()){
			if($ids){
				foreach($ids as  $v){
					if($v!='on'){
						unset($_SESSION['cart'][$v]);
					}
				}
			}
			$count=$this->getCnt(); /*查询购物车中商品的种类 */
			$sum= $this->getNum();/* 查询购物车中商品的个数*/
			$price=$this->getPrice(); /* 购物车中商品的总金额*/
			$data['count'] =$count;
			$data['price'] =$price;
			$Item=$this->getItem($sort);
			$data['num'] =$_SESSION['cart'][$sort]["num"];
			$data['sum'] =  $sum;
			$data['status'] = 1;
			$this->ajaxReturn($data);
		}else{
			$cart=D("shopcart");
			if($ids){
				$goodids = implode(',',$ids);
			}
			$user=D("member");
			$uid=$user->uid();

			if($result= $cart->where("goodid in ('".$goodids."') and uid='".$uid."'")->delete()){
				$count=$cart->getCntByuid(); /*查询购物车中商品的种类 */
				$sum= $cart->getNumByuid();/* 查询购物车中商品的个数*/
				$price=$cart->getPriceByuid(); /* 购物车中商品的总金额*/
				$data['status'] = 1;
				$data['goodid'] =$id;
				$data['price'] =$price;
				$data['count'] = $count;
				$data['num'] =  $sum;
				$data['sum'] =  $sum;
				$data['msg'] = '处理成功';
				$this->ajaxReturn($data);
			}else{
				$data['msg'] = '购物车商品不存在';
				$this->ajaxReturn($data);
			}
		}
	}


	//地区联动菜单
	public function changearea(){
		$comparea = M('area');
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
			$data['msg'] = 'no';	
			$this->ajaxReturn($data);
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
		 $this->success("删除成功",U('Shopcart/order'));
		 $data['status'] = 1;
		 $Transport->where("uid='$uid' and id='$id'")->setfied($data);
	}else{ 
		 $this->success("失败",U('Shopcart/order'));
		 $data['status'] = 0;
		 $Transport->where("uid='$uid' and id='$id'")->setfield($data);
	}
}

	//订单ajax删除地址
	public  function deladr() {
		if ( !is_login() ) {
			$this->error( "您还没有登陆",U("User/login") );
		}
		$id = $_POST['id'];
		if(!$id){
			$data['msg']='请选择要删除的地址';
			$data['stauts']=0;
			$this->ajaxReturn($data);
			exit;
		}
		
		$Transport = M("transport"); // 实例化transport对象
		$Member=D("member");
		$uid=$Member->uid();
		if($Transport->where("uid='$uid' and id='$id'")->delete()){
			 $data['status'] = 1;
			 $data['msg']='删除成功';

		}else{ 
			 $data['status'] = 0;
			 $data['msg']='删除失败';
		}
		$this->ajaxReturn($data);
	}
}
