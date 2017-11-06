<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------

namespace Weixin\Controller;
use Think\Controller;

/**
 * 前台微信页面控制器
 */
class WeixinController extends HomeController {

	/* 微信图文页面 */
	public function show(){
		$id = $_GET['id'];
		if($id){
			$news = M("Wxreply")->where(array("id"=>$id))->find();
			$this->assign('news',$news);
		}
		$this->meta_title = '微信图文页面';
		$this->display();		
	}

}
