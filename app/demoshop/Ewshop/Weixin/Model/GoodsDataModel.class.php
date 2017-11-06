<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | author 一网天行_xiaochuan <www@ewangtx.com>
// +----------------------------------------------------------------------

namespace Weixin\Model;
use Think\Model;

/**
 * 商品属性数据表
 * @author ew_xiaochuan <www@ewangtx.com>
 */
class GoodsDataModel extends Model{

    protected $_validate = array(
        //array('type_name', 'require', '名称不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
    );

  	protected $_auto = array(
        //array('create_time', NOW_TIME, self::MODEL_INSERT),
    );

    /**
     * 根据商品id获取所有属性信息
     * @param  boolean $field 查询字段
     * @return array     商品类型详细信息
     * @author ew_xiaochuan <www@ewangtx.com>
     */
    public function getDatabygid($id, $field = true){
        $map = array();
		$map['good_id'] = $id;
        return $this->field($field)->where($map)->find();
    }
    
}
