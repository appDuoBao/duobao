<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------
namespace Control\Controller;

/**
 * 表单提交
 * 我要加盟、我要购买
 */
class FormController extends ControlController {

	//我要加盟列表
  public function joinlist(){
   if(IS_GET){ 
		 $title=trim(I('get.title'));
		 if($title){
			 $map['name'] = array('like',"%{$title}%");
		 }

		 //$list  =  M("Ad")->where($map)->field(true)->order('px ASC ,id desc')->select();
		 $list = $this->lists('FormJoin', $map,'create_time desc ,id desc');
	}else{ 
		 $list = $this->lists('FormJoin', $map,'create_time desc ,id desc');
	}
	
	$this->assign('list', $list);
		// 记录当前列表页的cookie
	Cookie('__forward__',$_SERVER['REQUEST_URI']);
	
	$this->meta_title = '首页分类推广管理';
	
	$this->display();
  }

	//我要购买列表
	 public function buylist(){
		if(IS_GET){ 
			 $title=trim(I('get.title'));
			 if($title){
				 $map['name'] = array('like',"%{$title}%");
			 }
	
			 //$list  =  M("Ad")->where($map)->field(true)->order('px ASC ,id desc')->select();
			 $list = $this->lists('FormBuy', $map,'create_time desc ,id desc');
		}else{ 
			 $list = $this->lists('FormBuy', $map,'create_time desc ,id desc');
		}
		$this->assign('list', $list);
			// 记录当前列表页的cookie
		Cookie('__forward__',$_SERVER['REQUEST_URI']);
		
		$this->meta_title = '首页分类推广管理';
		
		$this->display();
	  }
	
	
	//我要加盟删除
	 public function join_del(){
		   if(IS_POST){
				$ids = I('post.id');
				$order = M("FormJoin");
				if(is_array($ids)){
					 foreach($ids as $id){
						 $order->where("id='$id'")->delete();
					}
				}
			   $this->success("删除成功！");
			}else{
				$id = I('get.id');
				$db = M("FormJoin");
				$status = $db->where("id='$id'")->delete();
				if ($status){
					$this->success("删除成功！");
				}else{
					$this->error("删除失败！");
				}
			} 
	}
	
	
	//我要购买删除
	 public function buy_del(){
		   if(IS_POST){
				$ids = I('post.id');
				$order = M("FormBuy");
				if(is_array($ids)){
					 foreach($ids as $id){
						 $order->where("id='$id'")->delete();
					}
				}
			   $this->success("删除成功！");
			}else{
				$id = I('get.id');
				$db = M("FormBuy");
				$status = $db->where("id='$id'")->delete();
				if ($status){
					$this->success("删除成功！");
				}else{
					$this->error("删除失败！");
				}
			} 
		}




}
