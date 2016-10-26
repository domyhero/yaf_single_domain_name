<?php
use services\LuckyService;
use common\YCore;
use winer\Paginator;
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

    /**
     * 抽奖记录列表。
     */
    public function recordAction() {
        $username    = $this->getString('username', '');
        $mobilephone = $this->getString('mobilephone', '');
        $goods_name  = $this->getString('goods_name', '');
        $goods_type  = $this->getString('goods_type', '');
        $page = $this->getInt(YCore::appconfig('pager'), 1);
        $list = LuckyService::getAdminLuckyPrizeList($username, $mobilephone, $goods_name, $goods_type, $page, 20);
        $paginator = new Paginator($list['total'], 20);
        $page_html = $paginator->backendPageShow();
        $this->_view->assign('page_html', $page_html);
        $this->_view->assign('list', $list['list']);
        $this->_view->assign('username', $username);
        $this->_view->assign('mobilephone', $mobilephone);
        $this->_view->assign('goods_name', $goods_name);
        $this->_view->assign('goods_type', $goods_type);
        $this->_view->assign('goods_type_dict', LuckyService::$goods_type_dict);
    }

    /**
     * 抽奖记录删除。
     */
    public function deleteRecordAction() {
        $id = $this->getInt('id');
        LuckyService::deletePrizeRecord($this->admin_id, $id);
        $this->json(true, '删除成功');
    }

    /**
     * 发送奖励。
     */
    public function sendPrizeAction() {
        if ($this->_request->isPost()) {
            $id   = $this->getInt('id');
            $data = $this->getArray('data');
            LuckyService::sendAward($this->admin_id, $id, $data);
            $this->json(true, '发送成功');
        }
        $id = $this->getInt('id');
        $detail = LuckyService::getAdminLuckyPrizeDetail($id);
        $logistics_list_dict = YCore::dict('logistics_list');
        $this->_view->assign('detail', $detail);
        $this->_view->assign('logistics_list_dict', $logistics_list_dict);
    }
}