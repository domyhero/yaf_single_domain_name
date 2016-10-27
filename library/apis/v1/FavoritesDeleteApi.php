<?php
/**
 * 用户收藏删除接口。
 * @author winerQin
 * @date 2016-10-27
 */
namespace apis\v1;

use apis\BaseApi;
use services\FavoritesService;
use services\UserService;

class FavoritesAddApi extends BaseApi {

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
        $obj_type = $this->getInt('obj_type');
        $obj_id   = $this->getInt('obj_id');
        FavoritesService::delete($user_id, $obj_type, $obj_id);
        $this->render(0, 'success');
    }

}