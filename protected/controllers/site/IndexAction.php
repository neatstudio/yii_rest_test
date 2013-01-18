<?php
/**
 *
 *
 * @category
 * @package
 * @author   gouki <gouki.xiao@gmail.com>
 * @version  $Id$
 * @created 12-11-21 PM4:27
 */
class IndexAction extends CAction
{
    public function run()
    {
        /** @var $ctl SiteController */
        $ctl = $this->getController();
        $act = 'site/index'; // $act = $this->getApiPath();
        $params = array(
            /**
             * 这种方法不支持
             */
//            'array'=>array(
//                1,2,3
//            ),
            'page' => 1,
            /*
             * 需要在测试服务器现实存在的图片
             *
             * 服务器接收为$_FILES['file']
             */
            'file' => '@/var/www/test.jpg'
        );
        $data = $ctl->runAppPost($act, $params);
        $ctl->parseContent($data);
    }
}
