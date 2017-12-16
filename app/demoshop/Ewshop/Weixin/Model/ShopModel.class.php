<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | author 一网天行 <www@ewangtx.com>
// +----------------------------------------------------------------------

namespace Weixin\Model;
use Think\Model;

/**
 * 优惠券模型
 * @author ew_xiaoxiao <www@ewangtx.com>
 */
class ShopModel extends Model{

    protected $_validate = array(
     array('shopname', 'require', '名称不能为空', self::EXISTS_VALIDATE, 'regex', self::MODEL_BOTH),
    array('shopname','','店铺名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
   
);

 protected $_auto = array(
        array('uid', 'is_login', self::MODEL_INSERT, 'function'),
     array('shopname', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
	   array('username', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
        array('description', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
       
    );

    /**
     * 获取详细信息
     * @param  milit   $id ID或标识
     * @param  boolean $field 查询字段
     * @return array  
     * @author 一网天行 <www@ewangtx.com>
     */
    public function info($id, $field = true){
        /* 获取信息 */
        $map = array();
        if(is_numeric($id)){ //通过ID查询
            $map['id'] = $id;
        } else { //通过标识查询
            $map['name'] = $id;
        }
        return $this->field($field)->where($map)->find();
    }

    /**
     * 更新信息
     * @return boolean 更新状态
     * @author ew_xiaoxiao <www@ewangtx.com>
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

        //更新缓存
        S('sys_brand_list', null);

        //记录行为
        action_log('update_Brand', 'brand', $data['id'] ? $data['id'] : $res, UID);

        return $res;
    }

    
}