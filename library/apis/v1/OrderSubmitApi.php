<?php
/**
 * 用户提交订单接口。
 * @author winerQin
 * @date 2016-10-26
 */

namespace apis\v1;

use apis\BaseApi;
use services\UserService;
use services\OrderService;
class OrderSubmitApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $token        = $this->getString('token');
        $userinfo     = UserService::checkAuth(UserService::LOGIN_MODE_API, $token);
        $user_id      = $userinfo['user_id'];
        $data = [
            'user_id'          => $user_id,
            'address_id'       => $this->getInt('address_id', -1),
            'need_invoice'     => $this->getInt('need_invoice', 0),
            'invoice_type'     => $this->getInt('invoice_type', 1),
            'invoice_name'     => $this->getString('invoice_name', ''),
            'buyer_message'    => $this->getString('buyer_message', ''),
            'is_exchange'      => $this->getInt('is_exchange', 0),
            'user_coupon_id'   => $this->getInt('user_coupon_id', 0),
            'new_address_info' => json_decode($this->getString('new_address_info', ''), true),
            'goods_list'       => json_decode($this->getString('goods_list', ''), true),
        ];
        $order_id = OrderService::submitOrder($data);
        $this->render(0, 'success', ['order_id' => $order_id]);
    }

}