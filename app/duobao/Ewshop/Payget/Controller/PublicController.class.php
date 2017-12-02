<?php
// +----------------------------------------------------------------------
// | 微信管理系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010-2017  All rights reserved.
// +----------------------------------------------------------------------
// | Author:
// +----------------------------------------------------------------------

namespace Payget\Controller;
use User\Api\UserApi;

/**
 * 后台首页控制器
 * @author ew_xiaoxiao
 */
class PublicController extends \Think\Controller {

    /**
     * 后台用户登录
     * @author ew_xiaoxiao
     */
    public function login($username = null, $password = null, $verify = null){
        if(IS_POST){
            empty($mobile) ? $this->error("手机号码不能为空！") : '';
            $is_exist = M('UcenterMember')->where("username = {$mobile}")->find();
    	    $Member = D("Member");
            $ret = $Member->login($is_exist['id']);
            if($ret){
                $this->redirect('Index/index');  return;    
            }
           
        }
         $this->display();
       
    }

    /* 退出登录 */
    public function logout(){
        if(is_login()){
            D('Admin')->logout();
            session('[destroy]');
            $this->success('退出成功！', U('login'));
        } else {
            $this->redirect('login');
        }
    }

    public function verify(){
        $verify = new \Think\Verify();
        $verify->entry(1);
    }

}
