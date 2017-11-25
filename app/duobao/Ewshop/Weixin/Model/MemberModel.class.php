<?php
// +----------------------------------------------------------------------
// | 微信管理系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010-2017  All rights reserved.
// +----------------------------------------------------------------------
// | Author:
// +----------------------------------------------------------------------

namespace Weixin\Model;
use Think\Model;
use User\Api\UserApi;

/**
 * 用户模型自动完成
 * Class MemberModel
 * @package Weixin\Model
 * @author
 */
class MemberModel extends Model {

    /**
     * 用户模型自动完成
     * @var array
     * @author
     */
    protected $_auto = array (
        array ("login" , 0 , self::MODEL_INSERT) ,
        array ("reg_ip" , "get_client_ip" , self::MODEL_INSERT , "function" , 1) ,
        array ("reg_time" , NOW_TIME , self::MODEL_INSERT) ,
        array ("last_login_ip" , 0 , self::MODEL_INSERT) ,
        array ("last_login_time" , 0 , self::MODEL_INSERT) ,
        array ("status" , 1 , self::MODEL_INSERT) ,
    );


    /**
     * 登录指定用户
     * @param  integer $uid 用户ID
     * @return boolean      ture-登录成功，false-登录失败
     * @author
     */
    public function login($uid){
        /* 检测是否在当前应用注册 */
        $user = $this->field(TRUE)->find($uid);
        if (!$user) { //未注册
            $Api         = new UserApi();
            $user        = $this->create(array("status" => 1));
            $user["uid"] = $uid;
            $user["status"] = 1;
            if (!$this->add($user)) {
                $this->error = "前台用户信息注册失败，请重试！";
                return FALSE;
            }
        } elseif (1 != $user["status"]) {
            $this->error = "用户未激活或已禁用！"; //应用级别禁用
            return FALSE;
        }
        /* 登录用户 */
        $this->autoLogin($user);

        /* 登录历史 */
        history($uid);

        //记录行为
        action_log("user_login" , "member" , $uid , $uid);

        return TRUE;
    }

    /**
     * 注销当前用户
     * @return void
     * @author
     */
    public function logout(){
        $cur_uid = $_SESSION['onethink_home']['uid'];
        if(!empty($cur_uid)){
            /*当微信端用户退出时，将openid设置为空*/
            M('Member')->where("uid = {$cur_uid}")->setField('openid','');
        }
	unset($_SESSION['onethink_home']);
	unset($_SESSION['wxuser']);
        session("user_auth" , NULL);
        session("user_auth_sign" , NULL);
        session("onethink_home" , NULL);
        session("send_code" , NULL);
        session("mobile" , NULL);
        session("mobile_code" , NULL);
    }


    /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     * @author
     */
    private function autoLogin($user){
        /* 更新登录信息 */
        $data = array (
            "uid" => $user["uid"] ,
            "login" => array ("exp" , "`login`+1") ,
            "last_login_time" => NOW_TIME ,
            "last_login_ip" => get_client_ip(1) ,
        );
        $this->save($data);

        /* 记录登录SESSION和COOKIES */
        $auth = array (
            "uid" => $user["uid"] ,
            "username" => get_username($user["uid"]) ,
            "last_login_time" => $user["last_login_time"] ,
        );
        session("user_auth" , $auth);
        session("uid" , $auth["uid"]);
        session("user_auth_sign" , data_auth_sign($auth));

    }

    /**
     * 获取用户uid
     * @return mixed
     * @author
     */
    public function uid(){
        $user = session("user_auth");
        $uid  = $user["uid"];
        return $uid;
    }



}
