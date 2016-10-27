<?php
/**
 * 聚合历史上的今天事情详情接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\TodayHistory;
class JuheTodayHistoryDetailApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $event_id = $this->getInt('event_id');
        $detail = TodayHistory::queryDetail($event_id);
        $this->render(0, 'success', $detail);
    }
}