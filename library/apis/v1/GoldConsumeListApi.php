<?php
/**
 * 用户金币消费列表接口。
 * @author winerQin
 * @date 2016-10-27
 */
namespace apis\v1;

use apis\BaseApi;
use services\GoldService;
use services\UserService;

class GoldConsumeListApi extends BaseApi {

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
        $consume_type = $this->getInt('consume_type', -1);
        $start_time   = $this->getString('start_time', '');
        $end_time     = $this->getString('end_time', '');
        $page         = $this->getInt('page', 1);
        $result       = GoldService::getUserGoldConsume($user_id, $consume_type, $start_time, $end_time, $page, 20);
        $this->render(0, 'success', $result);
    }

}