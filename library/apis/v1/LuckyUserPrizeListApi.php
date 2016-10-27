<?php
/**
 * 用户中奖记录接口。
 * @author winerQin
 * @date 2016-10-20
 */

namespace apis\v1;

use apis\BaseApi;
use services\LuckyService;
use services\UserService;

class LuckyUserPrizeListApi extends BaseApi {

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
        $goods_name = $this->getString('goods_name', '');
        $goods_type = $this->getString('goods_type', '');
        $page       = $this->getInt('page', 1);
        $result     = LuckyService::getUserLuckyPrizeList($user_id, $goods_name, $goods_type, $page, 20);
        $this->render(0, 'success', $result);
    }

}