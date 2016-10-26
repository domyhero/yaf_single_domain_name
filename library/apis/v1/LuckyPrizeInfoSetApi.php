<?php
/**
 * 中奖领取信息设置接口。
 * @author winerQin
 * @date 2016-10-26
 */

namespace apis\v1;

use apis\BaseApi;
use services\LuckyService;
use services\UserService;
class LuckyPrizeInfoSetApi extends BaseApi {

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
        $id         = $this->getInt('id');
        $data       = $this->getArray('data');
        $goods_list = LuckyService::setGetInfo($user_id, $id, $data);
        $this->render(0, 'success', $goods_list);
    }
}