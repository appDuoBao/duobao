<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | author 一网天行 <www@ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Model;
use Think\Model;

/**
 * 消息回复模型
 * @author ew_xiaoxiao <www@ewangtx.com>
 */
class WxreplyModel extends Model{

    protected $_validate = array(    
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),   
    	array('title', '', '标题已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
    );

  	protected $_auto = array(
        array('datetime', NOW_TIME, self::MODEL_BOTH),     
    );

    /**
     * 获取回复详细信息
     * @param  milit   $id 回复ID或回复标题
     * @param  boolean $field 查询字段
     * @return array     回复信息
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function info($id, $field = true){
        $map = array();
        if(is_numeric($id)){ //通过ID查询
            $map['id'] = $id;
        } else { //通过标识查询
            $map['title'] = $id;
        }
        return $this->field($field)->where($map)->find();
    }

    /**
     * 更新回复信息
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
        action_log('update_Wxreply', 'Wxreply', $data['id'] ? $data['id'] : $res, UID);

        return $res;
    }

    
}
