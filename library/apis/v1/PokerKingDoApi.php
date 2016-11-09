<?php
/**
 * 扑克王翻版。
 * @author winerQin
 * @date 2016-11-09
 */

namespace apis\v1;

use apis\BaseApi;
use services\PokerKingService;
use services\UserService;

class PokerKingDoApi extends BaseApi {

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
        $money    = $this->getInt('money');
        $index    = $this->getInt('index');
        $result   = PokerKingService::startDo($user_id, $money, $index);
        $this->render(0, 'success', $result);
    }

}