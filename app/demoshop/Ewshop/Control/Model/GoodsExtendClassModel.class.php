<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | author 一网天行_xiaochuan <www@ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Model;
use Think\Model;

/**
 * 商品扩展属性模型
 * @author ew_xiaochuan <www@ewangtx.com>
 */
class GoodsExtendClassModel extends Model{

    protected $_validate = array(

    );

  protected $_auto = array(

    );

    /**
     * 获取商品子分类型详细信息
     * @param  milit   $id 父级分类ID或标识
     * @param  boolean $field 查询字段
     * @return array     商品子分类类型列表
     * @author ew_liudi <www@ewangtx.com>
     */
    public function extendclasslist($id, $field = true){
        /* 获取商品扩展属性信息 */
        $map = array();
		$map['pid'] = $id;
        $map['is_delete'] = 0;
        return $this->field($field)->where($map)->select();
    }

    /**
     * 获取商品子分类型详细信息
     * @param  milit   $id 子分类ID或标识
     * @param  boolean $field 查询字段
     * @return array     商品子分类类型详细信息
     * @author ew_liudi <www@ewangtx.com>
     */
    public function extendclassinfo($id, $field = true){
        /* 获取商品扩展属性信息 */
        $map = array();
        $map['pid'] = $id;
        $map['is_delete'] = 0;
        return $this->field($field)->where($map)->find();
    }


    /**
     * 更新商品扩展属性子分类详细信息
     * @return boolean 更新状态
     * @author ew_liudi <www@ewangtx.com>
     */
    public function update($data){
        $data = $this->create($data);
        if(!$data){ //数据对象创建错误
            return false;
        }

        /* 添加或更新数据 */
        if(empty($data['id'])){
            $res = $this->add($data);
        }else{

                $res = $this->save($data);


        }

        //记录行为
        action_log('update_GoodsExtendClass', 'GoodsExtendClass', $data['id'] ? $data['id'] : $res, UID);

        return $res;
    }

    
}
