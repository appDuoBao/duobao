<?php
// +----------------------------------------------------------------------
// | 微信管理系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010-2017  All rights reserved.
// +----------------------------------------------------------------------
// | Author:
// +----------------------------------------------------------------------

namespace Weixin\Controller;

use OT\DataDictionary;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 * $url= $_SERVER[HTTP_HOST]; //获取当前域名
 */
class IndexController extends HomeController {

    /**
     * 构造方法
     * IndexController constructor.
     * @author
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * 首页
     * @author
     */
    public function index(){
        $ad1 = D('Ad')->getAds(28);
        $data['indexImgs']  = $ad1;  //获得顶部信息

        $ad2   = D('Ad')->getAds(29);  //获得中部广告
        $data['indexGoods'] = $ad2[0];

        //首页商品
        $data['list_50'] = M('Document')->where("category_id = 217 and price = 1 and status = 1")->order('id asc')->select();//50元卡
//        foreach($data['list_50'] as $key => $val){
//            //授权url
//            $detail_url = 'http://'.$_SERVER['HTTP_HOST'].'/Weixin/Goods/detail/id/'.$val['id'];
//            $data['list_50'][$key]['url'] = R('Qfpay/getGoodsDetailUrl' , array ($detail_url));
//        }
        $data['list_100'] = M('Document')->where("category_id = 217 and price = 2 and status = 1")->order('id asc')->select();//100元卡
//        foreach($data['list_100'] as $key => $val){
//            //授权url
//            $detail_url = 'http://'.$_SERVER['HTTP_HOST'].'/Weixin/Goods/detail/id/'.$val['id'];
//            $data['list_100'][$key]['url'] = R('Qfpay/getGoodsDetailUrl' , array ($detail_url));
//        }

        $data['time_end'] = $this->get_time_on_clock(time());//倒计时时间

        //最近中奖(中奖记录)
        $pk_list = M('WinExchange')->order('buy_time DESC')->limit(10,20)->select();
        foreach($pk_list as $key => $val){
            $pk_list[$key]['goods_title'] = M('Document')->where("id = {$val['goods_id']}")->getField('title');
            if($val['is_virtual'] == 1){
                $pk_list[$key]['userinfo'] = M('MemberTemp')->field('headimgurl,nickname')->where("id = {$val['uid']}")->find();//虚拟用户
            }else{
                $pk_list[$key]['userinfo'] = M('Member')->field('headimgurl,nickname')->where("uid = {$val['uid']}")->find();
            }
        }
        $data['pk_list'] = $pk_list;



        //半价pk榜(购买记录)
		$buy_list = array();
		$i = 0;
		$nowtime = time()-(60*10);
        $order_list = M('WinOrder')->where("status =1 and create_time >".$nowtime)->order('create_time DESC')->limit(10)->select();
        foreach($order_list as $key => $val1){
			$buy_list[$i]['goods_title'] = M('Document')->where("id = {$val1['goods_id']}")->getField('title');
			$buy_list[$i]['buy_time'] = $val1['create_time'];
			$buy_list[$i]['buy_num'] = $val1['num'];
            $buy_list[$i]['userinfo'] = M('Member')->field('headimgurl,nickname')->where("uid = {$val1['uid']}")->find();
       		$i++;
        }			
        //半价pk榜(中奖记录)
        $pk_list2 = M('WinExchange')->where("is_virtual =1")->order('buy_time DESC')->limit(1,20)->select();
        foreach($pk_list2 as $key => $val2){
			if($i<20){
				$buy_list[$i]['goods_title'] = M('Document')->where("id = {$val2['goods_id']}")->getField('title');
				$buy_list[$i]['buy_time'] = $val2['buy_time'];
				$buy_list[$i]['buy_num'] = $val2['buy_num'];				
				$buy_list[$i]['userinfo'] = M('MemberTemp')->field('headimgurl,nickname')->where("id = {$val2['uid']}")->find();//虚拟用户
				$i++;
			}
        }	
		
		$buy_time=array();
		foreach($buy_list as $buy){
			$buy_time[]=$buy["buy_time"];
		}
		array_multisort($buy_time, SORT_DESC, $buy_list);
		$data['buy_list'] = $buy_list;
		


        //开奖号码
        $code_list = M('WinCode')->where("code <> '0'")->order('id desc')->limit('10')->select();
        foreach($code_list as $key => $val){
            $code_list[$key]['code'] = chunk_split($val['code'],1,' ');
            $code_list[$key]['create_time'] = explode(' ',$val['create_time']);
            $code_list[$key]['code_56_type'] = ($val['code_56_type'] == 1) ? '小' : '大';
            $code_list[$key]['code_110_type'] = ($val['code_110_type'] == 1) ? '小' : '大';

        }
//        dump($code_list);

        $data['code_list'] = $code_list;
        $this->assign('data' , $data);
        $this->meta_title = '首页';
        $this->display();
    }

    /**
     * 新手介绍
     * @author
     */
    public function introduce(){
        $this->meta_title = '玩法规则';
        $this->display();
    }

    /**
     * 游戏算法规则
     * @author
     */
    public function gameIntroduce(){
        $this->meta_title = '玩法规则';
        $this->display();
    }

    /**
     * 提示页面，只能微信打开
     * @author
     */
    public function onlywx(){
		$this->meta_title = '微信页面';
		$this->display();
    }
}