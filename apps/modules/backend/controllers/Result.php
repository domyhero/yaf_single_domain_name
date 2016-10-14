<?php
use common\YCore;
use winer\Paginator;
use services\LotteryService;
/**
 * 开奖结果。
 * @author winerQin
 * @date 2016-10-14
 */

class ResultController extends \common\controllers\Admin {

    /**
     * 开奖结果列表。
     */
    public function listAction() {
        $lottery_type = $this->getString('lottery_type', -1);
        $display = $this->getInt('display', -1);
        $page = $this->getInt(YCore::appconfig('pager'), 1);
        $list = LotteryService::getLotteryResultList($lottery_type, $page, 20);
        $paginator = new Paginator($list['total'], 20);
        $page_html = $paginator->backendPageShow();
        $this->_view->assign('page_html', $page_html);
        $this->_view->assign('list', $list['list']);
        $this->_view->assign('display', $display);
        $this->_view->assign('lottery_type', $lottery_type);
    }

    /**
     * 添加开奖结果。
     */
    public function addAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $lottery_type       = $this->getInt('lottery_type');
            $phase_sn           = $this->getString('phase_sn');
            $lottery_result     = $this->getString('lottery_result');
            $first_prize        = $this->getInt('first_prize');
            $second_prize       = $this->getInt('second_prize');
            $first_prize_count  = $this->getInt('first_prize_count');
            $second_prize_count = $this->getInt('second_prize_count');
            $third_prize_count  = $this->getInt('third_prize_count');
            $fourth_prize_count = $this->getInt('fourth_prize_count');
            $fifth_prize_count  = $this->getInt('fifth_prize_count');
            $sixth_prize_count  = $this->getInt('sixth_prize_count');
            $lottery_time       = $this->getString('lottery_time');
            LotteryService::addLotteryResult($lottery_type, $phase_sn, $lottery_result, $first_prize, $second_prize, $first_prize_count, $second_prize_count, $third_prize_count, $fourth_prize_count, $fifth_prize_count, $sixth_prize_count, $lottery_time, $this->admin_id);
            $this->json(true, '添加成功');
        }
    }

    /**
     * 编辑开奖结果。
     */
    public function editAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $id                 = $this->getInt('id');
            $lottery_type       = $this->getInt('lottery_type');
            $phase_sn           = $this->getString('phase_sn');
            $lottery_result     = $this->getString('lottery_result');
            $first_prize        = $this->getInt('first_prize');
            $second_prize       = $this->getInt('second_prize');
            $first_prize_count  = $this->getInt('first_prize_count');
            $second_prize_count = $this->getInt('second_prize_count');
            $third_prize_count  = $this->getInt('third_prize_count');
            $fourth_prize_count = $this->getInt('fourth_prize_count');
            $fifth_prize_count  = $this->getInt('fifth_prize_count');
            $sixth_prize_count  = $this->getInt('sixth_prize_count');
            $lottery_time       = $this->getString('lottery_time');
            LotteryService::editLotteryResult($id, $lottery_type, $phase_sn, $lottery_result, $first_prize, $second_prize, $first_prize_count, $second_prize_count, $third_prize_count, $fourth_prize_count, $fifth_prize_count, $sixth_prize_count, $lottery_time, $this->admin_id);
            $this->json(true, '保存成功');
        }
        $id = $this->getInt('id');
        $detail = LotteryService::getLotteryResultDetail($id);
        $this->_view->assign('detail', $detail);
    }

    /**
     * 删除开奖结果。
     */
    public function deleteAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $id = $this->getInt('id');
            LotteryService::deleteLotteryResult($id, $this->admin_id);
            $this->json(true, '保存成功');
        }
    }
}