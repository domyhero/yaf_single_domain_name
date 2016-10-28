<?php
use common\YCore;
use services\GoldService;
use winer\Paginator;
use services\PaymentService;
/**
 * 消费明细。
 * @author winerQin
 * @date 2016-10-28
 */

class CashController extends \common\controllers\Admin {

    /**
     * 现金支付记录。
     */
    public function payLogAction() {
        $page        = $this->getInt(YCore::appconfig('pager'), 1);
        $username    = $this->getString('username', '');
        $mobilephone = $this->getString('mobilephone', '');
        $start_time  = $this->getString('start_time', '');
        $end_time    = $this->getString('end_time', '');
        $list = PaymentService::getAdminPaymentLogList($username, $mobilephone, $start_time, $end_time, $page, 20);
        $paginator = new Paginator($list['total'], 20);
        $page_html = $paginator->backendPageShow();
        $this->_view->assign('page_html', $page_html);
        $this->_view->assign('list', $list['list']);
        $this->_view->assign('mobilephone', $mobilephone);
        $this->_view->assign('username', $username);
        $this->_view->assign('start_time', $start_time);
        $this->_view->assign('end_time', $end_time);
    }

    /**
     * 金币消费记录。
     */
    public function glodLogAction() {
        $consume_type = $this->getString('consume_type', -1);
        $page         = $this->getInt(YCore::appconfig('pager'), 1);
        $username     = $this->getString('username', '');
        $mobilephone  = $this->getString('mobilephone', '');
        $start_time   = $this->getString('start_time', '');
        $end_time     = $this->getString('end_time', '');
        $list = GoldService::getAdminGoldConsume($username, $mobilephone, $start_time, $end_time, $consume_type, $page, 20);
        $paginator = new Paginator($list['total'], 20);
        $page_html = $paginator->backendPageShow();
        $this->_view->assign('page_html', $page_html);
        $this->_view->assign('list', $list['list']);
        $this->_view->assign('consume_type', $consume_type);
        $this->_view->assign('mobilephone', $mobilephone);
        $this->_view->assign('username', $username);
        $this->_view->assign('start_time', $start_time);
        $this->_view->assign('end_time', $end_time);
    }
}