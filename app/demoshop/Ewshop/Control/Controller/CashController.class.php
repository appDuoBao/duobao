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
class CashController extends ControlController {

    /**
     *  提现管理
     * author 一网天行 <www@ewangtx.com>
     */
      public function index(){
        /* 查询条件初始化 */
	
        $map['status']  = 0;


			$list = $this->lists('Cash', $map,'id asc');
			
			foreach($list as $k=>$v){
				$list[$k]['username'] = M('member')->getFieldByUid($v['uid'],'nickname');
			}

        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '提现审核管理';
        $this->display();
    }
	
      public function right(){
        /* 查询条件初始化 */
	
        $map['status']  = 1;


			$list = $this->lists('Cash', $map,'id asc');
			
			foreach($list as $k=>$v){
				$list[$k]['username'] = M('member')->getFieldByUid($v['uid'],'nickname');
			}

        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '成功提现记录';
        $this->display('success');
    }	
	      public function wrong(){
        /* 查询条件初始化 */
	
        $map['status']  = 2;


			$list = $this->lists('Cash', $map,'id asc');
			
			foreach($list as $k=>$v){
				$list[$k]['username'] = M('member')->getFieldByUid($v['uid'],'nickname');
			}

        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '驳回提现记录';
        $this->display();
    }	

	/* 驳回 */
    public function edit($id = null){
		$cash = D('Cash');
		 if(IS_POST){ //提交表单 
			$map['id'] = $_POST['id'];
			$memberid = M('Cash')->getFieldById($map['id'],'uid');
			$money = M('Cash')->getFieldById($map['id'],'money');
			$member=M("member")->where("uid='$memberid'")->limit(1)->find();
			$membermap['uid'] = $memberid;
			$nowmoney = $member['account']+$money;
			$memberdata['account'] = $nowmoney;
			if(false !== M('Member')->where($membermap)->save($memberdata)){
				$data['note'] = $_POST['note'];
				$data['status'] = 2;
				$data['update_time'] = time();
				if(false !== $cash->where($map)->save($data)){
					$this->success('驳回成功！', U('wrong'));
				} else {
				$error = $cash->getError();
				$this->error(empty($error) ? '驳回错误' : $error);
				}
            } else {
				$members = D('member');
                $error = $members->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }

        } else {

			$info = $id ? $cash->info($id) : '';
				
			$info['username'] = M('member')->getFieldByUid($info['uid'],'nickname');
			
			$this->assign('info',       $info);
			$this->meta_title = $id ? '查看分销记录' : '不存在此记录';
			$this->display();	
        }	
	
    }




 
	/* 成功分销商 */
    public function oksuccess($id = null){
		$id = $_GET['id'];
        $map['id'] = $_GET['id'];
		$cash = M("Cash")->where("id='$id'")->limit(1)->find();
		$walletdata['uid'] = $cash['uid'];
		$walletdata['money'] = $cash['money'];
		$walletdata['note'] = '提现';
		$walletdata['changetime'] = time();
		$walletdata['status'] = 2;
		$walletdata['cometype'] = 3;
		if(false !== M('Walletrecord')->add($walletdata)){
                $data['status'] = 1;
				$data['update_time'] = time();
				if(false !== M('Cash')->where($map)->save($data)){
					$this->success('转账成功！', U('right'));
				} else {
				$error = D('Cash')->getError();
				$this->error(empty($error) ? '转账错误' : $error);
				}				
            } else {
				$wallet = D('Cash');
                $error = $wallet->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
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