<?php
/**
 * 订论列表。
 * @author winerQin
 * @date 2016-06-10
 */
use services\AppraiseService;
use common\YCore;
use winer\Paginator;

class CommentController extends \common\controllers\Admin {

    /**
     * 评论列表。
     */
    public function listAction() {
        $goods_id = $this->getInt('goods_id', -1);
        $order_sn = $this->getString('order_sn', '');
        $evaluate_level = $this->getInt('evaluate_level', -1);
        $username = $this->getString('username', '');
        $mobilephone = $this->getString('mobilephone', '');
        $page = $this->getInt(YCore::appconfig('pager'), 1);
        $list = AppraiseService::getBackendAppraiseList($goods_id, $order_sn, $evaluate_level, $username, $mobilephone, $page, 20);
        $paginator = new Paginator($list['total'], 20);
        $page_html = $paginator->backendPageShow();
        $this->_view->assign('page_html', $page_html);
        $this->_view->assign('list', $list['list']);
        $this->_view->assign('goods_id', $goods_id);
        $this->_view->assign('order_sn', $order_sn);
        $this->_view->assign('evaluate_level', $evaluate_level);
        $this->_view->assign('username', $username);
        $this->_view->assign('mobilephone', $mobilephone);
    }

    /**
     * 隐藏评论。
     */
    public function deleteAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $comment_id = $this->getInt('comment_id');
            AppraiseService::sellerHideComment($this->admin_id, $comment_id);
            $this->json(true, '操作成功');
        }
    }

    /**
     * 评价回复。
     */
    public function replyAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $comment_id = $this->getInt('comment_id');
            $sub_order_id = $this->getInt('sub_order_id');
            $reply_type = $this->getInt('reply_type');
            $comment = $this->getInt('comment');
            AppraiseService::sellerAppraiseReply($this->admin_id, $sub_order_id, $reply_type, $comment);
            $this->json(true, '回复成功');
        }
    }
}