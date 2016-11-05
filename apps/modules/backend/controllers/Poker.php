<?php
/**
 * 扑克王活动管理。
 * @author winerQin
 * @date 2016-11-05
 */

use services\PokerKingService;
use common\YCore;
use winer\Paginator;

class PokerController extends \common\controllers\Admin {

    /**
     * 参与记录。
     */
    public function recordAction() {
        $username    = $this->getString('username', '');
        $mobilephone = $this->getString('mobilephone', '');
        $is_prize    = $this->getInt('is_prize', -1);
        $poker_type  = $this->getInt('poker_type', -1);
        $page = $this->getInt(YCore::appconfig('pager'), 1);
        $list = PokerKingService::getAdminPokerKingRecordList($username, $mobilephone, $poker_type, $is_prize, $page, 20);
        $paginator = new Paginator($list['total'], 20);
        $page_html = $paginator->backendPageShow();
        $this->_view->assign('page_html', $page_html);
        $this->_view->assign('list', $list['list']);
        $this->_view->assign('username', $username);
        $this->_view->assign('mobilephone', $mobilephone);
        $this->_view->assign('is_prize', $is_prize);
        $this->_view->assign('poker_type', $poker_type);
    }
}