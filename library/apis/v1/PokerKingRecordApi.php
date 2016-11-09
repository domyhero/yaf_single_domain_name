<?php
/**
 * 扑克王翻版记录。
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
        $token      = $this->getString('token');
        $userinfo   = UserService::checkAuth(UserService::LOGIN_MODE_API, $token);
        $user_id    = $userinfo['user_id'];
        $poker_type = $this->getInt('poker_type');
        $is_prize   = $this->getInt('is_prize', -1);
        $page       = $this->getInt('page', 1);
        $result = PokerKingService::getUserPokerKingRecordList($user_id, $poker_type, $is_prize, $page, 20);
        $this->render(0, 'success', $result);
    }

}