<?php
/**
 * 商品列表接口。
 * @author winerQin
 * @date 2016-06-06
 */
namespace apis\v1;

use apis\BaseApi;
use services\GoodsService;

class GoodsListApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $keywords    = $this->getString('keywords', '');
        $cat_id      = $this->getInt('cat_id', - 1);
        $order_by    = $this->getString('order_by', 'price');
        $page        = $this->getInt('page', 1);
        $start_price = $this->getString('start_price', '');
        $end_price   = $this->getString('end_price', '');
        $count       = 10;
        $result      = GoodsService::getGoodsList($keywords, $cat_id, $start_price, $end_price, $page, $count);
        $this->render(0, 'success', $result);
    }

}