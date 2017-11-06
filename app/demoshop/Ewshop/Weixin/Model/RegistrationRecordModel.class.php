<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------

namespace Weixin\Model;
use Think\Model;
use Think\Controller;

/**
 * 文档基础模型
 */
class RegistrationRecordModel extends Model{
	
	
	protected $_validate = array(
	
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
        /* 获取商品扩展属性信息 */
        $map = array();
		$map['id'] = $id;
        return $this->field($field)->where($map)->find();
    }


    /**
     * 更新商品扩展属性详细信息
     * @return boolean 更新状态
     * @author ew_xiaochuan <www@ewangtx.com>
     */
    public function update(){
        $data = $this->create();
        if(!$data){ //数据对象创建错误
            return false;
        }

        /* 添加或更新数据 */
        if(empty($data['id'])){
            $res = $this->add();
        }else{
            $res = $this->save();
        }

        //记录行为
        action_log('update_RegistrationRecord', 'RegistrationRecord', $data['id'] ? $data['id'] : $res, UID);

        return $res;
    }
   


}
