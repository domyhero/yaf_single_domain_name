<?php
/**
 * 抽奖商品列表。
 * @author winerQin
 * @date 2016-10-0196
 */
namespace apis\v1;

use apis\BaseApi;

class LuckyGoodsListApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $goods_id   = $this->getInt('goods_id');
        $product_id = $this->getInt('product_id');
        $this->render(0, 'ok');
    }

}