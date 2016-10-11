<?php
use Juhe\IpToAddr;
/**
 * 首页。
 * @author winerQin
 * @date 2016-09-07
 */

class IndexController extends \common\controllers\Guest {

    /**
     * 首页。
     * -- 1、需要静态化处理。
     */
    public function indexAction() {
        $ob = IpToAddr::query("125.93.16.111");
        print_r($ob);
        exit;
    }

    /**
     * 联系我们。
     */
    public function contactAction() {

    }

    /**
     * 关于我们。
     */
    public function aboutAction() {

    }
}