<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com>
// +----------------------------------------------------------------------
namespace Control\Controller;
use Control\Model\AuthGroupModel;
use Think\Page;

/**
 * 后台内容控制器
 * @author ew_xiaoxiao <www@ewangtx.com>
 */
class GoodsExtendController extends ControlController {
 /**
     * 订单管理
     * author 一网天行 <www@ewangtx.com>
     */
    public function index(){
        /* 查询条件初始化 */
	
       $map  = array();
       if(IS_GET){ 
	   	 $title=trim(I('get.extend_name'));
		 if($title){
			 $map['extend_name'] = array('like',"%{$title}%");
		 }
		 $type=trim(I('get.adp'));
		 if($type){
		 	$map['type_id'] = $type;
		 }
	    }
        $map['is_delete'] = 0;
        $list = $this->lists('GoodsExtend', $map,'extend_id desc');
		$adp = D('GoodsType');
		foreach($list as $k=>$value){
			$info = $adp->where("type_id=".$value['type_id'])->find();
			if($info){
				$list[$k]['type'] = $info['type_name'];
			}
		}	   
        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '首页分类推广管理';
        $this->display();
    }

    /* 编辑分类 */
    public function edit($extend_id = null){
        $adp = D('GoodsType');
        $goodsextendclass = D('GoodsExtendClass');
		$list = $adp->select();
        $this->assign('list',$list);//商品类型列表
		$goodsextend = D('GoodsExtend');
        if(IS_POST){ //提交表单
            if(false !== $goodsextend->update()){
                $this->success('编辑成功！', U('/Control/GoodsExtend/index/adp/'.$_POST['type_id']));
            } else {
                $error = $goodsextend->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $info = $extend_id ? $goodsextend->info($extend_id) : '';

            $extendclasslist = $goodsextendclass->extendclasslist($extend_id,'name');
            $info['extend_value'] = '';
            foreach($extendclasslist as $k=>$v){
                if($info['extend_value']){
                    $info['extend_value'] .= ','.$v['name'];
                }else{
                    $info['extend_value'] =$v['name'];
                }
            }
            $info['type_name'] = $adp->getFieldByType_id($info['type_id'],'type_name');

            $this->assign('info',       $info);
            $this->meta_title = '编辑商品属性';
            $this->display();
        }
    }

    /* 新增商品属性 */
    public function add(){
        $adp = D('GoodsType');
		$list = $adp->select();
        $this->assign('list',$list);//商品类型列表
		$ad = D('GoodsExtend');
        if(IS_POST){ //提交表单
            if(false !== $ad->update()){
                $this->success('新增成功！',  U('/Control/GoodsExtend/index/adp/'.$_POST['type_id']));
            } else {
                $error = $ad->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $this->meta_title = '新增商品属性';
            $this->display();
        }
    }


 public function del(){
       if(IS_POST){
            $ids = I('post.id');
            $order = M("GoodsExtend");
            if(is_array($ids)){
			 foreach($ids as $id){
				$order->where("extend_id='$id'")->setField('is_delete','1');
			 }
            }
           $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $db = M("GoodsExtend");
            $status = $db->where("extend_id='$id'")->setField('is_delete','1');
            if ($status){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }

}