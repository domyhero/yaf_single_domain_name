<?php
use services\LuckyService;
/**
 * 抽奖活动管理。
 * @author winerQin
 * @date 2016-10-25
 */

class LuckyController extends \common\controllers\Admin {

    /**
     * 活动列表。
     */
    public function listAction() {
        $list = LuckyService::getAdminLuckyGoodsList();
        $goods_type_dict = LuckyService::$goods_type_dict;
        $this->_view->assign('goods_type_dict', $goods_type_dict);
        $this->_view->assign('list', $list);
    }

    /**
     * 设置活动奖品。
     */
    public function setAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $goods = $this->getArray('goods');
            LuckyService::setLuckyGoods($this->admin_id, $goods);
            $this->json(true, '保存成功');
        }
    }
}