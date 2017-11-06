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
class CommentRecordModel extends Model{
	
	
	protected $_validate = array(
		array('comment', 'require', '内容不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
    );

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
       
    );

    /**
     * @author ew_xiaochuan <www@ewangtx.com>
     */
    public function info($id, $field = true){
        /* 获取商品扩展属性信息 */
        $map = array();
		$map['id'] = $id;
        return $this->field($field)->where($map)->find();
    }


    /**
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
        action_log('update_CommentRecord', 'update_CommentRecord', $data['id'] ? $data['id'] : $res, UID);

        return $res;
    }
   


}
