<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Controller;

/**
 * 后台客户反馈控制器
  * @author 一网天行 <www@ewangtx.com>
 */
class KuaidiController extends ControlController {

    /**
     * 客户反馈
     * author 一网天行 <www@ewangtx.com>
     */
	 
	  public function charu(){
		/* 查询条件初始化 */
		if($_SESSION['student']){
			$student = $_SESSION['student'];
		}else{
			$ch = curl_init();
			$url = 'http://apis.baidu.com/netpopo/express/express2';
			$header = array(
			'apikey: 5c14eb7863e4fb1e59e0820afb1f1e4b',
			);
			// 添加apikey到header
			curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// 执行HTTP请求
			curl_setopt($ch , CURLOPT_URL , $url);
			$res = curl_exec($ch);
			$students = json_decode($res,true);
			$student = $students['result'];

			$_SESSION['student'] = $student;
		}


		$data=array();
		$i = 0;
		var_dump($student);
		foreach($student as $k=>$v){
		$data[$i]['name']=$student[$i]['name'];
		$data[$i]['type']=$student[$i]['type'];
		$data[$i]['letter']=$student[$i]['letter'];
		$data[$i]['tel']=$student[$i]['tel'];
		$data[$i]['number']=$student[$i]['number'];
		$i++;
		if($i%10000 == 0){//每1000条数据执行一次插入。防止数据过大
		$result = M('Kuaidi')->addAll($data);
		$data = array();
		$i = 0;
		}
		}
	
		if($data){
		$result = M('Kuaidi')->addAll($data);
		die;
		if($result){
		$this->success('插入成功！', U('index'));
		}else{
		$this->success($data[1]['user_id'].'发放失败！'.$data[1]['sn'], U('index'));
		}					
		}
       
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '客户声音';
        $this->display();
    }
    public function index(){
        /* 查询条件初始化 */
	
        $map  = array();


			$list = $this->lists('Kuaidi', $map,'id desc');

        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '客户声音';
        $this->display();
    }

    /* 添加编辑公司 */
    public function edit($id = null){
		$clientsay = D('Kuaidi');
        if(IS_POST){ //提交表单
            if(false !== $clientsay->update()){
                $this->success('保存成功！', U('index'));
            } else {
                $error = $clientsay->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $info = $id ? $clientsay->info($id) : '';
            $this->assign('info',       $info);
            $this->meta_title = $id ? '添加客户声音' : '编辑客户声音';
            $this->display();
        }
    }

  	public function del(){
        if(IS_POST){
            $ids = I('post.id');
            $clientsay = M("Kuaidi");
            if(is_array($ids)){
				 foreach($ids as $id){
				 	$clientsay->where("id='$id'")->delete();	
                }
            }
            $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $db = M("Kuaidi");
            $status = $db->where("id='$id'")->delete();
            if ($status){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }	
	
	
    /**
     * 客户留言
     * author 一网天行 <www@ewangtx.com>
     */
    public function gongyi(){
        /* 查询条件初始化 */
	
        $map  = array();
			$user = M('Member');
			$list = $this->lists('Leaveword', $map,'issee asc,id desc');
			foreach($list as $k=>$v){
				if($v['uid']){
				$list[$k]['username'] = $user->getFieldByUid($v['uid'],'nickname');	
				}else{
				$list[$k]['username'] = '匿名';		
				}
			}

        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '客户留言';
        $this->display();
    }	


    /* 添加编辑留言 */
    public function editkuaidi($id = null){
		$leaveword = D('Kuaidi');
        if(IS_POST){ //提交表单
            if(false !== $leaveword->update()){
                $this->success('保存成功！', U('Kuaidi'));
            } else {
                $error = $leaveword->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
			$user = M('Member');
            $info = $id ? $leaveword->info($id) : '';
			$username = $user->getFieldByUid($info['uid'],'nickname');
			if($id){
				$map['id'] = $id;
				$data['issee'] = 1;
				$leaveword->where($map)->save($data);
			}
			if($username){
				$info['username'] = $username;
			}else{
				$info['username'] = '匿名';
			}
            $this->assign('info',       $info);
            $this->meta_title = $id ? '不存在此留言' : '查看留言';
            $this->display();
        }
    }	
	
  	public function delleaveword(){
        if(IS_POST){
            $ids = I('post.id');
            $leaveword = M("Kuaidi");
            if(is_array($ids)){
				 foreach($ids as $id){
				 	$leaveword->where("id='$id'")->delete();	
                }
            }
            $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $db = M("Kuaidi");
            $status = $db->where("id='$id'")->delete();
            if ($status){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }		
	
	
}



	