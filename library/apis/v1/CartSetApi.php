<?php
/**
 * 购物车商品设置(添加/删除/修改)。
 * @author winerQin
 * @date 2016-10-26
 */

namespace apis\v1;

use apis\BaseApi;
use services\UserService;
use services\CartService;
class CartSetApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $token      = $this->getString('token');
        $userinfo   = UserService::checkAuth(UserService::LOGIN_MODE_API, $token);
        $user_id    = $userinfo['user_id'];
        $goods_id   = $this->getInt('goods_id');
        $product_id = $this->getInt('product_id');
        $quantity   = $this->getInt('quantity');
        CartService::setUserCartGoodsQuantity($user_id, $goods_id, $product_id, $quantity);
        $this->render(0, 'success');
    }

}