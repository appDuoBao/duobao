<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Controller;

/**
 * 后台回复管理控制器
 * @author ew_xiaoxiao <www@ewangtx.com>
 */
class ReplyController extends ControlController {

    /**
     * 回复管理列表
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function index(){ 
     
       $list = $this->lists('Reply','' ,'id desc');
         $this->assign('list', $list);
		 $this->meta_title = '回复管理';
        $this->display();
    }

    /**
     * 显示回复树，仅支持内部调
     * @param  array $tree 回复树
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
 
    /* 编辑回复 */
    public function edit($id = null, $pid = 0){
        $reply = D('reply');

        if(IS_POST){ //提交表单
            if(false !== $reply->update()){
                $this->success('编辑成功！', U('index'));
            } else {
                $error = $reply->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $cate = '';
            if($pid){
                /* 获取上级回复信息 */
                $cate = $reply->info($pid, 'id,name,title,status');
                if(!($cate && 1 == $cate['status'])){
                    $this->error('指定的上级回复不存在或被禁用！');
                }
            }

            /* 获取回复信息 */
            $info = $id ? $reply->info($id) : '';

            $this->assign('info',       $info);
            $this->assign('reply',   $cate);
            $this->meta_title = '编辑回复';
            $this->display();
        }
    }

    /* 新增回复 */
    public function add($pid = 0){
        $reply = D('reply');

        if(IS_POST){ //提交表单
            if(false !== $reply->update()){
                $this->success('新增成功！', U('index'));
            } else {
                $error = $reply->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $cate = array();
            if($pid){
                /* 获取上级回复信息 */
                $cate = $reply->info($pid, 'id,name,title,status');
                if(!($cate && 1 == $cate['status'])){
                    $this->error('指定的上级回复不存在或被禁用！');
                }
            }

            /* 获取回复信息 */
            $this->assign('info',       null);
            $this->assign('reply', $cate);
            $this->meta_title = '新增回复';
            $this->display('edit');
        }
    }

    /**
     * 删除一个回复
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
public function del(){
     if(IS_GET){
		  $id=I('get.id');  
          $reply =   M('reply');
        
        if( $reply->where("id='$id'")->delete()){
            $this->success('删除成功');
        }
		else{
            
		 $this->error('删除失败');
        }
		}
		
  if(IS_POST){
             $ids = I('post.id');
            $reply = M("reply");
			
            if(is_array($ids)){
                             foreach($ids as $id){
		
                             $reply->where("id='$id'")->delete();
						
                }
            }
           $this->success("删除成功！");
        }

	}
   
 


}
