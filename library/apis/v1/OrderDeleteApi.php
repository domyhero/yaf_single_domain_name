<?php
/**
 * 用户订单删除接口。
 * @author winerQin
 * @date 2016-10-26
 */

namespace apis\v1;

use apis\BaseApi;
use services\UserService;
use services\OrderService;
class OrderDeleteApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $token    = $this->getString('token');
        $userinfo = UserService::checkAuth(UserService::LOGIN_MODE_API, $token);
        $user_id  = $userinfo['user_id'];
        $order_id = $this->getInt('order_id');
        OrderService::deleteOrder($user_id, $order_id);
        $this->render(0, '删除成功');
    }

}