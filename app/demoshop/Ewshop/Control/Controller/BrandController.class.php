<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | author 一网天行 <www@ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Controller;

/**
 * 后台品牌控制器
  * @author 一网天行 <www@ewangtx.com>
 */
class BrandController extends ControlController {

    /**
     * 品牌管理
     * author 一网天行 <www@ewangtx.com>
     */
    public function index(){	
		$map  = array('status' => 1);
		$title=trim(I('get.title'));
		if($title){ 
			$map['name|title'] = array('like',"%{$title}%");//多字段like
			$list = M("Brand")->where($map)->field(true)->order('id desc')->select();
		}else { 
			$list = $this->lists('Brand', $map,'id desc');
		}
		int_to_string($list,array('status'=>array(1=>'正常',2=>'禁用')));
        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '品牌管理';
        $this->display();
    }

    /* 编辑品牌管理 */
    public function edit($id = null, $pid = 0){
	    $brand = D('brand');
        if(IS_POST){ //提交表单
            if(	false !== $brand->update()){
                $this->success('编辑成功！', U('index'));
            } else {
                $error = $brand->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            /* 获取分类信息 */
            $info = $id ? $brand->info($id) : '';
            $this->assign('info',       $info);
            $this->meta_title = '编辑品牌';
            $this->display();
        }
    }

    /* 新增品牌 */
    public function add(){
        $brand = D('brand');
        if(IS_POST){ //提交表单
            if(false !== $brand->update()){
                $this->success('新增成功！', U('index'));
            } else {
                $error = $brand->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
			/*品牌信息 */
            $this->assign('info',null);
            $this->meta_title = '新增品牌';
            $this->display();
        }
    }

 	public function del(){
       if(IS_POST){
            $ids = I('post.id');
            $order = M("brand");
            if(is_array($ids)){
				 foreach($ids as $id){
				 	$order->where("id='$id'")->delete();	
                 }
            }
           $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $db = M("brand");
            $status = $db->where("id='$id'")->delete();
            if ($status){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }
	
}