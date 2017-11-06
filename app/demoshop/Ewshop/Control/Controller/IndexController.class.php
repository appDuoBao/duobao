<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Controller;
use User\Api\UserApi as UserApi;

/**
 * 后台首页控制器
 * @author ew_xiaoxiao <www@ewangtx.com>
 */
class IndexController extends ControlController {

    /**
     * 后台首页
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function index(){
   $damain=$_SERVER['SERVER_NAME'];
        $this->assign('data',$damain); 
	    $url="http://".$damain.__ROOT__;
        M("config")->where("name='DOMAIN'")->setField('value',$url);
  
	 $this->meta_title = '管理首页';
      
		 $this->display();
    }

   public function insert(){
	if($_POST['code']){
	  $code=$_POST['code'];
     M("config")->where('id=75')->setField('SCODE',$code);
    $ycode=M("config")->where('id=75')->getField('code');
    if($ycode){
	 $data['ycode'] = $ycode;
     $this->ajaxReturn($data); 
	  }
	  else
		  {$ycode=M("config")->where('id=75')->getField('code');

	  $data['ycode'] = $ycode;
      $this->ajaxReturn($data);
	  
	  }

	}
	else



    $this->display();
	}

}
