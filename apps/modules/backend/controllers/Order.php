<?php
/**
 * 订单列表。
 * @author winerQin
 * @date 2016-06-10
 */
use services\OrderService;
use winer\Paginator;

class OrderController extends \common\controllers\Admin {

    /**
     * 订单列表。
     */
    public function listAction() {
        $goods_id        = $this->getString('goods_id', -1);
        $receiver_name   = $this->getString('receiver_name', '');
        $receiver_mobile = $this->getString('receiver_mobile', '');
        $order_status    = $this->getInt('order_status', -1);
        $order_sn        = $this->getString('order_sn', '');
        $start_time      = $this->getString('start_time', '');
        $end_time        = $this->getString('$end_time', '');
        $page            = $this->getString('page', 1);
        $result = OrderService::getBackendOrderList($goods_id, $receiver_name, $receiver_mobile, $order_sn, $order_status, $start_time, $end_time, $page, 20);
        $paginator = new Paginator($result['total'], 20);
        $page_html = $paginator->backendPageShow();
        $this->_view->assign('page_html', $page_html);
        $this->_view->assign('list', $result['list']);
        $this->_view->assign('goods_id', $goods_id);
        $this->_view->assign('receiver_name', $receiver_name);
        $this->_view->assign('receiver_mobile', $receiver_mobile);
        $this->_view->assign('order_sn', $order_sn);
        $this->_view->assign('order_status', $order_status);
        $this->_view->assign('start_time', $start_time);
        $this->_view->assign('end_time', $end_time);
    }

    /**
     * 发货。
     */
    public function deliverGoodsAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $order_id         = $this->getInt('order_id');
            $logistics_code   = $this->getString('logistics_code');
            $logistics_number = $this->getString('logistics_number');
            OrderService::deliverGoods($this->admin_id, $order_id, $logistics_code, $logistics_number);
            $this->json(true, '发货成功');
        }
    }

    /**
     * 订单调价。
     */
    public function editPriceAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $order_id   = $this->getInt('order_id');
            $product_id = $this->getInt('product_id');
            $price = $this->getFloat('price');
            OrderService::editOrderGoodsPrice($this->admin_id, $order_id, $product_id, $price);
            $this->json(true, '调价成功');
        }
    }

    /**
     * 订单收货地址调整。
     */
    public function adjustAddressAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $order_id         = $this->getInt('order_id');
            $district_id      = $this->getInt('district_id');
            $receiver_name    = $this->getString('receiver_name');
            $receiver_address = $this->getString('receiver_address');
            $receiver_mobile  = $this->getString('receiver_mobile');
            $receiver_zip     = $this->getString('receiver_zip');
            OrderService::adjustAddress($this->admin_id, $order_id, $district_id, $receiver_name, $receiver_address, $receiver_mobile, $receiver_zip);
            $this->json(true, '调价成功');
        }
        $order_id = $this->getInt('order_id');
        $order_detail = OrderService::getShopOrderDetail($order_id);
        $this->_view->assign('order_detail', $order_detail);
    }

    /**
     * 订单运费调整。
     */
    public function adjustFreightAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $order_id = $this->getInt('order_id');
            $freight  = $this->getInt('freight');
            OrderService::adjustFreight($this->admin_id, $order_id, $freight);
            $this->json(true, '操作成功');
        }
    }

    /**
     * 删除订单。
     */
    public function deleteAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $order_id = $this->getInt('order_id');
            OrderService::deleteOrder($this->admin_id, $order_id);
            $this->json(true, '删除成功');
        }
    }

    /**
     * 关闭订单。
     */
    public function closeAction() {
        if ($this->_request->isXmlHttpRequest()) {
            $order_id = $this->getInt('order_id');
            OrderService::closeOrder($this->admin_id, $order_id);
            $this->json(true, '操作成功');
        }
    }
}