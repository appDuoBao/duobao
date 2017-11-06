<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_lk <liukang@ewangtx.com> <www.ewangtx.com>
// +----------------------------------------------------------------------
namespace Control\Model;
use Think\Model;

/**
 * 插件模型
 */

class WxsettingModel extends Model {

    protected $_validate = array(
        array('name','require','公众号名称必须填写', 1), 
        array('appid','require','appID接必须填写', 1), 
		array('appsecret','require','appSecret接必须填写', 1), 
    );

    /* 自动完成规则 */
    protected $_auto = array(
	
    );

}