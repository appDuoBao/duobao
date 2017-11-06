<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Controller;

/**
 * 后台满减促销控制器
  * @author 一网天行 <www@ewangtx.com>
 */
class PromotionMjController extends ControlController {

    /**
     * 满减促销管理
     * author 一网天行 <www@ewangtx.com>
     */
    public function index(){
        /* 查询条件初始化 */
        $map  = array();
        if(IS_GET){ 
			$name=trim(I('get.name'));
		    $map['name'] = array('like',"%{$name}%");
		  	$list = $this->lists('PromotionMj', $map,'id desc');
		} else { 
		    $list = $this->lists('PromotionMj', $map,'id desc');
	 	}
        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '满减促销管理';
        $this->display();
    }

    /* 编辑满减促销 */
    public function edit($id = null){
        $pmj = D('PromotionMj');
        if(IS_POST){ //提交表单
            if(false !== $pmj->update()){
                $this->success('编辑成功！', U('index'));
            } else {
                $error = $pmj->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            /* 获取满减促销信息 */
            $info = $id ? $pmj->info($id) : '';
			
			if($info['type']=='2'){//产品分类
				$map['id']  = array('in',$info['ids']);
				$idlist  =  M("Category")->where($map)->field('id,title')->order('id desc')->select();
			}elseif($info['type']=='3'){//产品
				$map['id']  = array('in',$info['ids']);
				$idlist  =  M("Document")->where($map)->field('id,title')->order('id desc')->select();
			}
			$this->assign('idlist',    $idlist);
            $this->assign('info',    $info);
            $this->meta_title = '编辑满减促销';
            $this->display();
        }
    }

    /* 新增满减促销 */
    public function add(){
        $pmj = D('PromotionMj');
        if(IS_POST){ //提交表单
            if(false !== $pmj->update()){
                $this->success('新增成功！', U('index'));
            } else {
                $error = $pmj->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $this->meta_title = '新增满减促销';
            $this->display();
        }
    }

	/* 删除满减促销 */
	public function del(){
       if(IS_POST){
            $ids = I('post.id');
            $pmj = M("PromotionMj");
			
            if(is_array($ids)){
				 foreach($ids as $id){
					 $pmj->where("id='$id'")->delete();	
                 }
            }
           $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $db = M("PromotionMj");
            $status = $db->where("id='$id'")->delete();
            if ($status){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }
	//根据类型获取搜索内容
	public function ajaxsearch() {
		 $ptype=htmlspecialchars($_POST["ptype"]);
		 $keyword=htmlspecialchars($_POST["keyword"]);
		 
		 if($ptype=='2'){//分类搜索
		 	$map['title'] = array('like', '%'.$keyword.'%');
			$map['ismenu'] = 1;//产品分类
			$list  =  M("Category")->where($map)->field('id,title')->order('id desc')->limit('0,50')->select();
		 }elseif($ptype=='3'){//产品搜索
			 $map['title']  = array('like', '%'.$keyword.'%');
			 $list  =  M("Document")->where($map)->field('id,title')->order('id desc')->limit('0,50')->select();
		 }
		 
		if (empty($list)){
			$data['status'] = 0; 
			$data['result'] = "搜索结果为空";
		}else{
			$data['status'] = 1; 
			$data['result'] = $list;
		}	 
		$this->ajaxReturn($data);
	}
}