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
        $mobile = I('mobile'); //搜索使用
		$login = M('Join')->where('uid = '.$gid)->find();
        $puid = $gid;
        $puid = trim($puid); 
        if ($puid) {
            
            $Model = new \Think\Model(); // 实例化一个model对象 没有对应任何数据表
            if($login['join_type']==='0'){
                $list = $Model->query("select id,username,nickname,mobile,uid,m.last_login_ip,m.last_login_time,parent_id from  __PREFIX__ucenter_member as u,__PREFIX__member as m where u.id=m.uid and root_id = " .$puid);
		    }else{ //需要加入分页
		          $uids = self::getAllUidsByParent($puid);
		          array_push($uids,$puid);
		          $ids = implode(',',$uids);
		          $list = $Model->query("select id,username,nickname,mobile,uid,m.last_login_ip,m.last_login_time,parent_id from  __PREFIX__ucenter_member as u,__PREFIX__member as m where u.id=m.uid and parent_id in (" .$ids." )");    
		       	         
		     }
		    
		        $page = new \Think\Page('100', '20', $REQUEST);
                if($total>$listRows){
                    $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
                }
                $p =$page->show();
                $this->assign('_page', $p? $p: '');  
		    
             int_to_string($list);

             $this->assign('_list' , $list);
             $this->assign('puid',$puid);
        }else{
               $this->display();
        }		
      
        $this->meta_title = '用户信息';
        $this->display();
    }

    private static function getUidsByjoin($pid){
        global $memberlist; 
    	$map['parent_id']=$pid; 
    	$members = M('Join')->field('uid')->where($map)->select();
    	//根据id获取下级用户
    	if($members){
    		if($memberlist){
    			$memberlist = array_merge($memberlist, $members);
    		}else{
    			$memberlist = $members;	
    		}
    		foreach ($members as $k => $v ) {
    			self::getUidsByjoin($v['uid']);
    		}	
    	}
	    return $memberlist;
            
    }
    
    private static function getAllUidsByParent($pid){
        if($pid){
            $joinuids = self::getUidsByjoin($pid);
		     if($joinuids){
		        foreach($joinuids as $k=>$v){
		            $uids[] = $v['uid'];    
		        }
		            
		            return $uids;
		     }
		}
            return false;
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
        $pid = I('pid');
        $agent_login = $pid ? $pid : $agent_login;// 查看下级业绩 
        $login = M('Join');
        $Member = D('Member');
        $loginfo = $login->where(sprintf('uid = %d',$agent_login))->find();
		if($loginfo['join_type'] === '0'){//总代理,可以一次把所有用户数据取出,收益比例可能有特别
		     $alluser = $Member->where(sprintf('root_id = %d or uid = %d',$agent_login,$agent_login))->getField('uid,nickname',true); 
             $uids = array_merge(array_keys($alluser),array((int)$loginfo['uid']));//包括用户本身
		}else{ //非一级代理用户
		    
		     $uids = self::getAllUidsByParent($agent_login);
		     $uids = array_merge($uids,array((int)$agent_login));//代理本身需要计算进来
		     $alluser = $Member->where(sprintf('uid in (%s)',implode(',', $uids)))->getField('uid,nickname',true);
		}
		//var_dump($uids);exit;
	    //$this->assign('userinfo' , $info);	
		if(empty($uids)){
		    $this->meta_title = '佣金记录';
            $this->display();
		}
		$map['uid']  = array('in',$uids);
		//$map['status']  = 1;//1佣金 2购买
		
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
		//$zong =M('AccountLog')->where($map)->Sum('money_p');var_dump($zong);exit;
		$cur_ratio =$loginfo["ratio"];
		
		//整理数据
		
		//var_dump($alogs);exit;				
		//计算总订单金额
		$allorder = M('WinOrder')->where($map)->Sum('money');
		$all_order = M('WinOrder')->where($map)->getField('id,num,uid,money,create_time,period,num,number_section');
		$alogs = $all_order;
		if($all_order){
		    $zmap['order_id'] =array('in',array_keys($all_order));
		    $is_winstate = M('WinExchange')->where($zmap)->getField('goods_id,order_id,buy_num',true);
    		foreach($alogs as $k=>$v){
    		    $alogs[$k]['nickname'] = $alluser[$v['uid']];
    		    if($is_winstate){
    		        foreach($is_winstate as $kk=>$vv){
    		            if($k == $vv['order_id']){
    		                  $alogs[$k]['is_win'] = '中奖';  break;
    		            }else{
    		                   $alogs[$k]['is_win'] = '未中奖';  
    		            }    
    		        }
    		    }else{  
    		        $alogs[$k]['is_win'] = '未中奖';
    		    }
    		}
      
    		//计算中奖金额
    		
    		$zmap['is_exchange'] = array('eq',1); 
    		//支出金额
    		
    		if($loginfo['ratio_type'] ==2){
    		    $is_win = M('WinExchange')->where($zmap)->getField('goods_id,buy_num',true); //中奖与否
    		    if($is_win){
        		    $zjmap['id'] = array('in',array_keys($is_win));
        		    $zjorder = M('Document')->where($zjmap)->getField('id,real_price');
        		    foreach ($is_win as $k=>$v) {//总支出
                         $zhichu =bcadd($zhichu,bcmul($zjorder[$k],$v,4),4);
                    } 
    		    }
    		    $zhichu = $zhichu ? $zhichu : 0;
    		    $lirun = bcsub($allorder,$zhichu,4);
    		    $sy = bcmul($lirun,bcdiv($cur_ratio,100,4),4);
    	    }else{
    	        $sy =   bcmul($allorder,bcdiv($cur_ratio,100,4),4);  
    	    }
    		//按比例收益,这地方计算不对，应该是减去商品本身的价格
	    }
		$this->assign('uname',$loginfo['name']);
		$this->assign('cur_ratio',$cur_ratio);
		$this->assign('sy',$sy);
		$this->assign('zj',$zhichu);
		$this->assign('licount',$lirun);
		$this->assign('zongcount',$allorder);
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
    * 批量生成二维码，工具方法
    **/
    public function makeErm(){
        $join = M('Join');
        $data = $join->where('1=1')->select();
        foreach($data as $k=>$v){
            $uid = $v['uid'];
            $v['root_id'] = $v['root_id'] ? $v['root_id'] : $uid;
            $shareurl ='http://' . $_SERVER['HTTP_HOST'] . '/Weixin/User/register/parent_id/'.$uid.'/root_id/'.$v['root_id'];
            $url = $this->makeShareCode($uid,$shareurl);
            $arr['erm'] = $url;
            $ret = $join->where('uid = '.$uid)->save($arr);
        }
    }
    
     private function makeShareCode($uid,$shareurl){
        
        $userinfo = M('Member')->where(array('uid'=>$uid))->find();
         $weixin = (C('weixin'));
      
            $oldpic = $userinfo['headimgurl'] ? $userinfo['headimgurl'] : './Public/Weixin/erweima/logo.png';
            
            $wurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$weixin['appid']."&redirect_uri=".$shareurl."&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
            //var_dump($wurl);exit($wurl);
            $url = $this->makeCodeLogo('DL'.$uid,$oldpic,$wurl);
            $res['ewm']  = ltrim($url,'.');
            M('BrandingMember')->where(array('id'=>$buid))->save($res);
        
       // print_r($url);exit;
            return $url;
        
    }
    public function makeCodeLogo($uid,$oldpic,$shareurl){
        		//var_dump($uid);die();
        vendor("phpqrcode");
        $value = $shareurl;
        $errorCorrectionLevel = 'L';//纠错级别：L、M、Q、H
        $matrixPointSize = 10;//二维码点的大小：1到10
        $ewm = "./Public/Weixin/erweima/".$uid.".png";
        \QRcode::png ( $value, $ewm, $errorCorrectionLevel, $matrixPointSize, 2 );//不带Logo二维码的文件名
        $logo = $oldpic ? $oldpic :"http://".$_SERVER['HTTP_HOST']."/Public/Weixin/img/erweima_logo.png";;//需要显示在二维码中的Logo图像
        $QR = ltrim($ewm,'.');
//        dump($logo);

        if ($logo !== FALSE) {
            $QR = imagecreatefromstring ( file_get_contents ( 'http://' . $_SERVER['HTTP_HOST']."/".$QR ) );
//            $logo = imagecreatefromstring ( file_get_contents ('http://' . $_SERVER['HTTP_HOST']."/".$logo) );

            $logo = imagecreatefromstring($this->get_by_curl($logo));
            if (imageistruecolor($logo)) imagetruecolortopalette($logo, false, 65535);
//            $logo = imagecreate($this->get_by_curl($logo));
//            dump($logo);die();
            $QR_width = imagesx ( $QR );
            $QR_height = imagesy ( $QR );
            $logo_width = imagesx ( $logo );
            $logo_height = imagesy ( $logo );
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            imagecopyresampled ( $QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height );
        }
        $logoCode = "./Public/Weixin/erweima/logo_".$uid.".png";
        $res = imagepng ($QR, $logoCode);//带Logo二维码的文件名
       
        @unlink($ewm);
        return $logoCode;
    }


    public function get_by_curl($url,$post = false){
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if($post){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
		}
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	public function updateRootIdByParent(){
	        
	    $join = M('Join');
	    $mem = M('Member');
        $data = $join->where('1=1')->select();
        foreach($data as $k=>$v){
            $isp = $mem->where('parent_id = '.$v['uid'])->getField('uid',true); 
            if($isp){
                $map['uid'] = array('in',$isp);
                $datap['root_id'] = $v['uid'];
                $mem->where($map)->save($datap);             
                error_log(print_r($isp,true).'update-->'.$uid.'-->root_id:'.$v['uid']."\n\t",3,'/home/tmp/uprootid.log');
            }
        }
	}
}