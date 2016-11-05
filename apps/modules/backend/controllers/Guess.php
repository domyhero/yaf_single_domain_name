<?php
/**
 * 竞猜活动管理。
 * @author winerQin
 * @date 2016-11-05
 */

use common\YCore;
use services\GuessService;
use winer\Paginator;

class GuessController extends \common\controllers\Admin {

    /**
     * 活动列表。
     */
    public function listAction() {
        $title      = $this->getString('title', '');
        $start_time = $this->getString('start_time', '');
        $end_time   = $this->getString('end_time', '');
        $is_open    = $this->getInt('is_open', -1);
        $page = $this->getInt(YCore::appconfig('pager'), 1);
        $list = GuessService::getAdminGuessList($title, $start_time, $end_time, $is_open, $page, 20);
        $paginator = new Paginator($list['total'], 20);
        $page_html = $paginator->backendPageShow();
        $this->_view->assign('page_html', $page_html);
        $this->_view->assign('list', $list['list']);
        $this->_view->assign('title', $title);
        $this->_view->assign('start_time', $start_time);
        $this->_view->assign('end_time', $end_time);
        $this->_view->assign('is_open', $is_open);
    }

    /**
     * 活动添加。
     */
    public function addAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $title        = $this->getString('title');
            $image_url    = $this->getString('image_url');
            $options_data = $this->getArray('options_data');
            $deadline     = $this->getString('deadline');
            GuessService::addGuess($this->admin_id, $title, $image_url, $options_data, $deadline);
            $this->json(true, '添加成功');
        }
    }

    /**
     * 添加编辑。
     */
    public function editAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $guess_id     = $this->getInt('guess_id');
            $title        = $this->getString('title');
            $image_url    = $this->getString('image_url');
            $options_data = $this->getArray('options_data');
            $deadline     = $this->getString('deadline');
            GuessService::editGuess($this->admin_id, $guess_id, $title, $image_url, $options_data, $deadline);
            $this->json(true, '修改成功');
        }
        $guess_id = $this->getInt('guess_id');
        $detail = GuessService::getAdminGuessDetail($guess_id);
        $this->_view->assign('detail', $detail);
    }

    /**
     * 活动删除。
     */
    public function deleteAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $guess_id = $this->getInt('guess_id');
            GuessService::deleteGuess($this->admin_id, $guess_id);
            $this->json(true, '删除成功');
        }
    }
    
    /**
     * 参与记录。
     */
    public function recordAction() {
        $username    = $this->getString('username', '');
        $mobilephone = $this->getString('mobilephone', '');
        $is_prize    = $this->getInt('is_prize', -1);
        $page = $this->getInt(YCore::appconfig('pager'), 1);
        $list = GuessService::getAdminGuessRecordList($username, $mobilephone, $is_prize, $page, 20);
        $paginator = new Paginator($list['total'], 20);
        $page_html = $paginator->backendPageShow();
        $this->_view->assign('page_html', $page_html);
        $this->_view->assign('list', $list['list']);
        $this->_view->assign('username', $username);
        $this->_view->assign('mobilephone', $mobilephone);
        $this->_view->assign('is_prize', $is_prize);
    }
}