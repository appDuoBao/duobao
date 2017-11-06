<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------

/**
 * UCenter客户端配置文件
 * 注意：该配置文件请使用常量方式定义
 */

define('UC_APP_ID', 1); //应用ID
define('UC_API_TYPE', 'Model'); //可选值 Model / Service
define('UC_AUTH_KEY', '_/.(2;l@tx~XvF)`4aZC:f+Q1h<b*&pN%dms#g=q'); //加密KEY
define('UC_DB_DSN', 'mysql://root:ewangtx2014@localhost:3306/newewshop#utf8'); // 数据库连接，使用Model方式调用API必须配置此项
define('UC_TABLE_PREFIX', 'ewshop_'); // 数据表前缀，使用Model方式调用API必须配置此项
