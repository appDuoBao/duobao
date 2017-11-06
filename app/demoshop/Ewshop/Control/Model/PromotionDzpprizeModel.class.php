<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2015 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | author 一网天行 <www@ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Model;
use Think\Model;

/**
 * 大转盘模型
 * @author ew_xiaoxiao <www@ewangtx.com>
 */
class PromotionDzpprizeModel extends Model{

    protected $_validate = array(
        array('name', 'require', '奖品不能为空，请设置奖品', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),	
		array('prize_id', 'require', '奖品不能为空，请设置奖品', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),	
		array('level', 'checklevel', '奖项已经设置，请重新选择奖品等级', self::MUST_VALIDATE , 'callback', self::MODEL_BOTH), 
		array('num', 'require', '奖品数量不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH), 
    );
  	protected $_auto = array(
    );

    /**
     * 检查当前转盘活动等级奖品是否已经设置
     * @param string $name
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    protected function checklevel(){
		$id        = I('post.id');
        $pid        = I('post.pid');
		$level      = I('post.level');
        $map = array('pid' => $pid,'level' => $level, 'id' => array('neq', $id));
        $res = $this->where($map)->getField('id');
        if ($res) {
            return false;
        }
        return true;
    }
	
    /**
     * 获取大转盘信息
     * @param  milit   $id 大转盘ID或标识
     * @param  boolean $field 查询字段
     * @return array     大转盘信息
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function info($id, $field = true){
        /* 获取大转盘信息 */
        $map = array();
        $map['id'] = $id;
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

        //记录行为
        action_log('update_PromotionDzp', 'PromotionDzp', $data['id'] ? $data['id'] : $res, UID);

        return $res;
    }	

    
}
