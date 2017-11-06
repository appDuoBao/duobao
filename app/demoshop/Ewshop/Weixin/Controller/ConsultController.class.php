<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com  All rights reserved.
// +----------------------------------------------------------------------
// |  Author: ewangtx <www@ewangtx.com> 
// +----------------------------------------------------------------------

namespace Weixin\Controller;
use Think\Controller;

/**
 * 前台首页控制器
 * 
 */
class ConsultController extends HomeController {
	public function index() {  
		if(!is_login()){
		$this->error( "您还没有登陆",U("User/login") );	
		}else{
		$this->meta_title = '首页';
		$this->display();
		}
	}	
	public function first(){	
	
		$User = M('sphere');
		$list = $User->select();
		$this->assign('list',$list);
		$this->meta_title = '咨询帮';
		$this->display();
		}
		public function two(){
		$this->meta_title = '咨询帮';
		$this->display();
	}
	
	public function add(){
    $Dao = M("consult");
    $data["title"] = I('title');
    $data["content"] = I('content');
    $data["money"] = I('money');
    $data["time"] =time();
	$member=D("member");
	$uid=$member->uid();
	$data['uid']=$uid;
    if($lastInsId = $Dao->add($data)){
		$this->display();
		
    } else {
        $this->error('数据写入错误！');
    }

	}
	
}