<?php
/**
 * 用户收货地址设置(添加/编辑)。
 * @author winerQin
 * @date 2016-10-26
 */

namespace apis\v1;

use apis\BaseApi;
use services\UserService;
use services\UserAddressService;
class UserAddressSetApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $token       = $this->getString('token');
        $userinfo    = UserService::checkAuth(UserService::LOGIN_MODE_API, $token);
        $user_id     = $userinfo['user_id'];
        $address_id  = $this->getInt('address_id', 0);
        $realname    = $this->getString('realname');
        $zipcode     = $this->getString('zipcode');
        $mobilephone = $this->getString('mobilephone');
        $address     = $this->getString('address');
        $district_id = $this->getInt('district_id');
        if ($address_id > 0) {
            UserAddressService::editAddress($user_id, $address_id, $realname, $zipcode, $mobilephone, $district_id, $address);
        } else {
            UserAddressService::addAddress($user_id, $realname, $zipcode, $mobilephone, $district_id, $address);
        }
        $this->render(0, '保存成功');
    }
}