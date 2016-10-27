<?php
/**
 * 聚合火车时刻表查询接口。
 * @author winerQin
 * @date 2016-10-27
 */

namespace apis\v1;

use apis\BaseApi;
use Juhe\Train;
class JuheTrainSearchApi extends BaseApi {

    /**
     * 逻辑处理。
     *
     * @see Api::runService()
     * @return bool
     */
    protected function runService() {
        $from   = $this->getString('from');
        $to     = $this->getString('to');
        $result = Train::query($from, $to);
        $this->render(0, 'success', $result);
    }

}