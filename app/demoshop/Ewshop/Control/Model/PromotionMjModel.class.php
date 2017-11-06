<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2015 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | author 一网天行 <www@ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Model;
use Think\Model;

/**
 * 满减促销模型
 * @author ew_xiaoxiao <www@ewangtx.com>
 */
class PromotionMjModel extends Model{

    protected $_validate = array(
        array('name', 'require', '满减促销名称不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
		array('start_time', 'require', '开始时间不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
		array('end_time', 'require', '结束时间不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
		array('top_price', 'require', '金额上线不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
		array('mj_price', 'require', '减免金额不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
    );

  	protected $_auto = array(
		array('ids', 'setids', self::MODEL_BOTH, 'callback'),
        array('create_time', NOW_TIME, self::MODEL_INSERT),
    );

	public function setids(){
		$ids = I('post.ids');
		if(is_array($ids)){
			$return = implode(',',$ids);
		}else{
			$return = $ids;
		} 
		return $return; 
	}

    /**
     * 获取满减促销信息
     * @param  milit   $id 满减促销ID或标识
     * @param  boolean $field 查询字段
     * @return array     满减促销信息
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function info($id, $field = true){
        /* 获取满减促销信息 */
        $map = array();
        $map['id'] = $id;
        return $this->field($field)->where($map)->find();
    }

    /**
     * 保存满减促销信息
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
        action_log('update_PromotionMj', 'PromotionMj', $data['id'] ? $data['id'] : $res, UID);

        return $res;
    }

    
}
