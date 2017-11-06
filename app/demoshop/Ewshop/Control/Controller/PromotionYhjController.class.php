<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Controller;

/**
 * 后台优惠券控制器
  * @author 一网天行 <www@ewangtx.com>
 */
class PromotionYhjController extends ControlController {

    /**
     * 优惠券管理
     * author 一网天行 <www@ewangtx.com>
     */
    public function index(){
        /* 查询条件初始化 */
        $map  = array();
        if(IS_GET){ 
			$name=trim(I('get.name'));
		    $map['name'] = array('like',"%{$name}%");
		  	$list = $this->lists('PromotionYhj', $map,'id desc');
		} else { 
		    $list = $this->lists('PromotionYhj', $map,'id desc');
	 	}
        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '优惠券管理';
        $this->display();
    }

    /* 编辑优惠券 */
    public function edit($id = null){
        $pyhj = D('PromotionYhj');
        if(IS_POST){ //提交表单
            if(false !== $pyhj->update()){
                $this->success('编辑成功！', U('index'));
            } else {
                $error = $pyhj->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            /* 获取优惠券信息 */
            $info = $id ? $pyhj->info($id) : '';
            $this->assign('info',    $info);
            $this->meta_title = '编辑优惠券';
            $this->display();
        }
    }

    /* 新增优惠券 */
    public function add(){
        $pyhj = D('PromotionYhj');
        if(IS_POST){ //提交表单
            if(false !== $pyhj->update()){
                $this->success('新增成功！', U('index'));
            } else {
                $error = $pyhj->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $this->meta_title = '新增优惠券';
            $this->display();
        }	
    }

	/* 删除优惠券 */
	public function del(){
       if(IS_POST){
            $ids = I('post.id');
            $pyhj = M("PromotionYhj");
			
            if(is_array($ids)){
				 foreach($ids as $id){
					 $pyhj->where("id='$id'")->delete();	
                 }
            }
           $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $db = M("PromotionYhj");
            $status = $db->where("id='$id'")->delete();
            if ($status){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }
	
    /* 发放优惠券 */
    public function send($id = null){
        $pyhj = D('PromotionYhj');
        if(IS_POST){ //提交表单
			$type = I('post.type');//优惠券发放类型
            if($type=='1'){//按会员发放优惠券
                $yhj_type = I('post.yhj_type');//优惠券类型
				$yhj_no = I('post.yhj_no');//优惠券标识
				$fftype = I('post.fftype');//优惠券标识
				if($fftype==1){//全部会员
					$users  =  M("UcenterMember")->field('id')->select();
					$data=array();
					$i = 0;
					foreach($users as $user){
						$data[$i]['yhj_id']= $yhj_type;
						$data[$i]['user_id']= $user['id'];
						$data[$i]['sn']= $yhj_no.time().str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);//标识+时间戳+4位随机数
						$i++;
						
						if($i%10000 == 0){//每1000条数据执行一次插入。防止数据过大
							$result = M('PromotionYhjdata')->addAll($data);
							$data = array();
							$i = 0;
						}							
					}					
				}elseif($fftype==2){//指定会员
					$users = $_REQUEST['user'];
					$data=array();
					$i = 0;
					foreach($users as $userid){
						$data[$i]['yhj_id']= $yhj_type;
						$data[$i]['user_id']= $userid;
						$data[$i]['sn']= $yhj_no.time().str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);//标识+时间戳+4位随机数
						$i++;
						
						if($i%10000 == 0){//每1000条数据执行一次插入。防止数据过大
							$result = M('PromotionYhjdata')->addAll($data);
							$data = array();
							$i = 0;
						}							
					}	
				}
            } else {//线下发放优惠券
                $yhj_type = I('post.yhj_type');//优惠券类型
				$yhj_no = I('post.yhj_no');//优惠券标识
				$yhj_num = I('post.yhj_num');//优惠券发放数量
				
				$data=array();
				$i = 0;
				for ($j = 0; $j < $yhj_num; $j++)
				{
					$data[$i]['yhj_id']= $yhj_type;
					$data[$i]['sn']= $yhj_no.time().str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);//标识+时间戳+4位随机数
					
					$i++;
					if($i%100 == 0){//每1000条数据执行一次插入。防止数据过大
						$result = M('PromotionYhjdata')->addAll($data);
						$data = array();
						$i = 0;
					}							
				}				
            }
			if($data){
				$result = M('PromotionYhjdata')->addAll($data);
				if($result){
					$this->success('发放成功！', U('index'));
				}else{
					$this->success($data[1]['user_id'].'发放失败！'.$data[1]['sn'], U('index'));
				}					
			}else{
				$this->success('发放成功！', U('index'));
			}
        } else {
            /* 获取优惠券信息 */
            $info = $id ? $pyhj->info($id) : '';
			$this->assign('id',    $id);	
            $this->assign('info',    $info);
            $this->meta_title = '发放优惠券';
			if($info['type']=='1'){//按会员发放优惠券
				$map['type'] = 1;
				$typelist = $this->lists('PromotionYhj', $map,'id desc');	
				$this->assign('typelist',    $typelist);				
				$this->display('send_user');
			}else{//线下发放优惠券
				$map['type'] = 2;
				$typelist = $this->lists('PromotionYhj', $map,'id desc');	
				$this->assign('typelist',    $typelist);	
				$this->display('send_other');
			}
        }
    }

    /**
     * 已发优惠券查看
     * author 一网天行 <www@ewangtx.com>
     */
    public function view(){
		$id=trim(I('get.id'));
		$map['yhj_id'] = $id;
		$list = $this->lists('PromotionYhjdata', $map,'id desc');
		
		foreach($list as $key=>$val){
			$yhjType = D('PromotionYhj')->info($val['yhj_id']);
			if($yhjType){
				$list[$key]['yhj_name']= $yhjType['name'];//优惠券类型名称
			}
			$nickName = D('Member')->getNickName($val['user_id']);
			if($nickName){
				$list[$key]['username']= $nickName;//优惠券类型名称
			}
			
		}	
        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '优惠券列表';
        $this->display();
    }
	/* 删除已发优惠券 */
	public function yhjdel(){
       if(IS_POST){
            $ids = I('post.id');
            $pyhjdata = M("PromotionYhjdata");
			
            if(is_array($ids)){
				 foreach($ids as $id){
					 $pyhjdata->where("id='$id'")->delete();	
                 }
            }
           $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $pyhjdata = M("PromotionYhjdata");
            $status = $pyhjdata->where("id='$id'")->delete();
            if ($status){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }
				
	//根据关键词搜索会员
	public function ajaxsearchuser() {
		$keyword=htmlspecialchars($_POST["keyword"]);
		$map['username'] = array('like', '%'.$keyword.'%');//昵称
		$list  =  M("UcenterMember")->where($map)->field('id,username')->order('id desc')->select();
		 
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