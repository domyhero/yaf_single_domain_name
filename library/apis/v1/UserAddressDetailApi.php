<?php
/**
 * 用户收货地址详情接口。
 * @author winerQin
 * @date 2016-10-26
 */

namespace apis\v1;

use apis\BaseApi;
use services\UserService;
use services\UserAddressService;
class UserAddressDetailApi extends BaseApi {

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
        $address_id = $this->getInt('address_id');
        $result     = UserAddressService::deleteAddress($user_id, $address_id);
        $this->render(0, 'success', $result);
    }

}