<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Controller;

/**
 * 后台地区管理配置控制器
 * @author ew_xiaoxiao <www@ewangtx.com>
 */
class AreaController extends ControlController {

    /**
     * 地区管理
     * author 一网天行 <www@ewangtx.com>
     */
    public function area($id = null){
		$id = I('get.id');
		if($id){
		$map['pid'] = $id;
		$list = $this->lists('Area',$map,'id asc');
			
		}else{
        /* 查询条件初始化 */
		$id = 0;
		$map['pid'] = $id;
		$list = $this->lists('Area', $map,'id asc');
		}
		$this->assign('pid', $id);
        $this->assign('list', $list);
		
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '地区管理';
        $this->display();
    }
	
	
	    /* 添加编辑地区 */
    public function areaedit($id = null,$pid = null){
        $comparea = D('Area');
        if(IS_POST){ //提交表单
            if(false !== $comparea->update()){
                $this->success('保存成功！', U('area?id='.I('post.pid')));
            } else {
                $error = $comparea->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
			
            $info = $id ? $comparea->info($id) : '';
			$this->assign('pid',       $pid);
            $this->assign('info',       $info);
            $this->meta_title = $id ? '添加地区' : '编辑地区';
            $this->display();
        }
    }

	public function areadel(){
        if(IS_POST){
            $ids = I('post.id');
            $comparea = M("Area");
            if(is_array($ids)){
				foreach($ids as $id){
				$map['pid'] = $id;
				$result = $comparea->where($map)->select();
					if($result){
					$i = 1;	
					break;
					}else{
					$comparea->where("id='$id'")->delete();	
					$i=2;
					}

				}
            }
			if($i==2){
				   $this->success("删除成功！");
			}else{
				$this->error("部分地区无法删除，请先清除对应的子栏目后，再进行删除");
			}
         
        }else{
            $id = I('get.id');
            $db = M("Area");
            $status = $db->where("id='$id'")->delete();
            if ($status){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }	
	
	//地区联动菜单
	public function changearea(){
		$comparea = M('area');
		$pid = htmlspecialchars($_POST["pid"]);
		if($pid){	
			$map['pid'] = $pid;
			$result = $comparea->where($map)->select();
			if($result){
				$data['msg'] = 'yes';	
				$data['list'] = $result;
			}else{
				$data['msg'] = 'no';	
			}
			$this->ajaxReturn($data);
		}else{
			$data['msg'] = 'no';	
			$this->ajaxReturn($data);
		}
    }	
}