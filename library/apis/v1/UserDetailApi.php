<?php
/**
 * 用户详情接口。
 * @author winerQin
 * @date 2016-10-20
 */

namespace apis\v1;

use apis\BaseApi;
use services\UserService;
use common\YUrl;

class UserDetailApi extends BaseApi {

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
        $detail   = UserService::getUserDetail($user_id);
        $detail['avatar'] = YUrl::filePath($detail['avatar']);
        $this->render(0, 'success', $detail);
    }

}