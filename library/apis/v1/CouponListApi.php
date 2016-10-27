<?php
/**
 * 平台优惠券列表接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use services\UserService;
use services\CouponService;

class CouponListApi extends BaseApi {

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
        $result   = CouponService::getUserToShopCouponList($page, 20);
        $this->render(0, 'success', $result);
    }

}