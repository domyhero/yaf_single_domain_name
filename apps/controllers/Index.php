<?php
use services\PokerKingService;
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
        $result = PokerKingService::startDo(1, 100, mt_rand(1, 5));
        print_r($result);
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