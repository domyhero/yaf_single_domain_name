<?php
/**
 * 彩票开奖结果列表接口。
 * @author winerQin
 * @date 2016-10-21
 */

namespace apis\v1;

use apis\BaseApi;
use services\LotteryService;

class LotteryResultListApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $page = $this->getInt('page', 1);
        $lottery_type = $this->getInt('lottery_type', -1);
        $goods_list = LotteryService::getLotteryResultList($lottery_type, $page, 20);
        $this->render(0, 'ok', $goods_list);
    }

}