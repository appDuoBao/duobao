<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Controller;

/**
 * 后台积分商城控制器
  * @author 一网天行 <www@ewangtx.com>
 */
class CreditsController extends ControlController {

    /**
     * 
     * author 一网天行 <www@ewangtx.com>
     */
      public function index(){
        /* 查询条件初始化 */
		$title=trim(I('get.title'));
		if($title){
			$map['title'] = array('like',"%{$title}%");
		}else{
			$map  = array();
		}
		$list = $this->lists('Credits', $map,'id desc');	

        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '积分商城';
        $this->display();
    }
	
	/* 新增商品 */
    public function add(){
		$Credits = D('Credits');
        if(IS_POST){ //提交表单
            if(false !== $Credits->update()){
                $this->success('新增成功！', U('index'));
            } else {
                $error = $Credits->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $this->meta_title = '新增广告';
            $this->display();
        }
    }

	/* 编辑商品 */
    public function edit($id = null){
		$Credits = D('Credits');
		$info = $id ? $Credits->info($id) : '';
		 if(IS_POST){
			if(false !== $Credits->update()){
				$this->success('编辑成功！', U('index'));
			} else {
				$error = $Credits->getError();
				$this->error(empty($error) ? '未知错误！' : $error);
			} 
		 }else{
			$this->assign('info', $info);
			$this->meta_title = $id ? '查看记录' : '不存在此记录';
			$this->display();		 
		 }
	
    }
	
	/* 删除商品 */
	public function del(){
	   if(IS_POST){
			$ids = I('post.id');
			$Credits = M("Credits");			
			if(is_array($ids)){
				 foreach($ids as $id){		
				 $Credits->where("id='$id'")->delete();						
				}
			}
		   $this->success("删除成功！");
		}else{
			$id = I('get.id');
			$Credits = M("Credits");
			$status = $Credits->where("id='$id'")->delete();
			if ($status){
				$this->success("删除成功！");
			}else{
				$this->error("删除失败！");
			}
		} 
    }
	
     public function record(){
        /* 查询条件初始化 */
		$title=trim(I('get.title'));
		if($title){
			$map['uid'] = $title;
		}else{
			$map  = array();
		}
		$list = $this->lists('CreditsLog', $map,'id desc');	
		foreach($list as $key => $val){
			$map['id']=$val['uid'];
			$userdata = M('ucenter_member')->where($map)->find();	
			$list[$key]['username']=$userdata['username'];//执行者
			$map1['id']=$val['gid'];
			$gooddata = M('Credits')->where($map1)->find();	
			$list[$key]['title']=$gooddata['title'];//购买的课程
		}

        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '积分明细';
        $this->display();
    }

	
	
	/* 删除积分明细 */
	public function record_del(){
	   if(IS_POST){
			$ids = I('post.id');
			$CreditsLog = M("CreditsLog");			
			if(is_array($ids)){
				 foreach($ids as $id){		
				 $CreditsLog->where("id='$id'")->delete();						
				}
			}
		   $this->success("删除成功！");
		}else{
			$id = I('get.id');
			$CreditsLog = M("CreditsLog");
			$status = $CreditsLog->where("id='$id'")->delete();
			if ($status){
				$this->success("删除成功！");
			}else{
				$this->error("删除失败！");
			}
		} 
    }
	
	
      public function order(){
        /* 查询条件初始化 */
		$title=trim(I('get.title'));
		if($title){
			$map['name'] = array('like',"%{$title}%");
		}else{
			$map  = array();
		}
		$list = $this->lists('credits_form', $map,'id desc');	
		foreach($list as $key => $val){
			$map['id']=$val['uid'];
			$userdata = M('ucenter_member')->where($map)->find();	
			$list[$key]['username']=$userdata['username'];//用户
			$map1['id']=$val['gid'];
			$gooddata = M('Credits')->where($map1)->find();	
			$list[$key]['title']=$gooddata['title'];//购买的课程
		}
        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '兑换订单';
        $this->display();
    }
	
	
	
	/* 编辑订单 */
    public function order_edit($id = null){
		$CreditsForm = M('CreditsForm');
		$map['id']=$id;
		$info = $id ? $CreditsForm->where($map)->find() : '';
		 if(IS_POST){
			if(false !== $CreditsForm->update()){
				$this->success('编辑成功！', U('index'));
			} else {
				$error = $CreditsForm->getError();
				$this->error(empty($error) ? '未知错误！' : $error);
			} 
		 }else{
			$this->assign('info', $info);
			$this->meta_title = $id ? '查看记录' : '不存在此记录';
			$this->display();		 
		 }
	
    }
	
	
		/* 删除订单 */
	public function order_del(){
	   if(IS_POST){
			$ids = I('post.id');
			$CreditsForm = M("CreditsForm");			
			if(is_array($ids)){
				 foreach($ids as $id){		
				 $CreditsForm->where("id='$id'")->delete();						
				}
			}
		   $this->success("删除成功！");
		}else{
			$id = I('get.id');
			$CreditsForm = M("CreditsForm");
			$status = $CreditsForm->where("id='$id'")->delete();
			if ($status){
				$this->success("删除成功！");
			}else{
				$this->error("删除失败！");
			}
		} 
    }
}