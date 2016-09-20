<?php
/**
 * 优惠券管理。
 * @author winerQin
 * @date 2016-07-20
 */
use services\CouponService;
use common\YCore;
use winer\Paginator;

class CouponController extends \common\controllers\Admin {

    /**
     * 优惠券列表。
     */
    public function listAction() {
        $coupon_name = $this->getString('coupon_name', '');
        $page = $this->getInt(YCore::appconfig('pager'), 1);
        $list = CouponService::getBackendCouponList($coupon_name, $page, 20);
        $paginator = new Paginator($list['total'], 20);
        $page_html = $paginator->backendPageShow();
        $this->_view->assign('page_html', $page_html);
        $this->_view->assign('list', $list['list']);
        $this->_view->assign('coupon_name', $coupon_name);
    }

    /**
     * 添加优惠券。
     */
    public function addAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $get_start_time = $this->getString('get_start_time');
            $get_end_time   = $this->getString('get_end_time');
            $limit_quantity = $this->getInt('limit_quantity');
            $coupon_name = $this->getString('coupon_name');
            $money = $this->getInt('money');
            $order_money = $this->getInt('order_money');
            $expiry_date = $this->getString('expiry_date');
            CouponService::addCoupon($this->admin_id, $get_start_time, $get_end_time, $limit_quantity, $coupon_name, $money, $order_money, $expiry_date);
            $this->json(true, '添加成功');
        }
    }

    /**
     * 编辑优惠券。
     */
    public function editAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $coupon_id      = $this->getInt('coupon_id');
            $get_start_time = $this->getString('get_start_time');
            $get_end_time   = $this->getString('get_end_time');
            $limit_quantity = $this->getInt('limit_quantity');
            $coupon_name    = $this->getString('coupon_name');
            $money = $this->getInt('money');
            $order_money = $this->getInt('order_money');
            $expiry_date = $this->getString('expiry_date');
            CouponService::editCoupon($this->admin_id, $coupon_id, $get_start_time, $get_end_time, $limit_quantity, $coupon_name, $money, $order_money, $expiry_date);
            $this->json(true, '保存成功');
        }
        $coupon_id = $this->getInt('coupon_id');
        $detail = CouponService::getCouponDetail($coupon_id);
        $this->_view->assign('detail', $detail);
    }

    /**
     * 删除优惠券。
     */
    public function deleteAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $coupon_id = $this->getInt('coupon_id');
            CouponService::deleteCoupon($this->admin_id, $coupon_id);
            $this->json(true, '保存成功');
        }
    }

    /**
     * 发送记录。
     */
    public function historyAction() {
        $coupon_id   = $this->getInt('coupon_id', -1);
        $username    = $this->getString('username', '');
        $mobilephone = $this->getString('mobilephone', '');
        $page = $this->getInt(YCore::appconfig('pager'), 1);
        $list = CouponService::getBackendSendHistory($coupon_id, $username, $mobilephone, $page, 20);
        $paginator = new Paginator($list['total'], 20);
        $page_html = $paginator->backendPageShow();
        $this->_view->assign('page_html', $page_html);
        $this->_view->assign('list', $list['list']);
        $this->_view->assign('username', $username);
        $this->_view->assign('mobilephone', $mobilephone);
        $this->_view->assign('coupon_id', $coupon_id);
    }
}