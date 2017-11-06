<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------
namespace Control\Logic;

/**
 * 文档模型子模型 - 文章模型
 */
class ClotheLogic extends BaseLogic{
    /* 自动验证规则 */
    protected $_validate = array(
        array('content', 'getContent', '内容不能为空！', self::MUST_VALIDATE , 'callback', self::MODEL_BOTH),
    );

    /**
     * 获取文章的详细内容
     * @return boolean
     * @author ew_xiaoxiao <www@ewangtx.com>
     */
    protected function getContent(){
        $type = I('post.type');
        $content = I('post.content');
        if($type > 1){//主题和段落必须有内容
            if(empty($content)){
                return false;
            }
        }else{  //目录没内容则生成空字符串
            if(empty($content)){
                $_POST['content'] = ' ';
            }
        }
        return true;
    }

}
