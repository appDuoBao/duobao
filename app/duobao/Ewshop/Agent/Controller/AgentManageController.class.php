<?php
// +----------------------------------------------------------------------
// | 微信管理系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010-2017  All rights reserved.
// +----------------------------------------------------------------------
// | Author:
// +----------------------------------------------------------------------

namespace Agent\Controller;
use User\Api\UserApi;

/**
 * 后台用户控制器
 * @author ew_xiaoxiao
 */
class AgentManageController extends ControlController {

    /**
     * 用户管理首页
     * @author ew_xiaoxiao
     */
    public function index(){
        //当前管理员id
        $gid = $_SESSION['user_agent']['id'];
        
		
        $puid = $gid;
        $puid = trim($puid); 
        if ($puid) {
            
            $Model = new \Think\Model(); // 实例化一个model对象 没有对应任何数据表
            $list = $Model->query("select id,username,nickname,mobile,uid,m.last_login_ip,m.last_login_time,parent_id from  __PREFIX__ucenter_member as u,__PREFIX__member as m where u.id=m.uid and root_id = " .$puid);
		      // print_r($list);exit;
             int_to_string($list);

             $this->assign('_list' , $list);
             $this->assign('puid',$puid);
        }else{
               $this->display();
        }		
       
        
		
        $this->meta_title = '用户信息';
        $this->display();
    }


    /**
     * 代理商管理
     * @author ew_xiaoxiao
     */
    public function agentlist(){

        $gid = $_SESSION['user_agent']['id'];
        if($gid){
    		$list = M('Join')->where(array('is_delete'=>0,'status'=>1,'parent_id'=>$gid))->select();
    		
            int_to_string($list);
            $this->assign('_list' , $list);
            $this->meta_title = '代理商信息';
        }
        $this->display();
    }
    
    public function childset(){
        $aid = I('aid');
        $com = I('commis') ? I('commis') :($_POST['commis']);
        $agent_login =$_SESSION['user_agent']['id'];
        if($aid && $agent_login){
          $joinobj = M('Join');
           $info = $joinobj->where(sprintf('id = %d',$aid))->find();
           $login = $joinobj->where(sprintf('id = %d',$agent_login['id']))->find();  
          if($com){
            $bc = bccomp($login['ratio'],$com,2);
            if($bc < 0){
                $this->error('设置分成比例不能大于自己的分成比例');
            }
            $data['ratio'] = $com;
            $ret = $joinobj->where(sprintf('id = %d',$aid))->save($data);
            if($ret)  $this->success('修改成功！', U('AgentManage/agentlist'));
          }
           
          $this->assign('info',$info); 
          $this->assign('aid',$aid); 
        }
        $this->display();
    }
	
    /**
     * 分享会员
     * @author ew_xiaoxiao
     */
    public function childlist($puid = NULL){
		
		$gid = I('aid');
		if($gid){
    		$list = M('Join')->where(array('is_delete'=>0,'status'=>1,'parent_id'=>$gid))->select();
    		
            int_to_string($list);
            $this->assign('_list' , $list);
            $this->assign('pid',$gid);
            $this->assign('lid',$_SESSION['user_agent']['uid']);
            $this->meta_title = '代理商信息';
        }
        $this->display();
        
    }	
    /**
     * 分佣明细
     * @author ew_xiaoxiao
     */
    public function orderlist($puid = NULL){
        
        $agent_login =$_SESSION['user_agent']['id'];
        $login = M('Join');
        $Member = D('Member');
        $loginfo = $login->where(sprintf('id = %d',$agent_login))->find();
		if($loginfo['join_type'] === '0'){//总代理,可以一次把所有用户数据取出,收益比例可能有特别
		     $alluser = $Member->where(sprintf('root_id = %d',$agent_login))->getField('uid,nickname',true);  
		     $uids = array_keys($alluser); 
		}else{ //非一级代理用户
		    
		     $uids = getfxuser($agent_login);    
		}
		
	    //$this->assign('userinfo' , $info);	
		
		$map['pid']  = array('in',$uids);
		$map['status']  = 1;//1佣金 2购买
		
		$start_date      = I('start_date');
		$end_date      = I('end_date');
		if($start_date && $end_date){
			$map['create_time'] = array(array('egt',strtotime($start_date)),array('lt',strtotime($end_date)+(24*60*60)));
		}elseif($start_date  && empty($end_date)){
			$map['create_time']  = array('egt',strtotime($start_date));
		}elseif($end_date && empty($start_date)){
			$map['create_time']  = array('lt',strtotime($end_date)+(24*60*60));
		}		
		//佣金总金额
		//$zong =M('AccountLog')->where($map)->Sum('money_p');
		$cur_ratio =$loginfo["ratio"];
		$alogs = M('AccountLog')->where($map)->select();
		$zong = 0 ;
        foreach ($alogs as $key => $aval) {
			$order_money = bcdiv($aval['money_p'],bcdiv($aval['ratio'],100));
			$zong =bcadd($zong,bcmul($order_money,bcdiv($cur_ratio,100)));
        }	
		$this->assign('zong' , $zong);
		$this->assign('tdzong' , $tdzong);
		
		//总销售金额（包含自己的销售额+所有下级的销售额）
		//$zongcount = getxse($fxuids);
		//$this->assign('zongcount' , $zongcount);			
		
        foreach ($alogs as $key => $val) {
            $alogs[$key]['nickname'] = $alluser[$val['uid']];
			$order_money = bcdiv($val['money_p'],bcdiv($val['ratio'],100));
			$alogs[$key]['ratio']=$cur_ratio;
            $alogs[$key]['money_p'] = bcmul($order_money,bcdiv($cur_ratio,100));	
        }				
		//var_dump($alogs);exit;
        $this->assign('_list' , $alogs);
        $this->meta_title = '佣金记录';
        $this->display();
    }
	
    /**
     * 添加会员信息
     * @author ew_xiaoxiao
     */
    public function add(){
        $loginuid = $_SESSION['onethink_admin']['user_auth']['uid'];
        if (IS_POST) {
            /* 检测密码 */
            $password = I('password');
            $repassword = I('repassword');
            if ($password != $repassword) {
                $this->error('密码和重复密码不一致！');
            }

            /* 调用注册接口注册用户 */
            //$User = new UserApi;
            //$uid  = $User->register($username , $password , $email);
            
            $puid = I('puid');
            $username = I('username');
            $mobile = I('mobile');
            $email = I('email');
            if ($puid == $loginuid) { //注册成功
                $user = array ('puid' => $puid , 'username' => $username ,'password'=>think_ucenter_md5($password,UC_AUTH_KEY), 'moblie' => $moblie,'email'=>$email);
                if (!M('BrandingMember')->add($user)) {
                    $this->error('用户添加失败！');
                } else {
                    $this->success('用户添加成功！' , U('index'));
                }
            } else { //注册失败，显示错误信息
                $this->error($this->showRegError($uid));
            }
        } else {
            $this->meta_title = '新增用户';
            //$puid = I('puid','');
            $this->assign('puid',$loginuid);
            $this->display();
        }
    }

    /**
     * 修改会员信息
     * @author ew_xiaoxiao
     */
    public function edit($id = NULL){
        $Member = D('BrandingMember');

        if (IS_POST) {
            $data['id']      = $_POST['id'];
            $yzdata['password']   = $_POST['password'];
            $yzdata['repassword'] = $_POST['repassword'];
            $udata['email']       = $_POST['email'];
            $udata['mobile']      = $_POST['mobile'];
            $udata['id']          = $_POST['id'];

            //提交表单
            if ($yzdata['password'] || $yzdata['repassword']) {

                $udata['password']   = $yzdata['password'];
                $udata['repassword'] = $yzdata['repassword'];
                /* 检测密码 */
                if ($udata['password'] != $udata['repassword']) {
                    $this->error('密码和重复密码不一致！');
                }

               
                
            } else {
               
                    if (FALSE !== $Member->updatem($data)) {
                        $this->success('编辑成功！' , U('index'));
                    } else {
                        $error = $Member->getError();
                        $this->error(empty($error) ? '未知错误！' : $error);
                    }
               
            }
        } else {
            $info             = $Member->where('id = '.$id)->find();;
            $this->assign('info' , $info);
            $this->meta_title = '编辑用户';
            $this->display();
        }
    }

    /**
     * 修改会员信息
     * @author ew_xiaoxiao
     */
    public function resetps(){ var_dump($mobile,$password);exit;
        $mobile = I('username');
        $password = I('password');
        $repassword = I('repassword');
        if($mobile){
            $Member = D('UcenterMember');
            if($password != $repassword){
                $this->error('修改失败,用户密码不一致！');  
                    
            }
    			$data['password'] = think_ucenter_md5($password,UC_AUTH_KEY);//公司名称
    			$ret = Member->where(array('username'=>$moblie))->save($data);			
    			if($ret){
    			     $this->success('修改成功！' , U('Public/login'));    
    			}
	    }
		$this->display();
    }
	
    


}