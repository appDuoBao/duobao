<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------

namespace Weixin\Model;
use Think\Model;
use Think\Page;

/**
 * 文档基础模型
 */
class CreditsFormModel extends Model{

    /* 自动验证规则 */
    protected $_validate = array(
        array('name', 'require', '姓名不能为空', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),

    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),

    );

    /**
     * 获取详细信息
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
        action_log('update_Slide', 'Slide', $data['id'] ? $data['id'] : $res, UID);

        return $res;
    }

}
