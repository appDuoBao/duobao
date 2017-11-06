<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------

namespace Weixin\Model;
use Think\Model;
use Think\Page;

/**
 * 文档基础模型
 */
class FavortableModel extends Model{

   
public  function getfavor() {
	    $user=D("member");
	    $uid=$user->uid();
        $order=D("favortable");
	    $favorlist=$order->where("uid='$uid'")->select();
		return $favorlist; 
}

}
