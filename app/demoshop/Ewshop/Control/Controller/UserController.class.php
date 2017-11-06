<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Controller;
use User\Api\UserApi;
/**
 * 后台用户控制器
 * @author ew_xiaoxiao <www@ewangtx.com>
 */
class UserController extends ControlController {

    /**
     * 用户管理首页
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function index(){
        $nickname       =   I('nickname');
        $map['status']  =   array('egt',0);
        if(is_numeric($nickname)){
            $map['uid|nickname']=   array(intval($nickname),array('like','%'.$nickname.'%'),'_multi'=>true);
        }else{
            $map['nickname']    =   array('like', '%'.(string)$nickname.'%');
        }

        $list   = $this->lists('Member', $map);
        int_to_string($list);

        $this->assign('_list', $list);
        $this->meta_title = '用户信息';
        $this->display();
    }

    /**
     * 添加会员信息
     * @author ew_xiaoxiao <www@ewangtx.com>
     */	
    public function add($username = '', $password = '', $repassword = '', $email = ''){
        if(IS_POST){
            /* 检测密码 */
            if($password != $repassword){
                $this->error('密码和重复密码不一致！');
            }

            /* 调用注册接口注册用户 */
            $User   =   new UserApi;
            $uid    =   $User->register($username, $password, $email);
            if(0 < $uid){ //注册成功
                $user = array('uid' => $uid, 'nickname' => $username, 'status' => 1);
                if(!M('Member')->add($user)){
                    $this->error('用户添加失败！');
                } else {
                    $this->success('用户添加成功！',U('index'));
                }
            } else { //注册失败，显示错误信息
                $this->error($this->showRegError($uid));
            }
        } else {
            $this->meta_title = '新增用户';
            $this->display();
        }
     }

    /**
     * 修改会员信息
     * @author ew_xiaoxiao <www@ewangtx.com>
     */	
	 public function edit($id = null, $pid = 0){
		$Member = D('Member');

        if(IS_POST){ 
			$data['sex'] = $_POST['sex'];
			$data['uid'] = $_POST['id'];
			$data['nickname'] = $_POST['nickname'];
			$data['account'] = $_POST['account'];

			$udata['email'] = $_POST['email'];
			$udata['mobile'] = $_POST['mobile'];
			$udata['id'] = $_POST['id'];

			$password = $_POST['password'];
			$repassword = $_POST['repassword'];
			//提交表单
			if($password || $repassword){
				/* 检测密码 */
				if($password != $repassword){
					$this->error('密码和重复密码不一致！');
				}
			}	
		
			$User  = new UserApi;
			/* 检测密码 */
			if(!empty($password)){
				$udata['password'] = $User->think_ucenter_md5($password,UC_AUTH_KEY);
			}
				
			/* 调用注册接口注册用户 */
			$uid   = $User->updateInfo($udata['id'], '', $udata);
			
			if($uid['status']){
				if(false !== $Member->updatem($data)){
					$this->success('编辑成功！', U('index'));
				} else {
					$error = $Member->getError();
					$this->error(empty($error) ? '未知错误！' : $error);
				}
			}else{
				$this->error($this->showRegError($uid['info']));
			}
			
        } else {
			/* 检测密码 */
            $info = $id ? $Member->info($id) : '';
			$info['email'] = M('UcenterMember')->getFieldById($id,'email');
			$info['mobile'] = M('UcenterMember')->getFieldById($id,'mobile');
			$info['username'] = M('UcenterMember')->getFieldById($id,'username');
            $this->assign('info',       $info);
            $this->meta_title = '编辑用户';
            $this->display();
        }
    }

    /**
     * 会员状态修改
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function changeStatus($method=null){
        $id = array_unique((array)I('id',0));
        if( in_array(C('USER_ADMINISTRATOR'), $id)){
            $this->error("不允许对超级管理员执行该操作!");
        }
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['uid'] =   array('in',$id);
        switch ( strtolower($method) ){
            case 'forbiduser':
                $this->forbid('Member', $map );
                break;
            case 'resumeuser':
                $this->resume('Member', $map );
                break;
            case 'deleteuser':
                $this->delete('Member', $map );
                break;
            default:
                $this->error('参数非法');
        }
    }

    /**
     * 会员收货地址
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function address($uid = null){
        $realname = I('realname');
        if($realname){
			$map['realname'] = array('like','%'.$realname.'%');
        }
		$map['uid'] = $uid;
		$this->assign('uid', $uid);
		$addresslist   = $this->lists('transport', $map);
		foreach($addresslist as $key => $val){
			$address3 = M("area")->where("id='".$val[area]."'")->find();		
			$address2 = M("area")->where("id='".$address3[pid]."'")->find();
			$address1 = M("area")->where("id='".$address2[pid]."'")->find();
			$areas=$address1['name'];
			$areas.=$address2['name'];
			$areas.=$address3['name'];
			$addresslist[$key]['areas'] = $areas;//三级地区	
		}
		$this->assign('addnum', count($list));
		$this->assign('list', $addresslist);
		$this->meta_title = get_username().'的地址管理'; 		
        $this->display();
    }
    /**
     * 添加会员收货地址
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function addressadd(){
        if(IS_POST){ 
			$Transport = M("transport"); // 实例化transport对象

			$data['uid'] = $_POST['uid'];
			$data['realname'] = $_POST['realname'];
			$data['cellphone'] = $_POST['cellphone'];
			$data['status'] = $_POST['status'];
			$data['address'] = $_POST['address'];
			$data['youbian'] = $_POST['youbian'];
			$data['mobile'] = $_POST['mobile'];
			$data['area'] = $_POST['areaid'];
			if($data['status']=="1"){//设为默认
				//默认地址更新会员
				if($Transport->where("uid='".$data['uid']."' and status='1'")->getField("id"))
				{
					$odata['status'] = 0;
					$Transport->where("uid='".$data['uid']."'")->save($odata);
				}
			}
			if(false !== $Transport->add($data)){
				$this->success('添加成功！');
			} else {
				$this->error('添加失败！');
			}				
			
        } else {	
			$comparea = M('area');
			$map['pid'] = 0;
			$arealist = $comparea->where($map)->select();
			$this->assign('arealist',$arealist);//地区列表
			
			$this->assign('info', $info);
			$this->display();
		}
    }

    /**
     * 编辑会员收货地址
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function addressedit($id = null){
        if(IS_POST){ 
			$Transport = M("transport"); // 实例化transport对象
			$id = $_POST['id'];
			//$data['id'] = $_POST['id'];
			$data['uid'] = $_POST['uid'];
			$data['realname'] = $_POST['realname'];
			$data['cellphone'] = $_POST['cellphone'];
			$data['status'] = $_POST['status'];
			$data['address'] = $_POST['address'];
			$data['youbian'] = $_POST['youbian'];
			$data['mobile'] = $_POST['mobile'];
			$data['area'] = $_POST['areaid'];
			if($data['status']=="1"){//设为默认
				//默认地址更新会员
				if($Transport->where("uid='".$data['uid']."' and status='1'")->getField("id"))
				{
					$odata['status'] = 0;
					$Transport->where("uid='".$data['uid']."'")->save($odata);
				}
			}
			if(false !== $Transport->where("id='".$id."'")->save($data)){
				$this->success('编辑成功！');
			} else {
				$this->error('编辑失败！');
			}				
			
        } else {		
			$area = M('area');
			$map['pid'] = 0;
			$arealist = $area->where($map)->select();
			$this->assign('arealist',$arealist);//地区列表
			$info = M("transport")->where("id='".$id."'")->find();
			
			$address3 = $area->where("id='".$info['area']."'")->find();		
			$address2 = $area->where("id='".$address3['pid']."'")->find();
			$address1 = $area->where("id='".$address2['pid']."'")->find();
			$areaname = $address1['name'].$address2['name'].$address3['name'];
			$this->assign('areaname', $areaname);
						
			$this->assign('info', $info);
			$this->display();
		}
    }
    /**
     * 删除会员收货地址
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function addressdel(){
       if(IS_POST){
            $ids = I('post.id');
            $transport = M("transport");
            if(is_array($ids)){
				 foreach($ids as $id){
				 	$transport->where("id='$id'")->delete();
                 }
            }
           $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $transport = M("transport");
            $status = $transport->where("id='$id'")->delete();
            if ($status){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }	
    /**
     * 会员资金明细
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function account($uid = null){
		
		$this->assign('uid', $uid);
		$user = M("member")->where("uid='".$uid."'")->find();
		$this->assign('user', $user);		
		
		$map['uid'] = $uid;
		$list = $this->lists('walletrecord', $map);
		$this->assign('_list',$list);// 赋值数据集
		
        $this->display();
    }
    /**
     * 调整会员资金
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function accountedit($uid = null){
        if(IS_POST){ 
			$walletrecord = M("walletrecord"); 
			$data['uid'] = $_POST['uid'];//用户id
			$data['status'] = $_POST['status'];//1充值2消费
			$data['cometype'] = $_POST['cometype'];//充值方式0为消费1在线充值
			$data['money'] = $_POST['money'];//金额
			$data['note'] = $_POST['note'];//资金变动说明
			$data['changetime'] = time();//时间
			
			$user = M("member")->where("uid='".$uid."'")->find();
			$useraccount = $user['account'];//当前用户资金
			if($data['status']=="2"){//消费
				//判断用户目前资金是否大于消费金额
				if($data['money'] > $useraccount){
					$this->error('支出金额不能大于用户当前账号资金！');
					exit;
				}else{
					$userdata['account'] = $useraccount-$data['money'];
				}
			}
			if($data['status']=="1"){//充值
				$userdata['account'] = $useraccount+$data['money'];
			}	
			if(false !== $walletrecord->add($data)){
				M("member")->where("uid='".$data['uid']."'")->save($userdata);			
				$this->success('资金变更成功！');
			} else {
				$this->error('资金变更失败！');
			}				
			
        } else {	
			$user = M("member")->where("uid='".$uid."'")->find();
			$this->assign('user', $user);
			$this->display();
		}
    }	
    /**
     * 删除会员资金记录
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function accountdel(){
       if(IS_POST){
            $ids = I('post.id');
            $walletrecord = M("walletrecord");
            if(is_array($ids)){
				 foreach($ids as $id){
				 	$walletrecord->where("id='$id'")->delete();
                 }
            }
           $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $walletrecord = M("walletrecord");
            $status = $walletrecord->where("id='$id'")->delete();
            if ($status){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }			
		
	 /* 留言回复 */
    public function answer($id = null){
        $Comment = D('consult');
        if(IS_POST){ //提交表单
            if(false !== $Comment->update($data)){
                $this->success('编辑成功', U('consult'));
            } else {
                $error = $Comment->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {

            /* 获取留言信息 */
            $info = $id ? $Comment->info($id) : '';

            $this->assign('info',       $info);
            $this->meta_title = '编辑评论';
            $this->display();
        }
    }
	
	
    /**
     * 修改昵称初始化
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function updateNickname(){
        $nickname = M('Member')->getFieldByUid(UID, 'nickname');
        $this->assign('nickname', $nickname);
        $this->meta_title = '修改昵称';
        $this->display('updatenickname');
    }

    /**
     * 修改昵称提交
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function submitNickname(){
        //获取参数
        $nickname = I('post.nickname');
        $password = I('post.password');
        empty($nickname) && $this->error('请输入昵称');
        empty($password) && $this->error('请输入密码');

        //密码验证
        $User   =   new UserApi();
        $uid    =   $User->login(UID, $password, 4);
        ($uid == -2) && $this->error('密码不正确');

        $Member =   D('Member');
        $data   =   $Member->create(array('nickname'=>$nickname));
        if(!$data){
            $this->error($Member->getError());
        }

        $res = $Member->where(array('uid'=>$uid))->save($data);

        if($res){
            $user               =   session('user_auth');
            $user['username']   =   $data['nickname'];
            session('user_auth', $user);
            session('user_auth_sign', data_auth_sign($user));
            $this->success('修改昵称成功！');
        }else{
            $this->error('修改昵称失败！');
        }
    }

    /**
     * 修改密码初始化
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function updatePassword(){
        $this->meta_title = '修改密码';
        $this->display('updatepassword');
    }

    /**
     * 修改密码提交
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function submitPassword(){
        //获取参数
        $password   =   I('post.old');
        empty($password) && $this->error('请输入原密码');
        $data['password'] = I('post.password');
        empty($data['password']) && $this->error('请输入新密码');
        $repassword = I('post.repassword');
        empty($repassword) && $this->error('请输入确认密码');

        if($data['password'] !== $repassword){
            $this->error('您输入的新密码与确认密码不一致');
        }

        $Api    =   new UserApi();
        $res    =   $Api->updateInfo(UID, $password, $data);
        if($res['status']){
            $this->success('修改密码成功！');
        }else{
            $this->error($res['info']);
        }
    }

    /**
     * 用户行为列表
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function action(){
        //获取列表数据
        $Action =   M('Action')->where(array('status'=>array('gt',-1)));
        $list   =   $this->lists($Action);
        int_to_string($list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->assign('_list', $list);
        $this->meta_title = '用户行为';
        $this->display();
    }

    /**
     * 新增行为
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function addAction(){
        $this->meta_title = '新增行为';
        $this->assign('data',null);
        $this->display('editaction');
    }

	 /**
     * 分销商设置
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function power($id = null){
        $member = D('Member');
        if(IS_POST){ //提交表单
			$map['uid'] = $_POST['id'];
			$data['isdis'] = $_POST['isdis'];
            if(false !== $member->where($map)->save($data)){
                $this->success('编辑成功！', U('index'));
            } else {
                $error = $member->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $info = $id ? $member->info($id) : '';
            $this->assign('info',       $info);
            $this->meta_title = '设置分销商';
            $this->display();
        }	
    }
    /**
     * 编辑行为
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function editAction(){
        $id = I('get.id');
        empty($id) && $this->error('参数不能为空！');
        $data = M('Action')->field(true)->find($id);

        $this->assign('data',$data);
        $this->meta_title = '编辑行为';
        $this->display('editaction');
    }

    /**
     * 更新行为
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function saveAction(){
        $res = D('Action')->update();
        if(!$res){
            $this->error(D('Action')->getError());
        }else{
            $this->success($res['id']?'更新成功！':'新增成功！', Cookie('__forward__'));
        }
    }


    /**
     * 会员登录历史记录
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function loginhistory($uid = null){
		$this->assign('uid', $uid);
		$user = M("member")->where("uid='".$uid."'")->find();
		$this->assign('user', $user);		
		
		$map['uid'] = $uid;
		$list = $this->lists('History', $map);
		$this->assign('_list',$list);// 赋值数据集
		
        $this->display();
    }
    /**
     * 删除会员登录记录
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function loginhistorydel(){
       if(IS_POST){
            $ids = I('post.id');
            $history = M("History");
            if(is_array($ids)){
				 foreach($ids as $id){
				 	$history->where("id='$id'")->delete();
                 }
            }
           $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $history = M("History");
            $status = $history->where("id='$id'")->delete();
            if ($status){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }
    /**
     * 获取用户注册错误信息
     * @param  integer $code 错误编码
     * @return string        错误信息
     */
    private function showRegError($code = 0){
        switch ($code) {
            case -1:  $error = '用户名长度必须在16个字符以内！'; break;
            case -2:  $error = '用户名被禁止注册！'; break;
            case -3:  $error = '用户名被占用！'; break;
            case -4:  $error = '密码长度必须在6-30个字符之间！'; break;
            case -5:  $error = '邮箱格式不正确！'; break;
            case -6:  $error = '邮箱长度必须在1-32个字符之间！'; break;
            case -7:  $error = '邮箱被禁止注册！'; break;
            case -8:  $error = '邮箱被占用！'; break;
            case -9:  $error = '手机格式不正确！'; break;
            case -10: $error = '手机被禁止注册！'; break;
            case -11: $error = '手机号被占用！'; break;
            default:  $error = '未知错误';
        }
        return $error;
    }

}