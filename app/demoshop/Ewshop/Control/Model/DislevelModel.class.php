<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2015 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | author 一网天行 <www@ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Model;
use Think\Model;

/**
 * 广告位模型
 * @author ew_xiaoxiao <www@ewangtx.com>
 */
class DislevelModel extends Model{

    protected $_validate = array(
        array('num', 'require', '分销数量不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
    );

  	protected $_auto = array(
    
    );

    /**
     * 获取信息
     * @param  milit   $id 广告位ID或标识
     * @param  boolean $field 查询字段
     * @return array     广告位信息
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function info($id, $field = true){
        /* 获取优惠券信息 */
        $map = array();
        $map['id'] = $id;
        return $this->field($field)->where($map)->find();
    }

    /**
     * 保存信息
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

        //记录行为
        action_log('update_Dislevel', 'update_Dislevel', $data['id'] ? $data['id'] : $res, UID);

        return $res;
    }

    
}
