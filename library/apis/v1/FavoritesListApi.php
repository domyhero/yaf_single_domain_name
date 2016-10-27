<?php
/**
 * 用户收藏列表接口。
 * @author winerQin
 * @date 2016-10-27
 */
namespace apis\v1;

use apis\BaseApi;
use services\FavoritesService;
use services\UserService;

class FavoritesListApi extends BaseApi {

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
        $page     = $this->getInt('page', 1);
        $result   = FavoritesService::getList($user_id, $obj_type, $page, 20);
        $this->render(0, 'success', $result);
    }

}