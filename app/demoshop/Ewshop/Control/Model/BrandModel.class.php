<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | author 一网天行 <www@ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Model;
use Think\Model;

/**
 * 品牌模型
 * @author ew_xiaoxiao <www@ewangtx.com>
 */
class BrandModel extends Model{

    protected $_validate = array(
		array('title', 'require', '品牌名称不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH), 
		array('title', 'checkTitle', '品牌名称已经存在', self::MUST_VALIDATE , 'callback', self::MODEL_BOTH), 
		array('name', 'require', '品牌编号不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH), 	
		array('name', 'checkName', '品牌编号已经存在', self::VALUE_VALIDATE, 'callback', self::MODEL_BOTH),
    );

	protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
    );

    /**
     * 检查名称是否已存在
     * @param string $name
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    protected function checkTitle(){
        $title        = I('post.title');
		$id          = I('post.id', 0);
        $map = array('title' => $title, 'id' => array('neq', $id));
        $res = $this->where($map)->getField('id');
        if ($res) {
            return false;
        }
        return true;
    }
    /**
     * 检查编号是否已存在
     * @param string $name
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    protected function checkName(){
        $name        = I('post.name');
		$id          = I('post.id', 0);
        $map = array('name' => $name, 'id' => array('neq', $id));
        $res = $this->where($map)->getField('id');
        if ($res) {
            return false;
        }
        return true;
    }
		
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
