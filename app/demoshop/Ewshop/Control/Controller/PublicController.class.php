<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Controller;
use User\Api\UserApi;

/**
 * 后台首页控制器
 * @author ew_xiaoxiao <www@ewangtx.com>
 */
class PublicController extends \Think\Controller {

    /**
     * 后台用户登录
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    public function login($username = null, $password = null, $verify = null){
        if(IS_POST){         
            /* 检测验证码 TODO: */
            if(!check_verify($verify)){
                $this->error('验证码输入错误！');
            }
			/* 登录用户 */
			$Admin = D('Admin');
			
			if($Admin->login($username,$password)){ //登录用户
				//TODO:跳转到登录前页面
				$this->success('登录成功！', U('Control/Index/index'));
			} else {
				$this->error($Admin->getError());
			}			
        } else {
            if(is_login()){
                $this->redirect('Index/index');
            }else{
                /* 读取数据库中的配置 */
                $config	=	S('DB_CONFIG_DATA');
                if(!$config){
                    $config	=	D('Config')->lists();
                    S('DB_CONFIG_DATA',$config);
                }
                C($config); //添加配置
               
                $this->display();
            }
        }
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
