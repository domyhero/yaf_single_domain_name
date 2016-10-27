<?php
/**
 * 商品追平接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use services\UserService;
use services\AppraiseService;

class GoodsCommentAppendApi extends BaseApi {

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
        $order_id     = $this->getInt('order_id');
        $sub_order_id = $this->getInt('sub_order_id');
        $comment      = $this->getString('comment');
        AppraiseService::buyerAppendAppraise($user_id, $order_id, $sub_order_id, $comment);
        $this->render(0, 'success');
    }
}