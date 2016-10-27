<?php
/**
 * 用户修改密码接口。
 * @author winerQin
 * @date 2016-10-20
 */

namespace apis\v1;

use apis\BaseApi;
use services\UserService;

class UserEditPwdApi extends BaseApi {

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
        $old_pwd  = $this->getString('old_pwd');
        $new_pwd  = $this->getString('new_pwd');
        UserService::editPwd($user_id, $old_pwd, $new_pwd);
        $this->render(0, 'success');
    }

}