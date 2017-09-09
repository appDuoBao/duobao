<?php
// +----------------------------------------------------------------------
// | 微信管理系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010-2017  All rights reserved.
// +----------------------------------------------------------------------
// | Author:
// +----------------------------------------------------------------------
namespace Control\Controller;
use User\Api\UserApi;

/**
 * 拍卖订单管理控制器
 * @author
 */
class BiddingController extends ControlController
{
    /**
     * 订单列表
     * author
     */
    public function index(){
        //ini_set('memory_limit', '-1');
        //当前管理员id
        $gid = $_SESSION['onethink_admin']['user_auth']['uid'];
        $groupid = M('admin')->where(array('uid'=>$gid))->getField('groupid');
        if($groupid == 7){    //是企业分销管理员
            $userinfo = M('Join')->where(array('gid'=>$gid,'is_delete'=>0,'status'=>1))->field('uid,ratio')->find();
            $ratio = $userinfo['ratio'];
            $ids = M('Member')->where(array('parent_id'=>$userinfo['uid']))->getField('uid',true);
//            $ids[] = $uid;
            if($ids){
                $map['uid'] = array("in", $ids);
            }
        }
        $this->assign('groupid', $groupid);
		

        $period = I('period');
		if($period){
			$map['period'] = $period;	
		}
		
		$start_date      = I('start_date');
		$end_date      = I('end_date');
		if($start_date && $end_date){
			$map['create_time'] = array(array('egt',strtotime($start_date)),array('lt',strtotime($end_date)+(24*60*60)));
		}elseif($start_date  && empty($end_date)){
			$map['create_time']  = array('egt',strtotime($start_date));
		}elseif($end_date && empty($start_date)){
			$map['create_time']  = array('lt',strtotime($end_date)+(24*60*60));
		}		 
		
        $uid = I('uid');
        $uid = trim($uid);
        if ($uid) {
             $map['uid'] = array('eq',$uid);
			$map_cur['uid'] = array('eq',$uid);
			$map_cur['utype'] = array('eq',1);
			$orders =M('WinOrder')->where($map_cur)->select();	
			$orderid = "";
			foreach ($orders as $k => $ov) {
				$orderid[] = $ov['id'];
			}			
			if($orderid){
				$map2['order_id'] = array("in", $orderid);
			}
        }
        
        if (!empty($_GET['goods'])) {
            $_GET['goods'] = htmlspecialchars(trim($_GET['goods']));
            $goodsIdList = M()->query("SELECT id FROM ewshop_document WHERE title LIKE '%{$_GET['goods']}%'");
            if ($goodsIdList) {
                foreach ($goodsIdList as $k => $v) {
                    $gList[] = $v['id'];
                }
                $map['goods_id'] = array('in', $gList);
            } else {
                $map['goods_id'] = '';
            }
        }

        $map['status'] = 1;
        $map['utype'] = 1;
		$allprice =M('WinOrder')->where($map)->Sum('money');	
        $list = $this->lists('WinOrder', $map, "create_time desc");
		
        foreach ($list as $key => $val) {
            $order[] = $val['id'];
            $lottery_time[] = $val['lottery_time'];
            $users[] = $val['uid'];
            $goods[] = $val['goods_id'];
            $list[$key]['pay_status'] = $val['status'] == 1 ? '已支付' : '未支付';                          
			$list[$key]['commission'] = $val['money'] * $ratio * 0.01;
           
            
        }
         $gmap['id'] = array('in',$goods);
         $goodinfo = M('Document')->where($gmap)->getField('id,title',true);
         $umap['uid'] =array('in',$users); 
         $usesinfo = M('Member')->where($umap)->getField("uid as id,nickname",true);
         $omap['order_id'] =array('in',$order);	
         $omap['utype'] = array('eq',1); 
         $WinExchange = M('WinExchange')->where($omap)->getField('order_id,is_exchange');

		$wmap['create_time'] = array('eq',$lottery_time);
   		//$winCode = M('WinCode')->where($wmap)->getField('id,code,code_56,code_56_type,code_110,code_110_type',true);
     
		 foreach ($list as $key => $val) {
            
            $list[$key]['goods_title'] = $goodinfo[$val['goods_id']];
            $list[$key]['nickname']  = $usesinfo[$val['uid']];
            if(isset($WinExchange[$val['id']])){
                $list[$key]['iszhong'] = '中奖';
			    $list[$key]['is_exchange'] = $WinExchange[$val['id']]['is_exchange'] == 1 ? '已兑换' : '未兑换';
		    }else{
		        $list[$key]['iszhong'] = '未中奖'; 
		    }
		    
//		    	$list[$key]['code'] = $winCode['code'];
//			
//				$list[$key]['xkjnum'] = $winCode['code_56'];//开奖大小
//				$list[$key]['xkjdx'] = $winCode['code_56_type'];//开奖大小
//			
//				$list[$key]['dkjnum'] = $winCode['code_110'];//开奖大小
//				$list[$key]['dkjdx'] = $winCode['code_110_type'];//开奖大小
//		
            
        }
       //exit;
        int_to_string($list);
		//$this->assign('allprice', $allprice);
        $this->assign('_list', $list);

//
//        $map2['utype'] = 1;
//        $dhprice =0;
//        $wdhprice =0;
//        $allprice1= 0;
//        $list2 = M('WinExchange')->where($map2)->select();
//        foreach ($list2 as $key => $val) {
//            $price = M('Document')->getFieldById($val['goods_id'], 'price');
//            if($price==1){
//                $allprice1 = $val['buy_num'] * 50;
//            }elseif($price==2){
//                $allprice1 = $val['buy_num'] * 100;
//            }
//            if($val['is_exchange'] == 1){
//                $dhprice += $allprice1;//已经兑奖金额
//            }else{
//                $wdhprice += $allprice1;//未兑奖金额
//            }
//            $allprice2 += $allprice1;//中奖总金额
//        }
//        $this->assign('allzprice', $allprice2);
//        $this->assign('dhprice', $dhprice);
//        $this->assign('wdhprice', $wdhprice);


        $this->meta_title = '订单列表';//exit('tet');
        $this->display();
    }


    /**
     * 导出订单信息信息
     * @author
     */
    public function export()
    {

        $mobile = I('mobile');
        $mobile = trim($mobile);
        if ($mobile) {
            $uid = M('UcenterMember')->field("id")->where("username LIKE '%$mobile%'")->select();
            $newuid = "";
            if ($uid) {
                foreach ($uid as $b) {
                    $newuid[] = $b['uid'];
                }
                $map['uid'] = array("in", $newuid);
            }
        }
        if (!empty($_GET['goods'])) {
            $_GET['goods'] = htmlspecialchars(trim($_GET['goods']));
            $goodsIdList = M()->query("SELECT id FROM ewshop_document WHERE title LIKE '%{$_GET['goods']}%'");
            foreach ($goodsIdList as $k => $v) {
                $gList[] = $v['id'];
            }
            $map['goods_id'] = array('in', $gList);
        }


        if (!empty($_GET['time'])) {
            $startTime = strtotime($_GET['time']) - 1;
            $endTime = strtotime($_GET['time']) + 86400;
            $map['create_at'] = array(array('gt', $startTime), array('lt', $endTime));
        }
        // $map['is_get'] = 1;
        $list = $this->lists_diy('bidding_log', $map, "create_at desc");

        foreach ($list as $key => $val) {
            $list[$key]['mobile'] = M('UcenterMember')->getFieldById($val['uid'], 'mobile');
            $list[$key]['email'] = M('UcenterMember')->getFieldById($val['uid'], 'email');
            $list[$key]['goods_name'] = M('Document')->getFieldById($val['goods_id'], 'title');
            $list[$key]['start_price'] = M('Document')->getFieldById($val['goods_id'], 'start_price');
        }
        int_to_string($list);

        vendor('PHPExcel');
        $objPHPExcel = new \PHPExcel();

        // 设置文件的一些属性，在xls文件——>属性——>详细信息里可以看到这些值，xml表格里是没有这些值的
        $objPHPExcel->getProperties()//获得文件属性对象，给下文提供设置资源
        ->setCreator("admin")//设置文件的创建者
        ->setLastModifiedBy("admin")//设置最后修改者
        ->setTitle("Office 2007 XLSX Document")//设置标题
        ->setSubject("Office 2007 XLSX Document")//设置主题
        ->setDescription("Document for Office 2007 XLSX, generated using PHP classes.")//设置备注
        ->setKeywords("office 2007 openxml php")//设置标记
        ->setCategory("Result file"); //设置类别

        // 给表格添加数据（表头）
        $objPHPExcel->setActiveSheetIndex(0)//设置第一个内置表（一个xls文件里可以有多个表）为活动的
        ->setCellValue('A1', '电话')//数据格式可以为字符串
        ->setCellValue('B1', '邮箱')//数据格式可以为字符串
        ->setCellValue('C1', '拍卖品')//数据格式可以为字符串
        ->setCellValue('D1', '起拍价')//数据格式可以为字符串
        ->setCellValue('E1', '成交价')//数据格式可以为字符串
        ->setCellValue('F1', '成交时间'); //数据格式可以为字符串


        // 给表格添加数据（内容）
        $name = 2;
        foreach ($list as $k => $v) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $name, $v['mobile']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $name, $v['email']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $name, $v['goods_name']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $name, $v['start_price']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $name, $v['price']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $name, date('Y-m-d H:i:s', $v['create_at']));
            $name++;
        }

        //得到当前活动的表,注意下文教程中会经常用到$objActSheet
        $objActSheet = $objPHPExcel->getActiveSheet();
        // 给当前活动的表设置名称
        $objActSheet->setTitle('会员数据');
        //设置列的宽度
        $objActSheet->getColumnDimension('A')->setWidth(22); //30
        $objActSheet->getColumnDimension('B')->setWidth(35); //30
        $objActSheet->getColumnDimension('C')->setWidth(25); //30
        $objActSheet->getColumnDimension('D')->setWidth(50); //30
        $objActSheet->getColumnDimension('E')->setWidth(25); //30
        $objActSheet->getColumnDimension('F')->setWidth(25); //30

        /*********  浏览器输出  ********/

        // 生成2007excel格式的xlsx文件
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="订单数据.xlsx"'); //文件名称
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }


    /**
     * 出价记录
     * author
     */
    public function bidding_log()
    {
        I('get.order_id') ? $map['id'] = I('get.order_id') : '';
        I('get.order_number') ? $map['order_number'] = I('get.order_number') : '';
        $map['status'] = 1;
        $data = M('WinOrder')->where($map)->find();
        $data['memberinfo'] = M('Member')->field('nickname')->where("uid = {$data['uid']}")->find();
        $data['goodsinfo']  = M('Document')->field('title')->where("id = {$data['goods_id']}")->find();
        $data['type']       = ($data['type'] == 1) ? '小' : '大';
        $this->assign('data',$data);
        $this->meta_title = '订单详情';
        $this->display();
    }

    /**
     * 未处理兑换管理
     * @author
     */
    public function noexchange(){

		$map['is_exchange'] = 0;

		$start_date      = I('start_date');
		$end_date      = I('end_date');
		if($start_date && $end_date){
			$map['buy_time'] = array(array('egt',strtotime($start_date)),array('lt',strtotime($end_date)+(24*60*60)));
		}elseif($start_date  && empty($end_date)){
			$map['buy_time']  = array('egt',strtotime($start_date));
		}elseif($end_date && empty($start_date)){
			$map['buy_time']  = array('lt',strtotime($end_date)+(24*60*60));
		}		 
		 
        $nickname = I('nickname');
        //兑换码
        $exchange_number = I('exchange_number');
        $nickname = trim($nickname);
        if ($nickname) {
            $uid = M('Member')->field("uid")->where("nickname LIKE '%$nickname%'")->select();
            $newuid = "";
            if ($uid) {
                foreach ($uid as $b) {
                    $newuid[] = $b['uid'];
                }
                $map['uid'] = array("in", $newuid);
            } else {
                $map['uid'] = '';
            }
        }
        if(!empty($exchange_number)){
            $map['exchange_number'] = $exchange_number;
        }
        if (!empty($_GET['goods'])) {
            $_GET['goods'] = htmlspecialchars(trim($_GET['goods']));
            $goodsIdList = M()->query("SELECT id FROM ewshop_document WHERE title LIKE '%{$_GET['goods']}%'");
            if ($goodsIdList) {
                foreach ($goodsIdList as $k => $v) {
                    $gList[] = $v['id'];
                }
                $map['goods_id'] = array('in', $gList);
            } else {
                $map['goods_id'] = '';
            }
        }

        $map['utype'] = 1;
        $map['is_virtual'] = 0;
		$wdhprice =0;	
        $list = $this->lists('WinExchange', $map, "buy_time desc");
        foreach ($list as $key => $val) {
             $orders[] = $val['order_id'];
             $uids[] = $val['uid'];
             $goods[] = $val['goods_id'];
             
            $list[$key]['is_exchange_val'] = ($val['is_exchange'] == 1) ? '已兑换' : '未兑换';
			if($val['is_exchange'] != 1){
				$wdhprice += $list[$key]['allprice'];//未兑奖金额
			}
			
        }
              $omap['id'] = array('in',$orders);
              $omap['status'] = array('eq',1);
              $orderarr = M('WinOrder')->where($omap)->getField('id,utype,goods_id,period,num,lottery_time',true);
              $umap['uid'] = array('in',$uids);
              $users = M('Member')->where($umap)->getField("uid as id,nickname");
              $gmap['id'] = array('in',$goods);
              $goodsin= M('Document')->where($gmap)->getField('id,title,price');
           
          foreach ($list as $key => $val) {
            $list[$key]['orderinfo'] = $orderarr[$val['order_id']];
            $list[$key]['nickname'] = $users[$val['uid']];
            $list[$key]['goods_title'] = $goodsin[$val['goods_id']]['title'];
            if($goodsin[$val['id']]['price']==1){
				$list[$key]['allprice'] = $list[$key]['orderinfo']['num'] * 50;
			}elseif($goodsin[$val['id']]['price']==2){
				$list[$key]['allprice'] = $list[$key]['orderinfo']['num'] * 100;
			}
            
        }
          
			
        int_to_string($list);
        $this->assign('_list', $list);
		$this->assign('wdhprice', $wdhprice);//未兑奖金额
        $this->meta_title = '未处理兑换';
        $this->display();
    }


    /**
     * 统计信息
     * @author
     */
    public function tongji(){


        $start_date      = I('start_date');
        $end_date      = I('end_date');
        if($start_date && $end_date){
            $ssmap['create_time'] = array(array('egt',strtotime($start_date)),array('lt',strtotime($end_date)+(24*60*60)));
            $ssmap2['buy_time'] = array(array('egt',strtotime($start_date)),array('lt',strtotime($end_date)+(24*60*60)));
        }elseif($start_date  && empty($end_date)){
            $ssmap['create_time']  = array('egt',strtotime($start_date));
            $ssmap2['buy_time']  = array('egt',strtotime($start_date));
        }elseif($end_date && empty($start_date)){
            $ssmap['create_time']  = array('lt',strtotime($end_date)+(24*60*60));
            $ssmap2['buy_time']  = array('lt',strtotime($end_date)+(24*60*60));
        }
        if($start_date  ||  $end_date){
            $ssmap['status'] = 1;
            $ssmap['utype'] = 1;
            $sstallprice =M('WinOrder')->where($ssmap)->Sum('money');	//总收益

            $ssmap2['utype'] = 1;
            $ssmap2['is_virtual'] = 0;
            $sstdhprice =0;
            $sstwdhprice =0;
            $ssallprice2 = 0;
            $sstlist = M('WinExchange')->where($ssmap2)->select();
            foreach ($sstlist as $key => $ssval2) {
                $price = M('Document')->getFieldById($ssval2['goods_id'], 'price');
                if($price==1){
                    $ssallprice2 = $ssval2['buy_num'] * 50;
                }elseif($price==2){
                    $ssallprice2 = $ssval2['buy_num'] * 100;
                }
                if($ssval2['is_exchange'] == 1){
                    $sstdhprice += $ssallprice2;//已经兑奖金额
                }else{
                    $sstwdhprice += $ssallprice2;//未兑奖金额
                }
            }
            $this->assign('sstallprice', $sstallprice);
            $this->assign('sstdhprice', $sstdhprice);
            $this->assign('sstwdhprice', $sstwdhprice);
        }


        $map['status'] = 1;
        $map['utype'] = 1;
		$allprice =M('WinOrder')->where($map)->Sum('money');	//总收益
		
        $map2['utype'] = 1;
        $map2['is_virtual'] = 0;
		$dhprice =0;
		$wdhprice =0;
		$allprice1= 0;
        $list = M('WinExchange')->where($map2)->select();
        foreach ($list as $key => $val) {
			$price = M('Document')->getFieldById($val['goods_id'], 'price');
			if($price==1){
				$allprice1 = $val['buy_num'] * 50;
			}elseif($price==2){
				$allprice1 = $val['buy_num'] * 100;
			}
			if($val['is_exchange'] == 1){
				$dhprice += $allprice1;//已经兑奖金额
			}else{
				$wdhprice += $allprice1;//未兑奖金额
			}
        }		
		$this->assign('allprice', $allprice);
		$this->assign('dhprice', $dhprice);
		$this->assign('wdhprice', $wdhprice);
		



		
        $tmap['status'] = 1;
        $tmap['utype'] = 1;
		$tmap['create_time']  = array('egt',strtotime(date('Y-m-d')));//当日
		$tallprice =M('WinOrder')->where($tmap)->Sum('money');	//总收益
		
        $tmap2['utype'] = 1;
        $tmap2['is_virtual'] = 0;
		$tmap2['buy_time']  = array('egt',strtotime(date('Y-m-d')));//当日
		$tdhprice =0;
		$twdhprice =0;
		$allprice2 = 0;
        $tlist = M('WinExchange')->where($tmap2)->select();
        foreach ($tlist as $key => $val2) {
			$price = M('Document')->getFieldById($val2['goods_id'], 'price');
			if($price==1){
				$allprice2 = $val2['buy_num'] * 50;
			}elseif($price==2){
				$allprice2 = $val2['buy_num'] * 100;
			}
			if($val2['is_exchange'] == 1){
				$tdhprice += $allprice2;//已经兑奖金额
			}else{
				$twdhprice += $allprice2;//未兑奖金额
			}
        }		
		$this->assign('tallprice', $tallprice);
		$this->assign('tdhprice', $tdhprice);
		$this->assign('twdhprice', $twdhprice);

        //昨天数据
        $tmap3['status'] = 1;
        $tmap3['utype'] = 1;
        $tmap3['create_time'] = array(array('egt',strtotime(date('Y-m-d'))-(24*60*60)),array('lt',strtotime(date('Y-m-d'))));
        $tallprice3 =M('WinOrder')->where($tmap3)->Sum('money');	//总收益

        $tmapp3['utype'] = 1;
         $tmapp3['is_virtual'] = 0;
        $tmapp3['buy_time']  = array(array('egt',strtotime(date('Y-m-d'))-(24*60*60)),array('lt',strtotime(date('Y-m-d'))));
        $tdhprice3 =0;
        $twdhprice3 =0;
        $allprice3 = 0;
        $tlist3 = M('WinExchange')->where($tmapp3)->select();
        foreach ($tlist3 as $key => $val3) {
            $price = M('Document')->getFieldById($val3['goods_id'], 'price');
            if($price==1){
                $allprice3 = $val3['buy_num'] * 50;
            }elseif($price==2){
                $allprice3 = $val3['buy_num'] * 100;
            }
            if($val3['is_exchange'] == 1){
                $tdhprice3 += $allprice3;//已经兑奖金额
            }else{
                $twdhprice3 += $allprice3;//未兑奖金额
            }
        }
        $this->assign('tallprice3', $tallprice3);
        $this->assign('tdhprice3', $tdhprice3);
        $this->assign('twdhprice3', $twdhprice3);

        //前天数据
        $tmap4['status'] = 1;
        $tmap4['utype'] = 1;
        $tmap4['create_time'] = array(array('egt',strtotime(date('Y-m-d'))-(24*60*60*2)),array('lt',strtotime(date('Y-m-d'))-(24*60*60)));
        $tallprice4 =M('WinOrder')->where($tmap4)->Sum('money');	//总收益

        $tmapp4['utype'] = 1;
        $tmapp4['is_virtual'] = 0;
        $tmapp4['buy_time']  = array(array('egt',strtotime(date('Y-m-d'))-(24*60*60*2)),array('lt',strtotime(date('Y-m-d'))-(24*60*60)));
        $tdhprice4 =0;
        $twdhprice4 =0;
        $allprice4 = 0;
        $tlist4 = M('WinExchange')->where($tmapp4)->select();
        foreach ($tlist4 as $key => $val4) {
            $price = M('Document')->getFieldById($val4['goods_id'], 'price');
            if($price==1){
                $allprice4 = $val4['buy_num'] * 50;
            }elseif($price==2){
                $allprice4 = $val4['buy_num'] * 100;
            }
            if($val4['is_exchange'] == 1){
                $tdhprice4 += $allprice4;//已经兑奖金额
            }else{
                $twdhprice4 += $allprice4;//未兑奖金额
            }
        }
        $this->assign('tallprice4', $tallprice4);
        $this->assign('tdhprice4', $tdhprice4);
        $this->assign('twdhprice4', $twdhprice4);


		$this->display();	
	}
	
		
    /**
     * 兑换管理
     * @author
     */
    public function exchange(){
        //当前管理员id
        $gid = $_SESSION['onethink_admin']['user_auth']['uid'];
        $groupid = M('admin')->where(array('uid'=>$gid))->getField('groupid');
        if($groupid == 7){    //是企业分销管理员
            $uid = M('Join')->where(array('gid'=>$gid,'is_delete'=>0,'status'=>1))->getField('uid');
            $ids = M('Member')->where(array('parent_id'=>$uid))->getField('uid',true);
            $map['uid'] = array("in", $ids);
        }
		
		$start_date      = I('start_date');
		$end_date      = I('end_date');
		if($start_date && $end_date){
			$map['buy_time'] = array(array('egt',strtotime($start_date)),array('lt',strtotime($end_date)+(24*60*60)));
		}elseif($start_date  && empty($end_date)){
			$map['buy_time']  = array('egt',strtotime($start_date));
		}elseif($end_date && empty($start_date)){
			$map['buy_time']  = array('lt',strtotime($end_date)+(24*60*60));
		}		 
		 
        $uid = I('uid');
        //兑换码
        $exchange_number = I('exchange_number');
        $uid = trim($uid);
        if ($uid) {
           $map['uid'] = array('eq',$uid);
        }else{
            //搜索
            $nickname = I('nickname');
            $nickname = trim($nickname);
            if($nickname){
                    $uid = M('Member')->field("uid")->where("nickname LIKE '%$nickname%'")->select();
                if(is_array($uid)){
                    foreach($uid as $b){
                        $newuid[] = $b['uid'];    
                    }
                    $map['uid'] = array('in',$newuid);    
                }else{ 
                    $map['uid'] = array('neq',0);
                } 
            }else{   
                     $map['uid'] = array('neq',0);
             }
        }
        //print_r($map);exit;
        if(!empty($exchange_number)){
            $map['exchange_number'] = $exchange_number;
        }
        if (!empty($_GET['goods'])) {
            $_GET['goods'] = htmlspecialchars(trim($_GET['goods']));
            $goodsIdList = M()->query("SELECT id FROM ewshop_document WHERE title LIKE '%{$_GET['goods']}%'");
            if ($goodsIdList) {
                foreach ($goodsIdList as $k => $v) {
                    $gList[] = $v['id'];
                }
                $map['goods_id'] = array('in', $gList);
            } else {
                $map['goods_id'] = '';
            }
        }

        $map['utype'] = 1;
        $map['is_virtual'] = 0;
		$dhprice =0;	
		$allprice =0;	
        $list = $this->lists('WinExchange', $map, "buy_time desc");
	
//        foreach ($list as $key => $val) {
//            $list[$key]['orderinfo'] = M('WinOrder')->where("id = {$val['order_id']} and status = 1")->find();
//            $list[$key]['nickname'] = M('Member')->where("uid='$val[uid]'")->getField("nickname");
//            $list[$key]['goods_title'] = M('Document')->getFieldById($list[$key]['orderinfo']['goods_id'], 'title');
//			$price = M('Document')->getFieldById($list[$key]['orderinfo']['goods_id'], 'price');
//			if($price==1){
//				$list[$key]['allprice'] = $list[$key]['orderinfo']['num'] * 50;
//			}elseif($price==2){
//				$list[$key]['allprice'] = $list[$key]['orderinfo']['num'] * 100;
//			}
//            $list[$key]['is_exchange_val'] = ($val['is_exchange'] == 1) ? '已兑换' : '未兑换';
//			if($val['is_exchange'] == 1){
//				$dhprice += $list[$key]['allprice'];//已经兑奖金额
//			}
//			$allprice += $list[$key]['allprice'];//中奖总金额
//			
//        }

        foreach ($list as $key => $val) {
             $orders[] = $val['order_id'];
             $uids[] = $val['uid'];
             $goods[] = $val['goods_id'];
             
            $list[$key]['is_exchange_val'] = ($val['is_exchange'] == 1) ? '已兑换' : '未兑换';
			if($val['is_exchange'] == 1){
				$wdhprice += $list[$key]['allprice'];//未兑奖金额
			}
			$allprice += $list[$key]['allprice'];//中奖总金额
        }
              $omap['id'] = array('in',$orders);
              $omap['status'] = array('eq',1);
              $orderarr = M('WinOrder')->where($omap)->getField('id,utype,goods_id,period,num,lottery_time',true);
              $umap['uid'] = array('in',$uids);
              $users = M('Member')->where($umap)->getField("uid as id,nickname");
              $gmap['id'] = array('in',$goods);
              $goodsin= M('Document')->where($gmap)->getField('id,title,price');
           
          foreach ($list as $key => $val) {
            $list[$key]['orderinfo'] = $orderarr[$val['order_id']];
            $list[$key]['nickname'] = $users[$val['uid']];
            $list[$key]['goods_title'] = $goodsin[$val['goods_id']]['title'];
            if($goodsin[$val['id']]['price']==1){
				$list[$key]['allprice'] = $list[$key]['orderinfo']['num'] * 50;
			}elseif($goodsin[$val['id']]['price']==2){
				$list[$key]['allprice'] = $list[$key]['orderinfo']['num'] * 100;
			}
            $allprice += $list[$key]['allprice'];//中奖总金额
        }
        int_to_string($list);
        $this->assign('_list', $list);

		//$this->assign('dhprice', $dhprice);//兑换总金额
		//$this->assign('allprice', $allprice);//中奖总金额
        $this->meta_title = '兑换管理';
        $this->display();
    }

    /**
     * 设置成已兑换
     * @author
     */
    public function setExchanged(){
        $id = I('get.id');
        (empty($id)) ? $this->error('信息不存在') : '';
        $res = M('WinExchange')->where("id = {$id}")->setField('is_exchange',1);
        if($res){
            $this->success('设置成功');
        }else{
            $this->error('设置失败');
        }
    }

    /**
     * 添加备注
     * @author
     */
    public function addRemarks(){
        if(IS_POST){
            $id = $_POST['id'];
            $data['remarks'] = $_POST['remarks'];
            $res = M('WinExchange')->where("id = {$id}")->save($data);
            if($res){
                $this->success('添加成功',U('exchange'));
            }else{
                $this->error('添加失败');
            }
        }else{
            $id = I('get.id');
            (empty($id)) ? $this->error('信息不存在') : '';
            $info = M('WinExchange')->where("id = {$id}")->find();
            $this->assign('info', $info);
            $this->meta_title = '兑换管理';
            $this->display();
        }

    }




    /**
     * 开奖码管理
     * @author
     */
    public function code(){
        $map['code'] = array('neq',0);
        $list = $this->lists('WinCode', $map, "time desc");
//        foreach ($list as $key => $val) {
//            $list[$key]['orderinfo'] = M('WinOrder')->where("id = {$val['order_id']}")->find();
//            $list[$key]['nickname'] = M('Member')->where("uid='$val[uid]'")->getField("nickname");
//            $list[$key]['goods_title'] = M('Document')->getFieldById($list[$key]['orderinfo']['goods_id'], 'title');
//            $list[$key]['is_exchange_val'] = ($val['is_exchange'] == 1) ? '已兑换' : '未兑换';
//        }
        int_to_string($list);

        $this->assign('_list', $list);
        $this->meta_title = '开奖码管理';
        $this->display();
    }


    public function recharge(){
        //当前管理员id
        $gid = $_SESSION['onethink_admin']['user_auth']['uid'];
        $groupid = M('admin')->where(array('uid'=>$gid))->getField('groupid');
        if($groupid == 7){    //是企业分销管理员
            $uid = M('Join')->where(array('gid'=>$gid,'is_delete'=>0,'status'=>1))->getField('uid');
            $ids = M('Member')->where(array('parent_id'=>$uid))->getField('uid',true);
            $map['uid'] = array("in", $ids);
        }

        $nickname = I('nickname');
        $nickname = trim($nickname);
        if ($nickname) {
            $uid = M('Member')->field("uid")->where("nickname LIKE '%$nickname%'")->select();
            $newuid = "";
            if ($uid) {
                foreach ($uid as $b) {
                    $newuid[] = $b['uid'];
                }
                $map['uid'] = array("in", $newuid);
            } else {
                $map['uid'] = '';
            }
        }
        $map['status'] = 1;
        $list = $this->lists('RechargeOrder', $map, "create_time desc");
        foreach ($list as $key => $val) {
            $list[$key]['nickname'] = M('Member')->where("uid='$val[uid]'")->getField("nickname");
            $list[$key]['phone'] = M('UcenterMember')->where("id='$val[uid]'")->getField("username");
            $list[$key]['pay_status'] = $val['status'] == 1 ? '已支付' : '未支付';
        }
        int_to_string($list);

        $this->assign('_list', $list);
        $this->meta_title = '充值列表';
        $this->display();


    }




}
