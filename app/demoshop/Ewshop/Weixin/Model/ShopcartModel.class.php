<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ewangtx <www@ewangtx.com> 
// +----------------------------------------------------------------------

namespace Weixin\Model;
use Think\Model;
use Think\Page;

/**
 * 登录用户的购物车类
 */
class ShopcartModel extends Model{

	 /*
	查询购物车
	*/
	public  function getcart() {
		$user=D("member");
		$goodsdata = M('GoodsData');
		$uid=$user->uid();
		$map["uid"]= $uid;
		$buyaode = array();
		$cartlist=$this->where($map)->select();
		if($cartlist){
			$document = D('Document');
			foreach($cartlist as $k=>$v){
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
					$cartlist[$k]['parameters'] = '';
				}  
				$cartlist[$k]['price'] = $document->getFieldById($v['goodid'],'price')+$parprice;
			}
		}
		if($buyaode){
			foreach($buyaode as $k=>$v){
				unset($cartlist[$v]);
			}
		}
		return $cartlist;
	}

	/*
    查询购物车中商品的种类
    */
	public  function getCnt(){
		$user=D("member");
		$uid=$user->uid();
		$map["uid"]= $uid;
		$cartlist=$this->where($map)->select();
		return count($cartlist);
	}
	public  function getCntByuid(){
		$user=D("member");
		$uid=$user->uid();
		$map["uid"]= $uid;
		$cartlist=$this->where($map)->select();
		return count($cartlist);
	}
	/*
    查询登录用户购物车中商品的总金额
    */
	public function getPriceByuid() {
		$document	=	D('Document');
		$goodsdata = M('GoodsData');
		$uid=D("member")->uid();
		$map["uid"]= $uid;
		//数量为0，价钱为0
		if ($this->getCnt() == 0) {
			return 0;
		}else{
			$total = 0.00;
			$parprice = 0;
			$data = $this->where($map)->select();
			foreach ($data as $k=>$val) {
				$id=$val['goodid'];
				$par = explode(',',$val['sort']);
				if(count($par)>1){
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
					$sql = "SELECT sku FROM `ewshop_goods_sku` WHERE cid IN (".$val['sort'].") AND sku IN (".$skustr.") GROUP BY sku HAVING COUNT(sku)=".count($par);
					$proinfo = M()->query($sql);
					if($proinfo){

						$parprice = M('GoodsData')->getFieldBySku($proinfo[0]['sku'],'total');

					}else{
						$parprice = 0;
					}

				}  
				$price = $document->getFieldById($id,'price')+$parprice;
				$total += $val['num'] * $price;
			}
		}
		return sprintf("%01.2f", $total);
	}
	/* 查询登录用户购物车中商品的个数*/
	public function getNumByuid(){
		$user=D("member");
		$uid=$user->uid();
		$map["uid"]= $uid;
		if ($this->getCnt() == 0) {
			//种数为0，个数也为0
			return 0;
		}else{
			$data=$this->where($map)->select();
			foreach ($data as $k=>$item){
				$sum += $item['num'];
			}
		}
		return $sum;
	}
	/* 登录用户增加购物车中商品的个数*/
	public function inc($sort){
		$user=D("member");
		$uid=$user->uid();
		$cart=D("shopcart");
		$num= $cart->where("sort='$sort'and uid='$uid'")->getField("num");
		$new=$num+1;
		$cart->where("sort='$sort'and uid='$uid'")->setField('num',$new);
		return $new;
	}
	/* 登录用户减少购物车中商品的个数*/
	public function dec($sort){
		$user=D("member");
		$uid=$user->uid();
		$cart=D("shopcart");
		$num= $cart->where("sort='$sort'and uid='$uid'")->getField("num");
		$new=$num-1;
		$cart->where("sort='$sort'and uid='$uid'")->setField('num',$new);
		return $new;

	}
	/* 登录用户删除购物车中商品的个数*/
	public function deleteid($sort){
		$user=D("member");
		$uid=$user->uid();
		$cart=D("shopcart");
		$result= $cart->where("sort='$sort'and uid='$uid'")->delete();

		return $id;
	}

}
