<?php
/**
 * 发送找回密码验证码接口。
 * @author winerQin
 * @date 2016-10-20
 */

namespace apis\v1;

use apis\BaseApi;
use services\UserService;

class SendFindPwdCodeApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $find_type  = $this->getInt('find_type');
        $to_account = $this->getString('to_account');
        $detail = UserService::sendFindPwdCode($find_type, $to_account);
        $this->render(0, '验证码发送成功');
    }

}