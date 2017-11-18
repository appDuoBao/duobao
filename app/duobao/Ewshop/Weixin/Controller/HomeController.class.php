<?php
// +----------------------------------------------------------------------
// | 微信管理系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010-2017  All rights reserved.
// +----------------------------------------------------------------------
// | Author:
// +----------------------------------------------------------------------

namespace Weixin\Controller;
use Think\Controller;


/**
 * Class HomeController
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 * @package Weixin\Controller
 * @author
 */
class HomeController extends Controller {

	/**
	 * 初始化
	 * @author
	 */
	protected function _initialize(){
		/* 读取站点配置 */
		$config = api('Config/lists');
		C($config); //添加配置

		if(!C('WEB_SITE_CLOSE')){
			$this->error('站点已经关闭，请稍后访问~');
		}
	}

	/**
	 * 用户登录检测
	 * @author
	 */
	protected function login(){
		is_login() || $this->error('您还没有登录，请先登录！', U('User/login'));
	}



	/**
	 * 空操作，用于输出404页面
	 * @author
	 */
	public function _empty(){
		$this->redirect('Index/index');
	}


	/**
	 * 根据时间的分钟值，以每10分钟一个间隔，向上取整
	 * @param $timestamp
	 * @return int
	 * @author
	 */
	function get_time_on_clock($timestamp = ''){
		$timestamp = !empty($timestamp) ? $timestamp : time();
//		$timestamp = '1492364763';
//		echo date('Y/m/d H:i:s',$timestamp);
		$time_H = date('H',$timestamp);
		$time_m = date('i',$timestamp);
//		dump($time_H);
//		dump($time_m);
		$as = 10;
		if($time_H >= 22 || $time_H < 2 || ($time_H == 2 && $time_m < 1)){
			$as = 5;
		}
//		dump($as);

		if($time_H > 2 && $time_H < 10){
			return date('Y/m/d ', $timestamp)  . '10:00:00';
		}else{
			$minute = date('i', $timestamp);
			$timestamp += $as*60;//时间戳加十分钟

			if($minute == '00'){
				$minute = $as;
			}else{
				if($minute%$as == 0){
					$minute =  ($minute/$as + 1)*$as;//分钟数除以10，然后向上取整，乘10
				}else{
					$minute =  ceil($minute/$as)*$as;//分钟数除以10，然后向上取整，乘10
				}
				if($minute == 60){//当分钟数是60，分钟数为0
					$minute = '00';
				}

			}
			return date('Y/m/d H:', $timestamp) . $minute . ':00';
		}

	}

	/**
	 * GET  POST 模拟
	 * @param $url
	 * @param bool $post
	 * @return mixed
	 * @author
	 */
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


	/**
	 *  获取开奖期数
	 * @param $lottery_time  开奖时间
	 * @author
	 * $lottery_time
	 */
	public function getPeriod($lottery_time = ''){
		$lottery_time = !empty($lottery_time) ? $lottery_time : $this->get_time_on_clock(time());
		$timestamp = !empty($timestamp) ? $timestamp : time();

//		$timestamp = '1492279082';
//		echo date('Y/m/d H:i:s',$timestamp);
//
//		$lottery_time = $this->get_time_on_clock($timestamp);

		$time_H = date('H',$timestamp);
		$time_m = date('i',$timestamp);
//		dump($time_H);
//		dump($time_m);

		$as = 10;
		if($time_H >= 22 || $time_H < 2 || ($time_H == 2 && $time_m < 1)){
			$as = 5;
			if(($time_H >= 0 && $time_H < 2) ||  ($time_H == 2 && $time_m < 1)){
				$period = 96 + (strtotime($lottery_time) - strtotime(date('Y/m/d ').'00:00:00'))/(60*$as);
			}else{
				$period = 72 + (strtotime($lottery_time) - strtotime(date('Y/m/d ') . '22:00:00')) / (60 * $as);
			}
		}else{
			$period = (strtotime($lottery_time) - strtotime(date('Y/m/d ') . '10:00:00')) / (60 * $as);
		}
//		dump($as);
//		dump($period);
		return $period;
	}

	/**
	 * 获取用户的ip信息
	 * @author
	 */
	public function getIpInfo(){
		$arr = get_ip_address();
		$ipInfo = $arr->city;
		return $ipInfo;
	}

	/**
	 * 获取随机的临时用户id
	 * @author
	 */
	public function getRandVal1(){
		$rand_uid1 = rand(9,205);
		return $rand_uid1;
	}


	/**
	 * 获取随机的临时用户id
	 * @author
	 */
	public function getRandVal3(){
		$uid_arr = array();
		$rand_uid1 = rand(9,205);
		$uid_arr[] = $rand_uid1;
		$rand_uid2 = rand(9,205);
		while(true){
			if($rand_uid1 == $rand_uid2){
				$rand_uid2 = rand(9,205);

			}else{
				break;
			}
		}
		$uid_arr[] = $rand_uid2;

		$rand_uid3 = rand(9,205);

		while(true){
			if(($rand_uid3 == $rand_uid1) || ($rand_uid3 == $rand_uid1)){
				$rand_uid3 = rand(9,205);

			}else{
				break;
			}
		}
		$uid_arr[] = $rand_uid3;
		$uid_str = implode(',',$uid_arr);
		return $uid_str;

	}

}
