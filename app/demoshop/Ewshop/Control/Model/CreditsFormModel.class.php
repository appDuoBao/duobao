<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | author 一网天行 <www@ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Model;
use Think\Model;

/**
 * 优惠券模型
 * @author ew_xiaoxiao <www@ewangtx.com>
 */
class CreditsFormModel extends Model{

    protected $_validate = array(

			array('name', 'require', '姓名不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
			array('tel', 'require', '电话不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
			array('youbian', 'require', '邮编不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
			array('adr', 'require', '地址不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),

    );

  protected $_auto = array(


    );

    /**
     * 获取城市详细信息
     * @param  milit   $id 优惠券ID或标识
     * @param  boolean $field 查询字段
     * @return array     优惠券信息
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function info($id, $field = true){
        /* 获取优惠券信息 */
        $map = array();
        if(is_numeric($id)){ //通过ID查询
            $map['id'] = $id;
        } else { //通过标识查询
            $map['title'] = $id;
        }
        return $this->field($field)->where($map)->find();
    }




    /**
     * 更新城市信息
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
        action_log('update_CreditsForm', 'Slide', $data['id'] ? $data['id'] : $res, UID);

        return $res;
    }

    
}
