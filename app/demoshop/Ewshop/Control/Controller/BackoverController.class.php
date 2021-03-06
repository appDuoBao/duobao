<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | author 一网天行 <www@ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Controller;

/**
 * 后台订单控制器
  * @author 一网天行 <www@ewangtx.com>
 */
class BackoverController extends ControlController {

    /**
     * 订单管理
     * author 一网天行 <www@ewangtx.com>
     */
    public function index(){
        /* 查询条件初始化 */
		$a=M("backlist")->where("total='null'")->delete();	 
       $map  = array('status' =>5);
       $list = $this->lists('backlist', $map,'id desc');

        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '完成退货管理';
        $this->display();
    }

    /**
     * 新增订单
     * @author 一网天行 <www@ewangtx.com>
     */
    public function add(){
        if(IS_POST){
            $Config = D('backlist');
            $data = $Config->create();
			  /* 新增时间并更新时间*/
         	$shopid=$_POST["shopid"]; $shoplist=M('shoplist')->where("id='$shopid'")->setField('status','8');   
            if($data){
                if($Config->add()){
                    S('DB_CONFIG_DATA',null);
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Config->getError());
            }
        } else {
            $this->meta_title = '新增配置';
            $this->assign('info',null);
            $this->display();
        }
    }

    /**
     * 编辑订单
     * @author 一网天行 <www@ewangtx.com>
     */
    public function edit($id = 0){
        if(IS_POST){
            $Form =  D('backlist');
       
            if($_POST["id"]){
				$id=$_POST["id"];
               $Form->create();
           $result=$Form->where("id='$id'")->save();
                if($result){
                    //记录行为
                    action_log('update_backlist', 'backlist', $data['id'], UID);
                    $this->success('更新成功', Cookie('__forward__'));
                } else {
                    $this->error('更新失败,退货单'.$id);
                }
            } else {
                $this->error($Config->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('backlist')->find($id);

            $list=M('backlist')->where("shopid='$id'")->select();

            if(false === $info){
                $this->error('获取订单信息错误');
            }
            $this->assign('list', $list);

			 $this->assign('info', $info);
            $this->meta_title = '编辑订单';
            $this->display();
        }
    }
 /**
     * 同意订单
     * @author 一网天行 <www@ewangtx.com>
     */
   

  
   /**
     * 删除订单
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function del(){
       if(IS_POST){
             $ids = I('post.id');
            $order = M("backlist");
			
            if(is_array($ids)){
                             foreach($ids as $id){
		
                             $order->where("id='$id'")->delete();
						
                }
            }
           $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $db = M("backlist");
            $status = $db->where("id='$id'")->delete();
            if ($status){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }


}