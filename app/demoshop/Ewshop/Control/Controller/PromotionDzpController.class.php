<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Controller;

/**
 * 后台大转盘控制器
  * @author 一网天行 <www@ewangtx.com>
 */
class PromotionDzpController extends ControlController {

    /**
     * 大转盘管理
     * author 一网天行 <www@ewangtx.com>
     */
    public function index(){
        /* 查询条件初始化 */
        $map  = array();
        if(IS_GET){ 
			$name=trim(I('get.name'));
		    $map['name'] = array('like',"%{$name}%");
		  	$list = $this->lists('PromotionDzp', $map,'id desc');
		} else { 
		    $list = $this->lists('PromotionDzp', $map,'id desc');
	 	}
		
		int_to_string($list,array('status'=>array(1=>'正常',2=>'禁用')));
		
        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '大转盘管理';
        $this->display();
    }

    /* 编辑大转盘 */
    public function edit($id = null){
        $db = D('PromotionDzp');
        if(IS_POST){ //提交表单
            if(false !== $db->update()){
                $this->success('编辑成功！', U('index'));
            } else {
                $error = $db->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            /* 获取大转盘信息 */
            $info = $id ? $db->info($id) : '';
            $this->assign('info',    $info);
            $this->meta_title = '编辑大转盘';
            $this->display();
        }
    }

    /* 新增大转盘 */
    public function add(){
        $db = D('PromotionDzp');
        if(IS_POST){ //提交表单
            if(false !== $db->update()){
                $this->success('新增成功！', U('index'));
            } else {
                $error = $db->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $this->meta_title = '新增大转盘';
            $this->display();
        }	
    }

	/* 删除大转盘 */
	public function del(){
	   $dbprize = M("PromotionDzpprize");
       if(IS_POST){
            $ids = I('post.id');
            $db = M("PromotionDzp");
			$falg = false;
            if(is_array($ids)){
				 foreach($ids as $id){
					 $result = $dbprize->where("pid=".$id)->select();
					 if($result){
						$falg = true;
					 }else{
					 	$db->where("id='$id'")->delete();
					 }
                 }
            }
			if($falg){
				$this->success("存在奖品的活动，请先删除奖品！");
			}else{
				$this->success("删除成功！");
			}
        }else{
            $id = I('get.id');
			$result = $dbprize->where("pid=".$id)->select();
			if($result){
				$this->error("请先删除活动下的奖品！");
			}else{
				$db = M("PromotionDzp");
				$status = $db->where("id='$id'")->delete();
				if ($status){
					$this->success("删除成功！");
				}else{
					$this->error("删除失败！");
				}				
			}

        } 
    }
	
    /**
     * 转盘奖品管理
     * author 一网天行 <www@ewangtx.com>
     */
    public function prize_list(){
		$id=trim(I('get.id'));
		$map['pid'] = $id;//转盘ID
		$list = $this->lists('PromotionDzpprize', $map,'level asc');//奖品信息		
				
		$arrays = array('level'=>array(1=>'一等奖',2=>'二等奖',3=>'三等奖',4=>'四等奖',5=>'五等奖',6=>'六等奖'),'type'=>array(1=>'优惠券',2=>'实物奖品'));
		int_to_string($list,$arrays);
		
        $this->assign('list', $list);
		$dzpinfo = D('PromotionDzp')->info($id);//大转盘活动
		$this->assign('dzpinfo', $dzpinfo);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '奖品管理列表';
        $this->display();
    }	
    /**
     * 转盘奖品添加
     * author 一网天行 <www@ewangtx.com>
     */
    public function prize_add(){
        $db = D('PromotionDzpprize');
        if(IS_POST){ //提交表单
			$pid = I('post.pid');
            if(false !== $db->update()){
				//更改其他奖品的中奖比率
				$map['pid'] = $pid;
				$PDZP = M("PromotionDzpprize");
				$list = $PDZP->where($map)->field('id,num')->select();
				$allnum = 0;//奖品总数
				foreach($list as $key=>$val){
					$allnum += $val['num'];
				}	
				foreach($list as $key=>$val){
					$curbilv = ($val['num']/$allnum)*100;
					$data['bilv'] = $curbilv;
					$where['id'] = $val['id'];
					$PDZP->where($where)->setField($data);
				}			
                $this->success('新增成功！',  U('prize_list?id='.$pid));
            } else {
                $error = $db->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
			$pid=trim(I('get.pid'));
			$this->assign('pid', $pid);
            $this->meta_title = '新增大转盘奖品';
            $this->display();
        }	
    }	
    /**
     * 转盘奖品编辑
     * author 一网天行 <www@ewangtx.com>
     */
    public function prize_edit($id = null){
        $db = D('PromotionDzpprize');
		/* 获取大转盘奖品信息 */
		$info = $id ? $db->info($id) : '';		
        if(IS_POST){ //提交表单
            if(false !== $db->update()){
				//更改其他奖品的中奖比率
				$map['pid'] = $info['pid'];
				$PDZP = M("PromotionDzpprize");
				$list = $PDZP->where($map)->field('id,num')->select();
				$allnum = 0;//奖品总数
				foreach($list as $key=>$val){
					$allnum += $val['num'];
				}	
				foreach($list as $key=>$val){
					$curbilv = ($val['num']/$allnum)*100;
					$data['bilv'] = $curbilv;
					$where['id'] = $val['id'];
					$PDZP->where($where)->setField($data);
				}					
                $this->success('编辑成功！', U('prize_list?id='.$info['pid'])); 
            } else {
                $error = $db->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $this->assign('info',    $info);
			$this->assign('pid', $info['pid']);
            $this->meta_title = '编辑大转盘奖品';
            $this->display();
        }
    }
	
	/* 删除大转盘奖品 */
	public function prize_del(){
       if(IS_POST){
            $ids = I('post.id');
            $db = M("PromotionDzpprize");
            if(is_array($ids)){
				 foreach($ids as $id){
					 /* 获取大转盘奖品信息 */
					 $info = D('PromotionDzpprize')->info($id);							 
					 $db->where("id='$id'")->delete();	
                 }
            }
			//更改其他奖品的中奖比率
			$map['pid'] = $info['pid'];
			$PDZP = M("PromotionDzpprize");
			$list = $PDZP->where($map)->field('id,num')->select();
			$allnum = 0;//奖品总数
			foreach($list as $key=>$val){
				$allnum += $val['num'];
			}	
			foreach($list as $key=>$val){
				$curbilv = ($val['num']/$allnum)*100;
				$data['bilv'] = $curbilv;
				$where['id'] = $val['id'];
				$PDZP->where($where)->setField($data);
			}	
						
			
           $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $db = M("PromotionDzpprize");
			/* 获取大转盘奖品信息 */
			$info = D('PromotionDzpprize')->info($id);					
            $status = $db->where("id='$id'")->delete();
            if ($status){			
				//更改其他奖品的中奖比率
				$map['pid'] = $info['pid'];
				$PDZP = M("PromotionDzpprize");
				$list = $PDZP->where($map)->field('id,num')->select();
				$allnum = 0;//奖品总数
				foreach($list as $key=>$val){
					$allnum += $val['num'];
				}	
				foreach($list as $key=>$val){
					$curbilv = ($val['num']/$allnum)*100;
					$data['bilv'] = $curbilv;
					$where['id'] = $val['id'];
					$PDZP->where($where)->setField($data);
				}	
								
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }	

    /**
     * 转盘抽奖数据管理
     * author 一网天行 <www@ewangtx.com>
     */
    public function data_list(){
		$zid=trim(I('get.id'));
		$map['zid'] = $zid;//转盘活动ID
		$list = $this->lists('PromotionDzpdata', $map,'create_time desc');//奖品信息	
			
		foreach($list as $key=>$val){
			$pdzpp = D('PromotionDzpprize')->info($val['pid']);
			if($pdzpp){
				$list[$key]['level']= $pdzpp['level'];//奖品等级
				$list[$key]['type']= $pdzpp['type'];//奖品类型
				$list[$key]['name']= $pdzpp['name'];//奖品名称
			}
			$nickName = D('Member')->getNickName($val['uid']);
			if($nickName){
				$list[$key]['uid']= $nickName;//优惠券类型名称
			}
		}
		$arrays = array('level'=>array(1=>'一等奖',2=>'二等奖',3=>'三等奖',4=>'四等奖',5=>'五等奖',6=>'六等奖'),'type'=>array(1=>'优惠券',2=>'实物奖品'));
		int_to_string($list,$arrays);
        $this->assign('list', $list);
		$dzpinfo = D('PromotionDzp')->info($zid);//大转盘活动
		$this->assign('dzpinfo', $dzpinfo);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '抽奖数据列表';
        $this->display();
    }	
	
	//根据类型获取搜索内容
	public function ajaxsearch() {
		 $ptype=htmlspecialchars($_POST["ptype"]);
		 $keyword=htmlspecialchars($_POST["keyword"]);
		 
		 if($ptype=='1'){//优惠券
		 	 $map['name'] = array('like', '%'.$keyword.'%');
			 $list  =  M("PromotionYhj")->where($map)->field('id,name as title')->order('id desc')->limit('0,50')->select();
		 }elseif($ptype=='2'){//产品搜索
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
	
	//根据奖品数量获取中奖比率
	public function ajaxgetbilv() {
		$pid=htmlspecialchars($_POST["pid"]);//活动id
		$num=htmlspecialchars($_POST["num"]);//当前奖品数量
		$id=htmlspecialchars($_POST["id"]);//当前id
		
		$map['pid'] = $pid;
		$list  =  M("PromotionDzpprize")->where($map)->field('id,num')->select();
		$allnum = 0;
		foreach($list as $key=>$val){
			if($val['id']!=$id){
				$allnum += $val['num'];
			}
		}	
		$bilv= ($num/($allnum+$num))*100;//奖品中奖率
		if ($bilv){
			$data['status'] = 1; 
			$data['result'] = $bilv;
		}else{
			$data['status'] = 0; 
			$data['result'] = "获取比率失败";			
		}	 
		$this->ajaxReturn($data);
	}					
}