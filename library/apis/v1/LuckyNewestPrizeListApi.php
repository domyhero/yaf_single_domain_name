<?php
/**
 * 获取最新中奖记录。
 * @author winerQin
 * @date 2016-10-20
 */

namespace apis\v1;

use apis\BaseApi;
use services\LuckyService;

class LuckyNewestPrizeListApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $prize_list = LuckyService::getNewestLuckyPrizeList(20);
        $this->render(0, 'success', $prize_list);
    }

}