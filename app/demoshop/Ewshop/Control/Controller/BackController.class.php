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
class BackController extends ControlController {

    /**
     * 退货申请列表
     * author 一网天行 <www@ewangtx.com>
     */
    public function index(){
		$status = $_GET['status'];
		if(empty($status)){
			$status = 0;//申请退货
		}
        /* 查询条件初始化 */
       $map  = array('status' =>$status);
       $list = $this->lists('backlist', $map,'id desc');
       $this->assign('list', $list);
       // 记录当前列表页的cookie
       Cookie('__forward__',$_SERVER['REQUEST_URI']);
       if($status=0){
		   $this->meta_title = '申请退货';
		   $this->display('aindex');
	   }elseif($status=2){
		   $this->meta_title = '同意退货';
	   }elseif($status=3){
		    $this->meta_title= '拒绝退货';		   
	   }
	   elseif($status=5){
		    $this->meta_title= '已退货';		   
	   }
       
       $this->display();
	}
	
	/**
     * 退货申请列表
     * author 一网天行 <www@ewangtx.com>
     */
    public function aindex(){
		$status = $_GET['status'];
		if(empty($status)){
			$status = 0;//申请退货
		}
        /* 查询条件初始化 */
       $map  = array('status' =>$status);
       $list = $this->lists('backlist', $map,'id desc');
       $this->assign('list', $list);
       // 记录当前列表页的cookie
       Cookie('__forward__',$_SERVER['REQUEST_URI']);
       if($status=0){
		   $this->meta_title = '申请退货';
	   }elseif($status=2){
		   $this->meta_title = '同意退货';
	   }elseif($status=3){
		    $this->meta_title= '拒绝退货';		   
	   }
	   elseif($status=5){
		    $this->meta_title= '已退货';		   
	   }
       
       $this->display();
	}
	
    /**
     * 地址信息
     * @author 一网天行 <www@ewangtx.com>
     */
    public function add(){
		  if(IS_POST){
			$id=$_POST['id'];
			$data['tool']=$_POST['tool'];
			$data['backname']=$_POST['backname'];
			$data['address']=$_POST['address'];
			$data['contact']=$_POST['contact'];
			$data['backinfo']=$_POST['backinfo'];
			$data['update_time']= NOW_TIME;
			$status=$_POST['status'];
			$list=M('Backlist');
			$map['id'] = $id;
			$row=$list->where($map)->save($data);
			//var_dump($row);
			$result=M('backlist')->where($data)->setField('status',$status);
			$rel=M('shoplist')->where($data)->setField('status',$status);
			if($row){
				$this->success('提交成功',U('Back/index'));
			  
			}
		 }
    }

    /**
     * 编辑
     * @author 一网天行 <www@ewangtx.com>
     */
    public function edit($id = 0){
  if(IS_POST){
            $Form =  D('backlist');
       
            if($_POST["id"]){
				$data['id']=$_POST["id"];
				$status=$_POST['sex'];
				if($status=='2'){
				$result=M('backlist')->where($data)->setField('status',$status);//更新退货状态
				}else if($status=='3'){
					$result=M('backlist')->where($data)->setField('status',$status);//更新退货状态
				}else if($status=='5'){
					$result=M('backlist')->where($data)->setField('status',$status);//更新退货状态
				}
                if($result){
                    //记录行为
                    action_log('update_backlist', 'backlist', $data['id'], UID);
                    $this->success('更新成功', Cookie('__forward__'));
                } else {
                    $this->error('更新失败,退货单');
                }
            } else {
                $this->error($Config->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('backlist')->find($id);

            $list=M('backlist')->where("shopid='$id'")->select();

            if(false === $info){
                $this->error('获取订单信息错误');
            }
			$backname=M("backlist")->where($id)->getField('backname');
			$this->assign('backname',$backname);
            $this->assign('list', $list);

			 $this->assign('info', $info);
            $this->meta_title = '编辑订单';
            $this->display();
        }
    }
	/**
     * 编辑
     * @author 一网天行 <www@ewangtx.com>
     */
    public function agreed($id = 0){
         if(IS_POST){
            $Form =  D('backlist');
            if($_POST["id"]){
				$data['id']=$_POST["id"];
				$status=$_POST['sex'];
				if($status=='2'){
				    $result=M('backlist')->where($data)->setField('status',$status);//更新退货状
				}elseif($status=='3'){
					$result=M('backlist')->where($data)->setField('status',$status);//更新退货状态
				}
                if($result){
                    //记录行为
                    action_log('update_backlist', 'backlist', $data['id'], UID);
                    $this->success('更新成功', Cookie('__forward__'));
                } else {
                    $this->error('更新失败,退货单');
                }
            } else {
                $this->error($Config->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('backlist')->find($id);

            $list=M('backlist')->where("shopid='$id'")->select();

            if(false === $info){
                $this->error('获取订单信息错误');
            }
			$backname=M("backlist")->where($id)->getField('backname');
			$this->assign('backname',$backname);
            $this->assign('list', $list);

			 $this->assign('info', $info);
            $this->meta_title = '编辑订单';
            $this->display();
        }
    }
	
	/**
     * 编辑
     * @author 一网天行 <www@ewangtx.com>
     */
    public function answer($id = 0){
  if(IS_POST){
            $Form =  D('backlist');
       
            if($_POST["id"]){
				$data['id']=$_POST["id"];
				$status=$_POST['sex'];
				if($status=='2'){
				$result=M('backlist')->where($data)->setField('status',$status);//更新退货状态
				}else if($status=='3'){
					$result=M('backlist')->where($data)->setField('status',$status);//更新退货状态
				}else if($status=='5'){
					$result=M('backlist')->where($data)->setField('status',$status);//更新退货状态
				}
                if($result){
                    //记录行为
                    action_log('update_backlist', 'backlist', $data['id'], UID);
                    $this->success('更新成功', Cookie('__forward__'));
                } else {
                    $this->error('更新失败,退货单');
                }
            } else {
                $this->error($Config->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('backlist')->find($id);

            $list=M('backlist')->where("shopid='$id'")->select();

            if(false === $info){
                $this->error('获取订单信息错误');
            }
			$backname=M("backlist")->where($id)->getField('backname');
			$this->assign('backname',$backname);
            $this->assign('list', $list);

			 $this->assign('info', $info);
            $this->meta_title = '编辑订单';
            $this->display();
        }
    }
	
	//判断是否同意
    public function set_status(){
        if(IS_POST){
            $Form =  D('backlist');
            if($_POST["id"]){
				$data['id']=$_POST["id"];
				$status=$_POST['sex'];
				if($status=='2'){
				    $result=M('backlist')->where($data)->setField('status',$status);//更新退货状态
				}else if($status=='3'){
					$result=M('backlist')->where($data)->setField('status',$status);//更新退货状态
				}else if(($status=='5')){
					$result=M('backlist')->where($data)->setField('status',$status);//更新退货状态
				}
                if($result){
                    M('backlist')->where($data)->setField('update_time',NOW_TIME);
                    //记录行为
                    action_log('update_backlist', 'backlist', $data['id'], UID);
                    $this->success('更新成功', Cookie('__forward__'));
                } else {
                    $this->error('更新失败,退货单');
                }
            } else {
                $this->error($Config->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('backlist')->find($id);

            $list=M('backlist')->where("shopid='$id'")->select();

            if(false === $info){
                $this->error('获取订单信息错误');
            }
			$backname=M("backlist")->where($id)->getField('backname');
			$this->assign('backname',$backname);
            $this->assign('list', $list);

			 $this->assign('info', $info);
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
            $order = M("backlist");
			
            if(is_array($ids)){
                             foreach($ids as $id){
		
                             $order->where("id='$id'")->delete();
						
                }
            }
           $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $db = M("backlist");
            $status = $db->where("id='$id'")->delete();
            if ($status){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }
}