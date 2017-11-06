<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | author 一网天行 <www@ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Controller;

/**
 * 后台订单控制器
  * @author 一网天行 <www@ewangtx.com>
 */
class OrderController extends ControlController {

    /**
     * 所有订单管理
	 * status订单状态说明：-1：待付款  1：待发货 2：待发货 3：已完成 4：已取消
	 * ispay支付状态说明：-1：货到付款 1：在线支付-未支付  2:在线支付-已支付
     * author 一网天行 <www@ewangtx.com>
     */
    public function index(){
        /* 查询条件初始化 */
		 $map  = array('id'=>array('gt',0));
	 if(IS_GET){ 
			$orderid=trim(I('get.orderid'));
		    $map['orderid'] = array('like',"%{$orderid}%");
			$list = $this->lists('Order', $map,'id desc');
		} else { 
		    $list = $this->lists('Order', $map,'id desc');
	 	}

        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '订单管理';
        $this->display();
    }
	
	
    /**
     * 等待付款订单管理
	 * status订单状态说明：-1：待付款  1：待发货 2：待收货 3：已完成
	 * ispay支付状态说明：-1：货到付款 1：在线支付-未支付  2:在线支付-已支付
     * author 一网天行 <www@ewangtx.com>
     */
    public function dfk(){
        /* 查询条件初始化 */
		$status= -1;
		$this->assign('status', $status);
       $map  = array('status' => $status);
      	 if(IS_GET){ 
			$orderid=trim(I('get.orderid'));
		    $map['orderid'] = array('like',"%{$orderid}%");
		   $list = $this->lists('Order', $map,'id desc');
		} else { 
		    $list = $this->lists('Order', $map,'id desc');
	 	}
		
        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '待付款订单管理';
        $this->display('index');
    }
	
    /**
     * 待发货订单管理
	 * status订单状态说明：-1：待付款  1：待发货 2：待收货 3：已完成
	 * ispay支付状态说明：-1：货到付款 1：在线支付-未支付  2:在线支付-已支付
     * author 一网天行 <www@ewangtx.com>
     */
    public function dfh(){
		$status= 1;
		$this->assign('status', $status);
        $map  = array('status' => $status);
      	if(IS_GET){ 
			$orderid=trim(I('get.orderid'));
		    $map['orderid'] = array('like',"%{$orderid}%");
		   $list = $this->lists('Order', $map,'id desc');
		} else { 
		    $list = $this->lists('Order', $map,'id desc');
	 	}
		
        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '待发货订单管理';
        $this->display('index');
    }
    /**
     * 待收货订单管理
	 * status订单状态说明：-1：待付款  1：待发货 2：待收货 3：已完成
	 * ispay支付状态说明：-1：货到付款 1：在线支付-未支付  2:在线支付-已支付
     * author 一网天行 <www@ewangtx.com>
     */
    public function dsh(){
		$status= 2;
		$this->assign('status', $status);
        $map  = array('status' => $status);
      	 if(IS_GET){ 
			$orderid=trim(I('get.orderid'));
		    $map['orderid'] = array('like',"%{$orderid}%");
		   $list = $this->lists('Order', $map,'id desc');
		} else { 
		    $list = $this->lists('Order', $map,'id desc');
	 	}
		
        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '待收货订单管理';
        $this->display('index');
    }	
    /**
     * 已完成订单管理
	 * status订单状态说明：-1：待付款  1：待发货 2：待收货 3：已完成
	 * ispay支付状态说明：-1：货到付款 1：在线支付-未支付  2:在线支付-已支付
     * author 一网天行 <www@ewangtx.com>
     */
    public function ywc(){
		$status= 3;
		$this->assign('status', $status);
        $map  = array('status' => $status);
      	 if(IS_GET){ 
			$orderid=trim(I('get.orderid'));
		    $map['orderid'] = array('like',"%{$orderid}%");
		    $list = $this->lists('Order', $map,'id desc');
		} else { 
		    $list = $this->lists('Order', $map,'id desc');
	 	}
		
        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '已完成订单管理';
        $this->display('index');
    }		

    /**
     * 已取消订单管理
	 * status订单状态说明：-1：待付款  1：待发货 2：待收货 3：已完成 4：已取消
	 * ispay支付状态说明：-1：货到付款 1：在线支付-未支付  2:在线支付-已支付
     * author 一网天行 <www@ewangtx.com>
     */
    public function yqx(){
		$status= 4;
		$this->assign('status', $status);
        $map  = array('status' => $status);
      	if(IS_GET){ 
		   $orderid=trim(I('get.orderid'));
		   $map['orderid'] = array('like',"%{$orderid}%");
		   $list = $this->lists('Order', $map,'id desc');
		} else { 
		    $list = $this->lists('Order', $map,'id desc');
	 	}
		
        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '已取消订单管理';
        $this->display('index');
    }
		
		
	//订单导出
	public function orderexport() {
		$status=trim(I('get.status'));
		if($status){
			$map = array('status' => $status);
		}else{
			$map = array('1=1');
		}
		//$orderlist = $this->lists('Order', $map,'id desc');//只能导出第一页的
		$orderlist =  M("Order")->where($map)->select(); 
		//在此用$list模拟要导出的数据源(一般是从数据库中读取)
		$list=array();
		$i=0;
		foreach($orderlist as $k=>$val)
		{
			$list[$i]['orderid'] = $val['orderid'];//订单号
			$list[$i]['pricetotal'] = $val['pricetotal'];//订单金额
			if($val['status']==-1){
				$orderstatus = '待付款';
			}elseif($val['status']==1){
				$orderstatus = '待发货';
			}elseif($val['status']==2){
				$orderstatus = '待收货';
			}elseif($val['status']==3){
				$orderstatus = '已完成';
			}elseif($val['status']==4){
				$orderstatus = '已取消';
			}
			$list[$i]['status'] = $orderstatus;//订单状态
			if($val['ispay']==-1){
				$orderispay = '货到付款';
			}elseif($val['ispay']==1){
				$orderispay = '在线支付-未支付';
			}elseif($val['ispay']==2){
				$orderispay = '在线支付-已支付';
			}
			$list[$i]['ispay'] = $orderispay;//支付状态
			$list[$i]['create_time'] = date("Y-m-d H:i:s", $val['create_time']);//下单时间
			$username = M("UcenterMember")->where('id='.$val['uid'])->getField('username');//获取会员信息
			$list[$i]['uid'] = $username;//会员账号
			$list[$i]['phone'] = $val['phone'];//联系电话
			$list[$i]['realname'] = $val['realname'];//收货人姓名
			$list[$i]['address'] = $val['address'];//详细地址
			$list[$i]['tool'] = $val['tool'];//快递名称
			$list[$i]['toolid'] = $val['toolid'];//快递单号
			$i++;
		}
		//设置要导出excel的表头
		$title=array('订单号','订单金额','订单状态','支付状态','下单时间','会员账号','联系电话','收货人姓名','详细地址','快递名称','快递单号');	
		//导出文件名称
		$filename = '订单数据'.date("Y-m-d H:i:s", time());
		//进行导出
		exportExcel($list,$filename,$title);
	}		
		
    /**
     * 新增订单
     * @author 一网天行 <www@ewangtx.com>
     */
    public function add(){
        if(IS_POST){
			$kuaidi = D('Kuaidi');
		$list = $kuaidi->select();
        $this->assign('list',$list);//地区列表
            $Config = D('order');
            $data = $Config->create();
            if($data){
                if($Config->add()){
                    S('DB_CONFIG_DATA',null);
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Config->getError());
            }
        } else {
			$tag = $this->ordersn();
            $this->meta_title = '新增订单';
            $this->assign('info',null);
			$this->assign('tag',$tag);
            $this->display('edit');
			
        }
    }
	
	/**
	 * 编辑订单
	 * @author 一网天行 <www@ewangtx.com>
	 */
	public function edit($id = 0){
		if(IS_POST){
			 $kuaidi = D('kuaidi');
		$kuaidilist = $kuaidi->select();

		 $this->assign('kuaidilist',$kuaidilist);//地区列表
			$Form = D('order');
			if($_POST["id"]){ 
			$kuaidi = D('kuaidi');
		$kuaidilist = $kuaidi->select();
            foreach($kuaidilist as $k=>$v){
			 $v['type']=$_POST["type"];
		 }
		 $this->assign('kuaidilist',$kuaidilist);//地区列表
				$id=$_POST["id"];
				$Form->create();
				$result=$Form->where("id='$id'")->save();
				if($result){
					//记录行为
					action_log('update_order', 'order', $data['id'], UID);
					$this->success('更新成功', Cookie('__forward__'));
				} else {
					$this->error('更新失败'.$id);
				}
			} else {
				$this->error($Config->getError());
			}
		} else {
			$info = array();
			 $kuaidi = D('kuaidi');
		$kuaidilist = $kuaidi->select();
		 foreach($kuaidilist as $k=>$v){
			 $v['type']=$_POST["type"];
		 }
		 $this->assign('kuaidilist',$kuaidilist);//地区列表
			/* 获取数据 */
			$info = M('order')->find($id);
			$detail= M('order')->where("id='$id'")->select();
			$list=M('shoplist')->where("orderid='$id'")->select();
			$vname=M('Member')->getFieldByUid($info['uid'],'nickname');
			$this->assign('vname', $vname);
			if(false === $info){
				$this->error('获取订单信息错误');
			}
			$this->assign('list', $list);
			$tag = $info['orderid'];
			$this->assign('tag',$tag);
			$this->assign('detail', $detail);
			$this->assign('info', $info);
			$this->assign('a', $orderid);
			$this->meta_title = '编辑订单';
			$this->display();
		}
	}

  
   /**
     * 删除订单
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function del(){
       if(IS_POST){
            $ids = I('post.id');
            $order = M("order");
            if(is_array($ids)){
				foreach($ids as $id){
					 $order->where("id='$id'")->delete();
					 $shop=M("shoplist");
					 $shop->where("orderid='$id'")->delete(); 
                }
            }
           $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $db = M("order");
            $status = $db->where("id='$id'")->delete();
            if ($status){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }

	 /**
	 * 订单发货
	 * @author 一网天行 <www@ewangtx.com>
	 */
	public function send($id = 0){
		if(IS_POST){
			$Form = D('order');
			$user=session('user_auth');
			$uid=$user['uid'];
			if($_POST["id"]){ 
				$id=$_POST["id"];
				
				$Form->create();
				$user=session('user_auth');
				$uid=$user['uid'];
				$Form->assistant = $uid;
				$Form->update_time = NOW_TIME;
				$Form->status="2";
				$orderid=M('order')->where("id='$id'")->getField("orderid");
				$result=$Form->where("id='$id'")->save();
				
				//根据订单id获取购物清单
				$del=M("shoplist")->where("orderid='$id'")->select();
				foreach($del as $k=>$val)
				{
					//获取购物清单数据表产品id，字段id
					$byid=$val["id"];
					$goodid=$val["goodid"];
					//销量加1 库存减1
					$sales= M('document')->where("id='$goodid'")->setInc('sale');
					$stock= M('document_product')->where("id='$goodid'")->setDec('stock');
					$data['status']=2;
					$shop=M("shoplist");
					M("shoplist")->where("id='$byid'")->save($data);
				}
	
				if($result){
					//记录行为
					action_log('update_order', 'order', $data['id'], UID);
					$this->success('更新成功', Cookie('__forward__'));
				} else {
					$this->error('更新失败'.$id);
				}
			} else {
				$this->error($Config->getError());
			}
		} else {
			$info = array();
			/* 获取数据 */
			$info = M('order')->find($id);
			$detail= M('order')->where("id='$id'")->select();
			$list=M('shoplist')->where("orderid='$id'")->select();
	
			if(false === $info){
				$this->error('获取订单信息错误');
			}
			$this->assign('list', $list);
			$this->assign('detail', $detail);
			$this->assign('info', $info);
			
			$this->meta_title = '订单发货';
			$this->display();
		}
	}

	 /**
	 * 确认收货
	 * @author 一网天行 <www@ewangtx.com>
	 */
    public function complete($id = 0){
		if(IS_POST){
			$Form = D('order');
			$user=session('user_auth');
			$uid=$user['uid'];
			if($_POST["id"]){ 
				$id=$_POST["id"];
				$Form->create();
				$Form->assistant = $uid;
				$Form->update_time = NOW_TIME;
				$Form->status="3";
				$result=$Form->where("id='$id'")->save();
				//根据订单id获取购物清单,设置商品状态为已完成.，status=3
				$del=M("shoplist")->where("orderid='$id'")->select();
				
				foreach($del as $k=>$val)
				{
					//获取购物清单数据表产品id，字段id
					$byid=$val["id"];
					$data['iscomment']=1;
					$data['status']=3;
					$shop=M("shoplist");
					$shop->where("id='$byid'")->save($data);
				}
				if($result){
					//记录行为
					action_log('update_order', 'order', $data['id'], UID);
					$this->success('更新成功', Cookie('__forward__'));
				} else {
					$this->error('更新失败'.$id);
				}
			}else {
				$this->error($Config->getError());
			}
		}else {
			$info = array();
			/* 获取数据 */
			$info = M('order')->find($id);
			$detail= M('order')->where("id='$id'")->select();
			$list=M('shoplist')->where("orderid='$id'")->select();
			
			if(false === $info){
				$this->error('获取订单信息错误');
			}
			$this->assign('list', $list);
			$this->assign('detail', $detail);
			$this->assign('info', $info);
			$this->meta_title = '订单发货';
			$this->display();
		}
    }

	//生成订单号
	function ordersn(){
		$yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		$orderSn = $yCode[((intval(date('Y')) - 2015)+26)%26] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%04d%02d', rand(1000, 9999),rand(0,99));
		return $orderSn;
	}	
}