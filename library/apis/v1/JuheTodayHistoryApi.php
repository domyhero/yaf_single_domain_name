<?php
/**
 * 聚合历史上的今天接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\TodayHistory;
class JuheTodayHistoryApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $date = $this->getString('date', '');
        $result = TodayHistory::queryEvent($date);
        $this->render(0, 'success', $result);
    }
}