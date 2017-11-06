<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao 
// +----------------------------------------------------------------------

namespace Control\Controller;

/**
 * 缓存控制器
 */
class CacheController extends ControlController {


    public function delcache() {
            $this->deldir(TEMP_PATH);// 应用缓存目录
			$this->deldir(DATA_PATH);// 应用数据目录
			$this->deldir(CACHE_PATH);// 应用模板缓存目录
			//$this->deldir(LOG_PATH);// 应用日志目录 一般不清理
			$this->success('系统缓存清除成功！', U('/Control/Index/index'));
    }
	public function deldir($dir) {
		$dh = opendir($dir);
		while ($file = readdir($dh)) {
			if ($file != "." && $file != "..") {
				$fullpath = $dir . "/" . $file;
				if (!is_dir($fullpath)) {
					unlink($fullpath);
				} else {
					$this->deldir($fullpath);
				}
			}
		}
	}
}