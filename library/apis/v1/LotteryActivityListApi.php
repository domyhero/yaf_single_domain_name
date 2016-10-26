<?php
/**
 * 彩票活动列表。
 * @author winerQin
 * @date 2016-10-20
 */

namespace apis\v1;

use apis\BaseApi;
use services\LotteryService;

class LotteryActivityListApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $page = $this->getInt('page', 1);
        $goods_list = LotteryService::getLotteryActivityList($page, 20);
        $this->render(0, 'success', $goods_list);
    }

}