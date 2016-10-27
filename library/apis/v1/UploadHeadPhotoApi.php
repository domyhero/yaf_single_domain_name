<?php
/**
 * 用户头像上传接口。
 * @author winerQin
 * @date 2016-10-27
 */
namespace apis\v1;

use apis\BaseApi;
use services\UserService;
use services\UploadService;

class UploadHeadPhoto extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $token        = $this->getString('token');
        $userinfo     = UserService::checkAuth(UserService::LOGIN_MODE_API, $token);
        $user_id      = $userinfo['user_id'];
        $file_content = $this->getString('file_content');
        $result       = UploadService::saveImage($file_content, 2, $user_id, 'avatar');
        $this->render(0, 'success', $result);
    }

}