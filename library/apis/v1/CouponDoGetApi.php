<?php
/**
 * 用户领取优惠券接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use services\UserService;
use services\CouponService;

class CouponDoGetApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $token     = $this->getString('token');
        $userinfo  = UserService::checkAuth(UserService::LOGIN_MODE_API, $token);
        $user_id   = $userinfo['user_id'];
        $coupon_id = $this->getInt('coupon_id');
        CouponService::doGetCoupon($user_id, $coupon_id);
        $this->render(0, 'success');
    }

}