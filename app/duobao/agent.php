<?php
// +----------------------------------------------------------------------
// | 微信管理系统
// +----------------------------------------------------------------------
// | Copyright (c) 2010-2017  All rights reserved.
// +----------------------------------------------------------------------
// | Author:
// +----------------------------------------------------------------------

if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

/**
 * 系统调试设置
 * 项目正式部署后请设置为false
 */
define('APP_DEBUG', true );
define('BIND_MODULE','Agent');

/**
 * 应用目录设置
 * 安全期间，建议安装调试完成后移动到非WEB目录
 */
define ( 'APP_PATH', './Ewshop/' );

if(!is_file(APP_PATH . 'User/Conf/config.php')){
	header('Location: ./install.php');
	exit;
}

/**
 * 缓存目录设置
 * 此目录必须可写，建议移动到非WEB目录
 */
define ( 'RUNTIME_PATH', './Runtime/' );

/**
 * 引入核心入口
 * ThinkPHP亦可移动到WEB以外的目录
 */

require './ThinkPHP/ThinkPHP.php';