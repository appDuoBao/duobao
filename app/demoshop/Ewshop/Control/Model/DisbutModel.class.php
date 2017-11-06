<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | author 一网天行 <www@ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Model;
use Think\Model;

/**
 * 公司模型
 * @author ew_xiaoxiao <www@ewangtx.com>
 */
class DisbutModel extends Model{

    protected $_validate = array(

    );

 	protected $_auto = array(
   array('createtime', NOW_TIME, self::MODEL_INSERT),
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

        //记录行为
        action_log('update_Disbut', 'Disbut', $data['id'] ? $data['id'] : $res, UID);

        return $res;
    }

	

	
    
}
