<?php
/**
 * 用户找回密码接口。
 * @author winerQin
 * @date 2016-10-20
 */

namespace apis\v1;

use apis\BaseApi;
use services\UserService;

class UserFindPwdApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $find_type  = $this->getInt('find_type');
        $to_account = $this->getString('to_account');
        $code       = $this->getString('code');
        $new_pwd    = $this->getString('new_pwd');
        UserService::findPwd($find_type, $to_account, $code, $new_pwd);
        $this->render(0, '密码设置成功');
    }

}