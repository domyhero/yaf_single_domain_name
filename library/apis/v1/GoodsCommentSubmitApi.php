<?php
/**
 * 商品评论提交接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use services\UserService;
use services\AppraiseService;

class GoodsCommentSubmitApi extends BaseApi {

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
        $goods_id = $this->getInt('goods_id');
        $order_id = $this->getInt('order_id');
        $score1   = $this->getInt('score1');
        $score2   = $this->getInt('score2');
        $score3   = $this->getInt('score3');
        $comment  = json_decode($this->getString('comment'));
        $page     = $this->getInt('page', 1);
        AppraiseService::buyerAppraise($user_id, $order_id, $score1, $score2, $score3, $comment);
        $this->render(0, 'success');
    }
}