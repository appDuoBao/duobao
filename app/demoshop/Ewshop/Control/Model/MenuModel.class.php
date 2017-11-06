<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <www.ewangtx.com>
// +----------------------------------------------------------------------
namespace Control\Model;
use Think\Model;

/**
 * 插件模型
 * @author ew_xiaoxiao <www@ewangtx.com>
 */

class MenuModel extends Model {

    protected $_validate = array(
        array('title','require','标题必须填写'), 
        array('url','require','链接必须填写'), 
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('title', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );

}