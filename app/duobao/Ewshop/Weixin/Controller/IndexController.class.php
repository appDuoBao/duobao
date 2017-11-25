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
        $pk_list = M('WinExchange')->order('buy_time DESC')->limit(20)->select();
        foreach($pk_list as $key => $val){
            $codeid [] = $val['order_id'];
            $pk_list[$key]['goods_title'] = M('Document')->where("id = {$val['goods_id']}")->getField('title');
            $pk_list[$key]['period'] = $val['period'];
            if($val['utype'] == 2){
                $pk_list[$key]['userinfo'] = M('MemberTemp')->field('id as uid,headimgurl,nickname')->where("id = {$val['uid']}")->find();//虚拟用户
            }else{
                $pk_list[$key]['userinfo'] = M('Member')->field('uid,headimgurl,nickname')->where("uid = {$val['uid']}")->find();
            }
        }
	if(count($codeid)>0){
		$cmap['id'] = array('in',$codeid); 
		$codarr = M('WinOrder')->where($cmap)->getField('id,type,period');//var_dump($codeid);exit;
		foreach ($pk_list as $k=>$v) {
			$pk_list[$k]['codeid'] = ($codarr[$v['order_id']]['type'] ==1) ? '小' : '大';
		} 
	}
       // var_dump($pk_list);exit;
        $data['pk_list'] = $pk_list;
        

        //半价pk榜(购买记录)
		$buy_list = array();
		$i = 0;
		$nowtime = time()-(60*10);
        $order_list = M('WinOrder')->where("status =1")->order('create_time DESC')->limit(10)->select();
        foreach($order_list as $key => $val1){
			$buy_list[$i]['goods_title'] = M('Document')->where("id = {$val1['goods_id']}")->getField('title');
			$buy_list[$i]['buy_time'] = $val1['create_time'];
			$buy_list[$i]['buy_num'] = $val1['num'];
			$buy_list[$i]['period'] = $val1['period'];
			$buy_list[$i]['type'] = ($val1['type'] == 1) ? '小' : '大';
			if($val1['utype'] ==1){
                $buy_list[$i]['userinfo'] = M('Member')->field('uid,headimgurl,nickname')->where("uid = {$val1['uid']}")->find();
            }elseif($val1['utype']==2){
                $buy_list[$i]['userinfo'] = M('MemberTemp')->field('id as uid,headimgurl,nickname')->where("id = {$val1['uid']}")->find();
             }
       		$i++;
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
    
    public function topSort(){
        $ttype = $_GET['t'];
        if($ttype=='to'){
           $today = strtotime(date("Y-m-d"));
          // $today = 1503053125;
         }
         if($ttype == 'mo'){
           $today = time() - (7 * 24 *60 * 60);
           //
         }else{
             $today = strtotime(date("Y-m-d"));
             
              $ttype = 'to';
          }
         
        $Model = new \Think\Model(); // 实例化一个model对象 没有对应任何数据表
        $users = $Model->query("select w.uid,w.utype,order_id,sum(buy_num) as num from ewshop_win_exchange as w ,ewshop_win_order as o where w.order_id = o.id  and buy_time > ".$today."   group by uid  order by num desc limit 10");    
        if($users){
            foreach($users as $k=>$v){
                if($v['utype'] == 2){
                    $vuser[] = $v['uid'];    
                }else{
                     $user[] = $v['uid'];   
                 }    
            } 
            //var_dump($vuser);exit;
            if($vuser){
                 $vmap['id'] = array('in',$vuser); 
                 $vuser = M('MemberTemp')->where($vmap)->getField('id,headimgurl,nickname',true);//虚拟用户
             }
           
             if($user){
                $map['uid'] = array('in',$user);
                $user = M('Member')->where($map)->getField('uid as id,headimgurl,nickname',true);
             }   
            
             $vuser = is_array($vuser) ? $vuser : array();
             $user = is_array($user) ? $user : array();
             $usersar = array_merge_recursive($vuser,$user);
             
             foreach($users as $k=>$v){
                if($vuser[$v['uid']]){
                    $users[$k]['userinfo'] = $vuser[$v[uid]];
                }
                if($user[$v['uid']]){
                     $users[$k]['userinfo'] = $user[$v[uid]];   
                }
             }
        }
        $one = array_shift($users);
        $two  = array_shift($users);
        $three = array_shift($users);
        
        $this->assign('type',$ttype);
        $this->assign('one',$one);
        $this->assign('two',$two);
        $this->assign('three',$three);
        
        $this->assign('users',$users);
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
