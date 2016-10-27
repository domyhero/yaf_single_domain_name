<?php
/**
 * 聚合汽车站到站查询接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\Bus;
class JuheBusSearchToApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $from = $this->getString('from');
        $to   = $this->getString('to');
        $result  = Bus::queryAb($from, $to);
        $this->render(0, 'success', $result);
    }

}