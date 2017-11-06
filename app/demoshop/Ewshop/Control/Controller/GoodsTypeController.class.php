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
class GoodsTypeController extends ControlController {

    /**
     * 商品类型管理
     * author 一网天行 <www@ewangtx.com>
     */
    public function index(){
        /* 查询条件初始化 */
        $map  = array();
        if(IS_GET){ 
			$name=trim(I('get.name'));
		    $map['type_name'] = array('like',"%{$name}%");

		}
        $map['is_delete'] = 0;
		    $list = $this->lists('GoodsType', $map,'type_id desc');

        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '商品类型管理';
        $this->display();
    }

    /* 编辑商品类型 */
    public function edit($id = null){
        $goodstype = D('GoodsType');
        if(IS_POST){ //提交表单
            if(false !== $goodstype->update()){
                $this->success('编辑成功！', U('index'));
            } else {
                $error = $goodstype->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            /* 获取广告位信息 */
            $info = $id ? $goodstype->info($id) : '';
            $this->assign('info',    $info);
            $this->meta_title = '编辑商品类型';
            $this->display();
        }
    }

    /* 新增商品类型 */
    public function add(){
        $goodstype = D('GoodsType');
        if(IS_POST){ //提交表单
            if(false !== $goodstype->update()){
                $this->success('新增成功！', U('index'));
            } else {
                $error = $goodstype->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $this->meta_title = '新增商品类型';
            $this->display();
        }
    }

	/* 删除广告位 */
	public function del(){
       if(IS_POST){
            $ids = I('post.id');
            $adp = M("GoodsType");
			
            if(is_array($ids)){
				 foreach($ids as $id){
					 $adp->where("type_id='$id'")->setField('is_delete','1');
                 }
            }
           $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $db = M("GoodsType");
            $status = $db->where("type_id='$id'")->setField('is_delete','1');
            if ($status){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }

}