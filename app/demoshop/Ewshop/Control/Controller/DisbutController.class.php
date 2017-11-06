<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Controller;

/**
 * 后台订单控制器
  * @author 一网天行 <www@ewangtx.com>
 */
class DisbutController extends ControlController {

    /**
     * 订单管理
     * author 一网天行 <www@ewangtx.com>
     */
      public function index(){
        /* 查询条件初始化 */
	
        $map  = array();


			$list = $this->lists('Disbut', $map,'id desc');
			
			foreach($list as $k=>$v){
				$list[$k]['username'] = M('member')->getFieldByUid($v['uid'],'nickname');
				$list[$k]['disbutmoney'] = $v['disbutmoney']+$v['pdisbutmoney'];
			}

        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '分销记录管理';
        $this->display();
    }

	/* 添加编辑公司 */
    public function edit($id = null){
		$disbut = D('Disbut');
			$info = $id ? $disbut->info($id) : '';
			$info['username'] = M('member')->getFieldByUid($info['uid'],'nickname');
			$info['pusername'] = M('member')->getFieldByUid($info['pid'],'nickname');
			$info['ppusername'] = M('member')->getFieldByUid($info['ppid'],'nickname');
			$this->assign('info',       $info);
			$this->meta_title = $id ? '查看分销记录' : '不存在此记录';
			$this->display();		
    }

    /* 新增分类 */
    public function add(){
        $adp = D('Adposition');
		$list = $adp->select();
        $this->assign('list',$list);//广告位列表
		$ad = D('Ad');
        if(IS_POST){ //提交表单
            if(false !== $ad->update()){
                $this->success('新增成功！', U('index'));
            } else {
                $error = $ad->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $this->meta_title = '新增广告';
            $this->display();
        }
    }


 public function del(){
       if(IS_POST){
             $ids = I('post.id');
            $order = M("ad");
			
            if(is_array($ids)){
                             foreach($ids as $id){
		
                             $order->where("id='$id'")->delete();
						
                }
            }
           $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $db = M("ad");
            $status = $db->where("id='$id'")->delete();
            if ($status){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }
	
	
  /**
     * 分销商管理
     * author 一网天行 <www@ewangtx.com>
     */
      public function member(){
        /* 查询条件初始化 */
	
        $map['isdis']  = 1;


			$list = $this->lists('Member', $map,'num desc');
			

        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '分销商管理';
        $this->display();
    }

	/* 编辑分销商 */
    public function memberedit($id = null){
        $member = D('Member');
        if(IS_POST){ //提交表单
			$map['uid'] = $_POST['id'];
			$data['disnum'] = $_POST['disnum'];
            if(false !== $member->where($map)->save($data)){
                $this->success('编辑成功！', U('member'));
            } else {
                $error = $member->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $info = $id ? $member->info($id) : '';
            $this->assign('info',       $info);
            $this->meta_title = '编辑分销商';
            $this->display();
        }	
    }	
	
	/* 编辑分销商下的分销会员 */
    public function child($id = null){
		$id = I('get.id');
        $member = D('Member');
		$map['isdis'] = 0;
		$map['puid'] = array('eq',$id);
		$list = $this->lists('Member', $map,'puid asc, num desc');
		foreach($list as $k=>$v){
			$list[$k]['username'] = $member->getFieldByUid($v['puid'],'nickname');
		}
		$this->assign('list', $list);
		$this->meta_title = '编辑分销商';
        $this->display();
		
    }
	
	/* 编辑分销会员 */
    public function childedit($id = null){
        $member = D('Member');
        if(IS_POST){ //提交表单
			$map['uid'] = $_POST['id'];
			$data['disnum'] = $_POST['disnum'];
            if(false !== $member->where($map)->save($data)){
				$rootid = getuserrootid($map['uid']);
                $this->success('编辑成功！', U('/Control/Disbut/child/id/'.$rootid));
            } else {
                $error = $member->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $info = $id ? $member->info($id) : '';
			$info['username'] = $member->getFieldByUid($info['puid'],'nickname');
            $this->assign('info',       $info);
            $this->meta_title = '编辑分销商';
            $this->display();
        }	
    }	
	
	/* 分销会员等级 */
    public function dislevel(){
        /* 查询条件初始化 */
        $map  = array();

		    $list = $this->lists('dislevel', $map,'num asc');

        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '分销等级管理';
        $this->display();
    }
	
    /* 编辑分销 */
    public function leveledit($id = null){
        $adp = D('dislevel');
        if(IS_POST){ //提交表单
            if(false !== $adp->update()){
                $this->success('编辑成功！', U('dislevel'));
            } else {
                $error = $adp->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            /* 获取广告位信息 */
            $info = $id ? $adp->info($id) : '';
            $this->assign('info',    $info);
            $this->meta_title = '编辑默认分销';
            $this->display();
        }
    }
	
	/* 新增默认分销 */
    public function leveladd(){
        $adp = D('dislevel');
        if(IS_POST){ //提交表单
            if(false !== $adp->update()){
                $this->success('新增成功！', U('dislevel'));
            } else {
                $error = $adp->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $this->meta_title = '新增分销';
            $this->display();
        }
    }
	
	/* 删除分销*/
	public function leveldel(){
       if(IS_POST){
            $ids = I('post.id');
            $adp = M("dislevel");
			
            if(is_array($ids)){
				 foreach($ids as $id){
					 $adp->where("id='$id'")->delete();	
                 }
            }
           $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $db = M("dislevel");
            $status = $db->where("id='$id'")->delete();
            if ($status){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }

}