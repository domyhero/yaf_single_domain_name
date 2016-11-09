<?php
/**
 * 竞猜活动用户投注接口。
 * @author winerQin
 * @date 2016-11-09
 */

namespace apis\v1;

use apis\BaseApi;
use services\UserService;
use services\GuessService;

class GuessUserDoApi extends BaseApi {

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
        $guess_id     = $this->getInt('guess_id');
        $option_index = $this->getString('options_index');
        $bet_gold     = $this->getInt('bet_gold');
        $user_gold    = GuessService::startDo($user_id, $guess_id, $option_index, $bet_gold);
        $this->render(0, 'success', ['user_gold' => $user_gold]);
    }

}