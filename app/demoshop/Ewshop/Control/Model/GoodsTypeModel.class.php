<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | author 一网天行_xiaochuan <www@ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Model;
use Think\Model;

/**
 * 优惠券模型
 * @author ew_xiaochuan <www@ewangtx.com>
 */
class GoodsTypeModel extends Model{

    protected $_validate = array(
        array('type_name', 'require', '名称不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
    );

  protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
       
    );

    /**
     * 获取商品类型详细信息
     * @param  milit   $id 优惠券ID或标识
     * @param  boolean $field 查询字段
     * @return array     商品类型详细信息
     * @author ew_xiaochuan <www@ewangtx.com>
     */
    public function info($id, $field = true){
        $map = array();
		$map['type_id'] = $id;
        return $this->field($field)->where($map)->find();
    }


    /**
     * 更新商品类型详细信息
     * @return boolean 更新状态
     * @author ew_xiaochuan <www@ewangtx.com>
     */
    public function update(){
        $data = $this->create();
        if(!$data){ //数据对象创建错误
            return false;
        }

        /* 添加或更新数据 */
        if(empty($data['type_id'])){
            $res = $this->add();
        }else{
            $res = $this->save();
        }

        //记录行为
        action_log('update_GoodsType', 'GoodsType', $data['type_id'] ? $data['type_id'] : $res, UID);

        return $res;
    }

    
}
