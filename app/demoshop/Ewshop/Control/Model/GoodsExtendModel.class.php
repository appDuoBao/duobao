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
class GoodsExtendModel extends Model{

    protected $_validate = array(
        array('extend_name', 'require', '名称不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('extend_value', 'require', '属性值不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
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
		$map['extend_id'] = $id;
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
        $extendclass = D('GoodsExtendClass');
        /* 添加或更新数据 */
        if(empty($data['extend_id'])){
            $exmap['type_id'] = $data['type_id'];
            $exmap['extend_name'] = $data['extend_name'];
            $goodsextendinfo = $this->where($exmap)->find();
            if($goodsextendinfo){
                $exmap['extend_id'] = $goodsextendinfo['extend_id'];
                $pid =  $exmap['extend_id'];
                $exmap['is_delete'] = 0;
                $res = $this->where(array('extend_id'=>$exmap['extend_id']))->save($exmap);
            }else{
                $res = $this->add();
                $pid =  $res;
            }

        }else{
            $pid = $data['extend_id'];
            $res = $this->save();
        }

        if($res !== false){
            $extend_value = $data['extend_value'];
            $oldmap['pid'] = $pid;
            $oldmap['is_delete'] = 0;
            $old_extend_value = $extendclass->field('id')->where($oldmap)->select();//获取原来的属性值的id数组
            $old_id_arr = array();
            foreach($old_extend_value as $k=>$v){
                $old_id_arr[$v['id']] = $v['id'];//获取原来的属性值的id数组变成一维数组
            }
            $extend_value = explode(',',$extend_value);//将获取的当前属性值拆分成数组
            foreach($extend_value as $k=>$v){
                $map=array();
                $map['name'] = $v;
                $map['pid'] = $pid;
                $extendclassid = $extendclass->field('id')->where($map)->find();
                $map['px'] = $k+1;
                $map['is_delete'] = 0;
                if($extendclassid['id']){
                    $map['id'] = $extendclassid['id'];
                    if(in_array($map['id'], $old_id_arr)){
                        unset($old_id_arr[$map['id']]);//将需要保留的数组从原属性列表中删除
                    }
                }
                $classres = $extendclass->update($map);
                if($classres === false){
                    $falg = 'wrong';
                    break;
                }
            }
            if($old_id_arr){
                $delmap['id'] = array('in',implode(",", $old_id_arr));
                $deldate['is_delete'] = 1;
                $delres = $extendclass->where($delmap)->save($deldate);
                if($delres === false){
                    $falg = 'wrong';
                }
                //记录行为
                action_log('update_GoodsExtendClass', 'GoodsExtendClass', $delmap['id'] ? $delmap['id'] : $delres, UID);
            }
            if($falg == 'wrong'){
                return false;die;
            }
        }

        //记录行为
        action_log('update_GoodsExtend', 'GoodsExtend', $data['extend_id'] ? $data['extend_id'] : $res, UID);

        return $res;
    }

    
}
