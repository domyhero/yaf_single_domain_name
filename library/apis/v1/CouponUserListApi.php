<?php
/**
 * 用户优惠券列表接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use services\UserService;
use services\CouponService;

class CouponUserListApi extends BaseApi {

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
        $is_use   = $this->getInt('is_use');
        $result   = CouponService::getUserCouponList($user_id, $is_use, $page, 20);
        $this->render(0, 'success', $result);
    }

}