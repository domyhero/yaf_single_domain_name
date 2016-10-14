<?php
use common\YCore;
use services\LotteryService;
use winer\Paginator;
/**
 * 彩票活动管理。
 * @author winerQin
 * @date 2016-10-14
 */

class LotteryController extends \common\controllers\Admin {

    /**
     * 活动列表。
     */
    public function listAction() {
        $activity_status = $this->getString('activity_status', -1);
        $lottery_type = $this->getString('lottery_type', -1);
        $display = $this->getInt('display', -1);
        $page = $this->getInt(YCore::appconfig('pager'), 1);
        $list = LotteryService::getAdminLotteryActivityList($activity_status, $lottery_type, $display, $page, 20);
        $paginator = new Paginator($list['total'], 20);
        $page_html = $paginator->backendPageShow();
        $this->_view->assign('page_html', $page_html);
        $this->_view->assign('list', $list['list']);
        $this->_view->assign('activity_status', $activity_status);
        $this->_view->assign('display', $display);
        $this->_view->assign('lottery_type', $lottery_type);
    }

    /**
     * 添加活动。
     */
    public function addAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $start_time      = $this->getString('start_time');
            $end_time        = $this->getString('end_time');
            $title           = $this->getString('title');
            $lottery_type    = $this->getInt('lottery_type');
            $bet_number      = $this->getString('bet_number');
            $person_limit    = $this->getInt('person_limit');
            $open_apply_time = $this->getString('open_apply_time');
            $display         = $this->getInt('display');
            LotteryService::addLotteryActivity($title, $bet_number, $lottery_type, $person_limit, $open_apply_time, $start_time, $end_time, $display, $this->admin_id);
            $this->json(true, '添加成功');
        }
    }

    /**
     * 编辑活动。
     */
    public function editAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $aid             = $this->getInt('aid');
            $start_time      = $this->getString('start_time');
            $end_time        = $this->getString('end_time');
            $title           = $this->getString('title');
            $lottery_type    = $this->getInt('lottery_type');
            $bet_number      = $this->getString('bet_number');
            $person_limit    = $this->getInt('person_limit');
            $open_apply_time = $this->getString('open_apply_time');
            $display         = $this->getInt('display');
            LotteryService::editLotteryActivity($aid, $title, $bet_number, $lottery_type, $person_limit, $open_apply_time, $start_time, $end_time, $display, $this->admin_id);
            $this->json(true, '保存成功');
        }
        $aid = $this->getInt('aid');
        $detail = LotteryService::getAdminLotteryActivityDetail($aid);
        $this->_view->assign('detail', $detail);
    }

    /**
     * 删除活动。
     */
    public function deleteAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $aid = $this->getInt('aid');
            LotteryService::deleteLotteryActivity($aid, $this->admin_id);
            $this->json(true, '保存成功');
        }
    }

    /**
     * 彩票活动参与用户列表。
     */
    public function usersAction() {
        $aid = $this->getInt('aid');
        $username = $this->getString('username', '');
        $mobilephone = $this->getString('mobilephone', '');
        $page = $this->getInt(YCore::appconfig('pager'), 1);
        $list = LotteryService::getLotteryActivityUserList($aid, $mobilephone, $username, $page, 20);
        $paginator = new Paginator($list['total'], 20);
        $page_html = $paginator->backendPageShow();
        $this->_view->assign('page_html', $page_html);
        $this->_view->assign('list', $list['list']);
        $this->_view->assign('username', $username);
        $this->_view->assign('mobilephone', $mobilephone);
        $this->_view->assign('aid', $aid);
    }
}