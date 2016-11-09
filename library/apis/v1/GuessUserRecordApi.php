<?php
/**
 * 竞猜活动用户参与记录接口。
 * @author winerQin
 * @date 2016-11-09
 */

namespace apis\v1;

use apis\BaseApi;
use services\UserService;
use services\GuessService;

class GuessUserRecordApi extends BaseApi {

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
        $page     = $this->getInt('page', 1);
        $result   = GuessService::getUserGuessRecordList($user_id, $page, 20);
        $this->render(0, 'success', $result);
    }

}