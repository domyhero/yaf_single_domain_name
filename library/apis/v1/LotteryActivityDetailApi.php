<?php
/**
 * 彩票活动详情接口。
 * @author winerQin
 * @date 2016-10-20
 */

namespace apis\v1;

use apis\BaseApi;
use services\LotteryService;

class LotteryActivityDetailApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $aid = $this->getInt('aid');
        $goods_list = LotteryService::getLotteryActivityDetail($aid);
        $this->render(0, 'success', $goods_list);
    }

}