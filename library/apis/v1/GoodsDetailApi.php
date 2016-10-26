<?php
/**
 * 商品详情接口。
 * @author winerQin
 * @date 2016-06-06
 */
namespace apis\v1;

use apis\BaseApi;
use services\GoodsService;

class GoodsDetailApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $goods_id     = $this->getInt('goods_id');
        $product_id   = $this->getInt('product_id');
        $goods_detail = GoodsService::getGoodsDetail($goods_id);
        $this->render(0, 'success', $goods_detail);
    }

}