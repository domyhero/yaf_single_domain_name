<?php
/**
 * 聚合机器人接口。
 * @author winerQin
 * @date 2016-10-26
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\Robot;
use services\UserService;
class JuheRobotApi extends BaseApi {

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
        $str = $this->getString('str');
        $loc = $this->getString('loc', '');
        $result = Robot::ask($str, $loc, $user_id);
        $this->render(0, 'success', $result);
    }

}