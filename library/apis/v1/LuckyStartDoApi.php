<?php
/**
 * 用户抽奖接口。
 * @author winerQin
 * @date 2016-10-20
 */

namespace apis\v1;

use apis\BaseApi;
use services\LuckyService;
use services\UserService;

class LuckyStartDoApi extends BaseApi {

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
        $result = LuckyService::startDoLucky($user_id);
        $this->render(0, 'success', $result);
    }

}