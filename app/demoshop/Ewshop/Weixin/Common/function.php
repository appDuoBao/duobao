<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------

/**
 * 前台公共库文件
 * 主要定义前台公共函数库DF
 */

/**
 * 检测验证码
 * @param  integer $id 验证码ID
 * @return boolean     检测结果
 * @author ew_xiaoxiao <www@ewangtx.com>
 */
function check_verify($code, $id = 1){
	$verify = new \Think\Verify();
	return $verify->check($code, $id);
}

/**
 * 获取列表总行数
 * @param  string  $category 分类ID
 * @param  integer $status   数据状态
 * @author ew_xiaoxiao <www@ewangtx.com>
 */
function get_list_count($category, $status = 1){
    static $count;
    if(!isset($count[$category])){
        $count[$category] = D('Document')->listCount($category, $status);
    }
    return $count[$category];
}
function get_group_price($unionid){
	$unionid= explode('、',$unionid);
	$total="";
foreach($unionid as $val){
$id=$val;
$total+=get_good_price($id);
}
   
    return $total;
}
function get_group_count($unionid){
	$array= explode('、',$unionid);
$number=count($array);
  
    return $number;
}
function get_face($uid){

	$comment=M("ucenter_member");
	$map['id']=$uid;
	$count=$comment->where($map)->find();
  
    return $count["face"];
}
function get_comment_count($id){
	$comment=M("comment");
	$map['goodid']=$id;
	$count=$comment->where($map)->count();
  
    return $count;
}
function get_message_count($id){
$message=M("message");
$map['goodid']=$id;
	$count=$message->where($map)->count();
  
    return $count;
}

function get_group_marketprice($unionid){
	$unionid= explode('、',$unionid);
	$total="";
foreach($unionid as $val){
$id=$val;
$total+=get_good_yprice($id);
}

 if(!isset($total)){
$price=get_group_price($unionid);

 }
 return $total?$total:$price;  
 
}
/**
 * 返回优惠券可抵用金额
 */
function get_fcoupon_fee($code,$total){
    $lowfee=get_fcoupon_lowpayment($code);//优惠券最低消费金额
	if($lowfee<$total)
	{
$info=M("fcoupon")->where("code='$code' and status='1'")->find();//获取优惠券主键id
$fee=$info["price"];//获取优惠券金额
$codeid=$info["id"];
$usercouponid=M("usercoupon")->where("couponid='$codeid' and status='1'")->getField('id');//获取用户可用优惠券主键id
if($usercouponid){
$deccode=$fee;
$uid=D("member")->uid();
M("usercoupon")->where("couponid='$codeid' and uid='$uid' ")->setField('status',2);//设置优惠券已用
}
else{
	$deccode=0;
}
}
else{
$deccode=0;
}
 return $deccode; 
}

/**
 * 获取段落总数
 * @param  string $id 文档ID
 * @return integer    段落总数
 * @author ew_xiaoxiao <www@ewangtx.com>
 */
function get_part_count($id){
    static $count;
    if(!isset($count[$id])){
        $count[$id] = D('Document')->partCount($id);
    }
    return $count[$id];
}
function get_tuan_count($id){
 $number=M('Tuanid')->where("tuanpid='$id'")->count();
    return  $number;
}
function get_shop_mobile($id){
 $info=M('shop')->where("id='$id'")->find();
    return  $info["mobile"];
}
function get_shop_address($id){
 $info=M('shop')->where("id='$id'")->find();
    return  $info["shopaddress"];
}
function  get_up_school(){
	$get_up=$_GET['id'];
	return $get_up;
}

//在线交易订单支付处理函数
 //函数功能：根据支付接口传回的数据判断该订单是否已经支付成功；
 //返回值：如果订单已经成功支付，返回true，否则返回false；
 function checkorderstatus($ordid){
    $Ord=M('Orderlist');
    $ordstatus=$Ord->where('ordid='.$ordid)->getField('ordstatus');
    if($ordstatus==1){
        return true;
    }else{
        return false;    
    }
 }
//处理订单函数
 //更新订单状态，写入订单支付后返回的数据
 function orderhandle($parameter){
    $ordid=$parameter['out_trade_no'];//商户网站订单系统中唯一订单号
    $data['payment_trade_no']      =$parameter['trade_no']; //支付宝交易号
    $data['payment_trade_status']  =$parameter['trade_status'];
    $data['payment_notify_id']     =$parameter['notify_id'];//通知校验ID。
    $data['payment_notify_time']   =$parameter['notify_time'];
    $data['payment_buyer_email']   =$parameter['buyer_email']; //买家支付宝帐号；
    $data['ordstatus']             =1;
    $Ord=M('Orderlist');
    $Ord->where('ordid='.$ordid)->save($data);
	$data = array('status'=>'1','ispay'=>'2');//设置订单为已经支付,状态为已提交
	M('order')->where('orderid='.$ordid)->setField($data);
 } 
 
 
//-----------------------------------------------20150717-------------------------------// 
 /**
 * 根据分类id获取子分类及产品
 * @param  string $cid 分类id
 * @return array      分类产品数据
 * @author 一网天行 <www@ewangtx.com>
 */
function get_subcpbycid($cid){
	$field = 'id,name,pid,title';
	//根据分类id获取子分类
	$category = D('Category')->field($field)->order('sort desc')->where('display="1" and ismenu="1" and pid='.$cid)->select();
	foreach ($category as $k => $v ) {
		$category [$k] ['doc'] = array ();
		$category [$k] ['doc'] = getproducts($v['id']);
	}	
    return  $category;

}

/**热卖商品 获取基础表+扩展表商品信息**/
function remailist(){
	$prefix = C('DB_PREFIX');
	$list = M('Document')->table($prefix.'document a')
		->join($prefix.'document_product b on a.id=b.id')
		->where('a.mark like "%3%" and a.status=1')
		->order('a.level asc,a.id desc' )
		->limit("16")
		->select();		
	return $list;
}	

function get_subcpby(){   //获取一级栏目分类及分类
	$field = 'id,name,pid,title';
	//根据分类id获取子分类
	$category = D('Category')->field($field)->order('sort desc')->where('display="1" and ismenu="1" and pid="0"')->select();
	foreach ($category as $k => $v ) {
		$category [$k] ['doc'] = array ();
		$category [$k] ['doc'] = getproducts($v['id']);
	}	
    return  $category;
} 
function getproducts($cid){//根据分类id获取分类下产品，无限级别获取
	$map['category_id']=$cid;
	$map['status']=1;
	$doc = M('Document')->where($map)->order("id desc")->limit(10)->select();
	$field = 'id,name,pid,title';
	//根据分类id获取子分类
	$subcategory = D('Category')->field($field)->order('sort desc')->where('display="1" and ismenu="1" and pid='.$cid)->select();	
	if($subcategory){
		foreach ($subcategory as $k => $v ) {
			$docs = getproducts($v['id']);
			if($doc){
				$doc = array_merge($doc, $docs);
			}else{
				$doc = $docs;
			}
		}	
	}
	return $doc;
}


