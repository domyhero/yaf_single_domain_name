<?php
/**
 * 用户订单列表接口。
 * @author winerQin
 * @date 2016-10-26
 */

namespace apis\v1;

use apis\BaseApi;
use services\UserService;
use services\OrderService;
class OrderListApi extends BaseApi {

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
        $order_sn     = $this->getString('order_sn', '');
        $order_status = $this->getInt('order_status', -1);
        $start_time   = $this->getString('start_time', '');
        $end_time     = $this->getString('end_time', '');
        $page         = $this->getInt('page', 1);
        $list         = OrderService::getUserOrderList($user_id, $order_sn, $order_status, $start_time, $end_time, $page, 20);
        $this->render(0, 'success', $list);
    }

}