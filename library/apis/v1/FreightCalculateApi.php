<?php
/**
 * 商品运费计算接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use services\FreightService;
class CartSetApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $goods_list  = json_decode($this->getString('goods_list'));
        $district_id = $this->getInt('district_id', -1);
        $result = FreightService::calculateGoodsFreight($goods_list, $district_id);
        $this->render(0, 'success', $result);
    }

}